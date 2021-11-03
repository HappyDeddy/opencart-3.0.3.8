<?php
class ControllerExtensionPaymentGlobalPayments extends Controller {
	private $error = array();
	
	public function index() {
		$this->load->language('extension/payment/globalpayments');
						
		$this->load->model('extension/payment/globalpayments');
		$this->load->model('checkout/order');
		$this->load->model('localisation/zone');
		$this->load->model('localisation/country');
				
		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
		
		// Setting
		$_config = new Config();
		$_config->load('globalpayments');
			
		$config_setting = $_config->get('globalpayments_setting');
		
		$setting = array_replace_recursive((array)$config_setting, (array)$this->config->get('payment_globalpayments_setting'));
						
		$data['merchant_id'] = $this->config->get('payment_globalpayments_merchant_id');
		$data['account_id'] = $this->config->get('payment_globalpayments_account_id');
		$data['secret'] = $this->config->get('payment_globalpayments_secret');
		$data['checkout'] = $this->config->get('payment_globalpayments_checkout');
		$data['environment'] = $this->config->get('payment_globalpayments_environment');
		$data['service'] = $setting['service'][$data['checkout']][$data['environment']];
		$data['hpp_url'] = $this->url->link('extension/payment/globalpayments/hpp', '', true);
		$data['api_url'] = $this->url->link('extension/payment/globalpayments/api', '', true);
		$data['api_secure_2_check_version_url'] = $this->url->link('extension/payment/globalpayments/apiSecure2CheckVersion', '', true);
		$data['api_secure_2_initiate_authentication_url'] = $this->url->link('extension/payment/globalpayments/apiSecure2InitiateAuthentication', '', true);
		$data['api_secure_2_authorization_url'] = $this->url->link('extension/payment/globalpayments/apiSecure2Authorization', '', true);
		$data['api_secure_1_setup_url'] = $this->url->link('extension/payment/globalpayments/apiSecure1Setup', '', true);
		$data['api_secure_1_authorization_url'] = $this->url->link('extension/payment/globalpayments/apiSecure1Authorization', '', true);
		
		if ($data['checkout'] == 'hpp') {
			require_once DIR_SYSTEM . 'library/globalpayments/GlobalPayments.php';

			$servicesConfig = new GlobalPayments\Api\ServicesConfig();
		
			$servicesConfig->merchantId = $data['merchant_id'];
			$servicesConfig->accountId = $data['account_id'];
			$servicesConfig->sharedSecret = $data['secret'];
			$servicesConfig->serviceUrl = $data['service']['url'];
			
			$servicesConfig->hostedPaymentConfig = new GlobalPayments\Api\HostedPaymentConfig();
			$servicesConfig->hostedPaymentConfig->version = GlobalPayments\Api\Entities\Enums\HppVersion::VERSION_2;
			$servicesConfig->hostedPaymentConfig->responseUrl = $this->url->link('extension/payment/globalpayments/apmRedirect', '', true);
			$servicesConfig->hostedPaymentConfig->statusUpdateUrl = $this->url->link('extension/payment/globalpayments/apmUpdateOrderStatus', '', true);
			
			$hostedService = new GlobalPayments\Api\Services\HostedService($servicesConfig);
						
			$hostedPaymentData = new GlobalPayments\Api\Entities\HostedPaymentData();
				
			$hostedPaymentData->paymentMethods = 'cards|paypal|testpay|sepapm|sofort';
			$hostedPaymentData->customerFirstName = $order_info['firstname'];
			$hostedPaymentData->customerLastName = $order_info['lastname'];
			$hostedPaymentData->customerEmail = $order_info['email'];	
			$hostedPaymentData->addressesMatch = false;
													
			$billingAddress = new GlobalPayments\Api\Entities\Address();
			
			$billingAddress->streetAddress1 = $order_info['payment_address_1'];
			$billingAddress->streetAddress2 = $order_info['payment_address_2'];
			$billingAddress->city = $order_info['payment_city'];
			$billingAddress->postalCode = $order_info['payment_postcode'];
			
			if ($order_info['payment_zone_id']) {
				$zone_info = $this->model_localisation_zone->getZone($order_info['payment_zone_id']);
			
				if ($zone_info) {
					$billingAddress->state = $zone_info['code'];
				}
			}
						
			if ($order_info['payment_country_id']) {
				$country_info = $this->model_localisation_country->getCountry($order_info['payment_country_id']);
			
				if ($country_info && isset($setting['country'][$country_info['iso_code_3']])) {
					$billingAddress->country = $setting['country'][$country_info['iso_code_3']]['country_code'];
										
					$telephone = preg_replace('/[^0-9]/', '', $order_info['telephone']);
					$telephone = preg_replace('/^' . $setting['country'][$country_info['iso_code_3']]['phone_code'] . '/', '', $telephone);
					
					$hostedPaymentData->customerCountry = $country_info['iso_code_2'];
					$hostedPaymentData->customerPhoneMobile = $setting['country'][$country_info['iso_code_3']]['phone_code'] . '|' . $telephone;
				}
			}
						
			if ($this->cart->hasShipping()) {
				$shippingAddress = new GlobalPayments\Api\Entities\Address();
				
				$shippingAddress->streetAddress1 = $order_info['shipping_address_1'];
				$shippingAddress->streetAddress2 = $order_info['shipping_address_2'];
				$shippingAddress->city = $order_info['shipping_city'];
				$shippingAddress->postalCode = $order_info['shipping_postcode'];
			
				if ($order_info['shipping_zone_id']) {
					$zone_info = $this->model_localisation_zone->getZone($order_info['shipping_zone_id']);
			
					if ($zone_info) {
						$shippingAddress->state = $zone_info['code'];
					}
				}
			
				if ($order_info['shipping_country_id']) {
					$country_info = $this->model_localisation_country->getCountry($order_info['shipping_country_id']);
			
					if ($country_info && isset($setting['country'][$country_info['iso_code_3']])) {
						$shippingAddress->country = $setting['country'][$country_info['iso_code_3']]['country_code'];
					}	
				}
			}

			try {
				if ($this->cart->hasShipping()) {
					$data['hpp'] = $hostedService->charge($order_info['total'])->withCurrency($order_info['currency_code'])->withHostedPaymentData($hostedPaymentData)->withAddress($billingAddress, GlobalPayments\Api\Entities\Enums\AddressType::BILLING)->withAddress($shippingAddress, GlobalPayments\Api\Entities\Enums\AddressType::SHIPPING)->serialize();
				} else {
					$data['hpp'] = $hostedService->charge($order_info['total'])->withCurrency($order_info['currency_code'])->withHostedPaymentData($hostedPaymentData)->withAddress($billingAddress, GlobalPayments\Api\Entities\Enums\AddressType::BILLING)->serialize();
				}
				
				$response = json_decode($data['hpp'], true);
				
				$this->model_extension_payment_globalpayments->addOrder($this->session->data['order_id'], $response['ORDER_ID']);
				
			} catch (GlobalPayments\Api\Entities\Exceptions\ApiException $exception) {
				$this->model_extension_payment_globalpayments->log($exception, $responseCode . ' ' . $responseMessage);
				
				$this->error['warning'] = $exception->responseCode . ' ' . $exception->responseMessage;
			}	
		}
		
		if ($data['checkout'] == 'api') {
			$data['form_align'] = $setting['checkout']['api']['form_align'];
			$data['form_size'] = $setting['checkout']['api']['form_size'];
			$data['form_width'] = $setting['form_width'][$data['form_size']];
			$data['secure_status'] = $setting['checkout']['api']['secure_status'];
			
			$data['months'] = array();

			for ($i = 1; $i <= 12; $i++) {
				$data['months'][] = array(
					'text'  => strftime('%B', mktime(0, 0, 0, $i, 1, 2000)),
					'value' => sprintf('%02d', $i)
				);
			}

			$today = getdate();

			$data['year_expire'] = array();

			for ($i = $today['year']; $i < $today['year'] + 11; $i++) {
				$data['year_expire'][] = array(
					'text'  => strftime('%Y', mktime(0, 0, 0, 1, 1, $i)),
					'value' => strftime('%Y', mktime(0, 0, 0, 1, 1, $i))
				);
			}
		}
		
		return $this->load->view('extension/payment/globalpayments', $data);
	}
	
	public function hpp() {
		$this->load->language('extension/payment/globalpayments');
						
		$this->load->model('extension/payment/globalpayments');
		
		if (isset($this->request->post['hppResponse'])) {
			$this->load->model('checkout/order');
			
			$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
		
			// Setting
			$_config = new Config();
			$_config->load('globalpayments');
			
			$config_setting = $_config->get('globalpayments_setting');
		
			$setting = array_replace_recursive((array)$config_setting, (array)$this->config->get('payment_globalpayments_setting'));
						
			$merchant_id = $this->config->get('payment_globalpayments_merchant_id');
			$account_id = $this->config->get('payment_globalpayments_account_id');
			$secret = $this->config->get('payment_globalpayments_secret');
			$checkout = $this->config->get('payment_globalpayments_checkout');
			$environment = $this->config->get('payment_globalpayments_environment');
			$settlement_method = $this->config->get('payment_globalpayments_settlement_method');
			$service = $setting['service'][$checkout][$environment];
						
			require_once DIR_SYSTEM . 'library/globalpayments/GlobalPayments.php';

			$servicesConfig = new GlobalPayments\Api\ServicesConfig();
		
			$servicesConfig->merchantId = $merchant_id;
			$servicesConfig->accountId = $account_id;
			$servicesConfig->sharedSecret = $secret;
			$servicesConfig->serviceUrl = $service['url'];
				
			$hostedService = new GlobalPayments\Api\Services\HostedService($servicesConfig);
									
			try {
				$response = $hostedService->parseResponse(html_entity_decode($this->request->post['hppResponse']), true);
			
				$responseCode = $response->responseCode;
				$responseMessage = $response->responseMessage;
				$orderId = $response->orderId;
				$responseValues = $response->responseValues;
				
				if ($responseCode == '00') {
					$this->model_checkout_order->addOrderHistory($this->session->data['order_id'], $this->config->get('config_order_status_id'));
					
					if ($settlement_method == 'auto') {
						$this->model_checkout_order->addOrderHistory($this->session->data['order_id'], $setting['order_status']['success_settled']['id'], $responseMessage);
					} else {
						$this->model_checkout_order->addOrderHistory($this->session->data['order_id'], $setting['order_status']['success_unsettled']['id'], $responseMessage);
					}
				} else {
					$this->error['warning'] = $responseCode . ' ' . $responseMessage . ' ' . sprintf($this->language->get('error_payment'), $this->url->link('information/contact', '', true));
				}
			} catch (GlobalPayments\Api\Entities\Exceptions\ApiException $exception) {
				$responseCode = $exception->responseCode;
				$responseMessage = $exception->responseMessage;
				
				if (($responseCode >= 100) && ($responseCode < 200)) {
					$this->model_checkout_order->addOrderHistory($this->session->data['order_id'], $setting['order_status']['decline']['id'], $responseMessage);
				} 
				
				if ($responseCode >= 200) {
					$this->model_checkout_order->addOrderHistory($this->session->data['order_id'], $setting['order_status']['failed']['id'], $responseMessage);
				}
				
				$this->model_extension_payment_globalpayments->log($exception, $responseCode . ' ' . $responseMessage);
				
				$this->error['warning'] = $responseCode . ' ' . $responseMessage . ' ' . sprintf($this->language->get('error_payment'), $this->url->link('information/contact', '', true));
			}
		}	
		
		if (!$this->error) {
			$data['success'] = $this->url->link('checkout/success', '', true);
		}
		
		$data['error'] = $this->error;
				
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($data));		
	}
	
	public function api() {
		$this->load->language('extension/payment/globalpayments');
										
		$this->load->model('extension/payment/globalpayments');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {							
			$this->load->model('checkout/order');
			
			$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
		
			// Setting
			$_config = new Config();
			$_config->load('globalpayments');
			
			$config_setting = $_config->get('globalpayments_setting');
		
			$setting = array_replace_recursive((array)$config_setting, (array)$this->config->get('payment_globalpayments_setting'));
						
			$merchant_id = $this->config->get('payment_globalpayments_merchant_id');
			$account_id = $this->config->get('payment_globalpayments_account_id');
			$secret = $this->config->get('payment_globalpayments_secret');
			$checkout = $this->config->get('payment_globalpayments_checkout');
			$environment = $this->config->get('payment_globalpayments_environment');
			$settlement_method = $this->config->get('payment_globalpayments_settlement_method');
			$service = $setting['service'][$checkout][$environment];
						
			require_once DIR_SYSTEM . 'library/globalpayments/GlobalPayments.php';

			$servicesConfig = new GlobalPayments\Api\ServicesConfig();
		
			$servicesConfig->merchantId = $merchant_id;
			$servicesConfig->accountId = $account_id;
			$servicesConfig->sharedSecret = $secret;
			$servicesConfig->serviceUrl = $service['url'];
						
			GlobalPayments\Api\ServicesContainer::configure($servicesConfig);
			
			$card = new GlobalPayments\Api\PaymentMethods\CreditCardData();
			
			$card->number = $this->request->post['card_number'];
			$card->cardHolderName = $this->request->post['card_holder_name'];
			$card->expMonth = $this->request->post['card_expire_date_month'];
			$card->expYear = $this->request->post['card_expire_date_year'];
			$card->cvn = $this->request->post['card_cvn'];
						
			try {
				$response = $card->charge($order_info['total'])->withCurrency($order_info['currency_code'])->execute();
				
				$responseCode = $response->responseCode;
				$responseMessage = $response->responseMessage;
				$orderId = $response->orderId;
				$authCode = $response->authorizationCode;
				$paymentsReference = $response->transactionId;
								
				$this->model_extension_payment_globalpayments->addOrder($this->session->data['order_id'], $orderId);
				
				if ($responseCode == '00') {
					$this->model_checkout_order->addOrderHistory($this->session->data['order_id'], $this->config->get('config_order_status_id'));
					
					if ($settlement_method == 'auto') {
						$this->model_checkout_order->addOrderHistory($this->session->data['order_id'], $setting['order_status']['success_settled']['id'], $responseMessage);
					} else {
						$this->model_checkout_order->addOrderHistory($this->session->data['order_id'], $setting['order_status']['success_unsettled']['id'], $responseMessage);
					}
				} else {
					$this->error['warning'] = $responseCode . ' ' . $responseMessage . ' ' . sprintf($this->language->get('error_payment'), $this->url->link('information/contact', '', true));
				}
			} catch (GlobalPayments\Api\Entities\Exceptions\ApiException $exception) {
				$responseCode = $exception->responseCode;
				$responseMessage = $exception->responseMessage;
				
				if (($responseCode >= 100) && ($responseCode < 200)) {
					$this->model_checkout_order->addOrderHistory($this->session->data['order_id'], $setting['order_status']['decline']['id'], $responseMessage);
				} 
				
				if ($responseCode >= 200) {
					$this->model_checkout_order->addOrderHistory($this->session->data['order_id'], $setting['order_status']['failed']['id'], $responseMessage);
				}

				$this->model_extension_payment_globalpayments->log($exception, $responseCode . ' ' . $responseMessage);
				
				$this->error['warning'] = $responseCode . ' ' . $responseMessage . ' ' . sprintf($this->language->get('error_payment'), $this->url->link('information/contact', '', true));
			}
		}
		
		if (!$this->error) {
			$data['success'] = $this->url->link('checkout/success', '', true);
		}
		
		$data['error'] = $this->error;
				
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($data));		
	}
	
	public function apiSecure2CheckVersion() {
		$this->load->language('extension/payment/globalpayments');
		
		$this->load->model('extension/payment/globalpayments');
		
		$input_data = json_decode(html_entity_decode(file_get_contents('php://input')), true);
		
		$output_data = array();
			
		if (isset($input_data['card']['number'])) {
			$merchant_id = $this->config->get('payment_globalpayments_merchant_id');
			$account_id = $this->config->get('payment_globalpayments_account_id');
			$secret = $this->config->get('payment_globalpayments_secret');
			$checkout = $this->config->get('payment_globalpayments_checkout');
			$environment = $this->config->get('payment_globalpayments_environment');
			$settlement_method = $this->config->get('payment_globalpayments_settlement_method');
			
			require_once DIR_SYSTEM . 'library/globalpayments/GlobalPayments.php';

			$servicesConfig = new GlobalPayments\Api\ServicesConfig();
		
			$servicesConfig->merchantId = $merchant_id;
			$servicesConfig->accountId = $account_id;
			$servicesConfig->sharedSecret = $secret;
			$servicesConfig->methodNotificationUrl = $this->url->link('extension/payment/globalpayments/apiSecure2MethodNotificationUrl', '', true);
			$servicesConfig->challengeNotificationUrl = $this->url->link('extension/payment/globalpayments/apiSecure2ChallengeNotificationUrl', '', true);
			$servicesConfig->merchantContactUrl = $this->url->link('information/contact', '', true);
			$servicesConfig->secure3dVersion = GlobalPayments\Api\Entities\Enums\Secure3dVersion::TWO;
						
			GlobalPayments\Api\ServicesContainer::configure($servicesConfig);

			$card = new GlobalPayments\Api\PaymentMethods\CreditCardData();
			
			$card->number = $input_data['card']['number'];

			try {
				$threeDSecureData = GlobalPayments\Api\Services\Secure3dService::checkEnrollment($card)->execute(GlobalPayments\Api\Entities\Enums\Secure3dVersion::TWO);
			} catch (GlobalPayments\Api\Entities\Exceptions\ApiException $exception) {
				$responseCode = $exception->responseCode;
				$responseMessage = $exception->responseMessage;
				
				$this->model_extension_payment_globalpayments->log($exception, $responseCode . ' ' . $responseMessage);
				
				$this->error['warning'] = $responseCode . ' ' . $responseMessage . ' ' . sprintf($this->language->get('error_payment'), $this->url->link('information/contact', '', true));
			}

			if (isset($threeDSecureData)) {
				$enrolled = $threeDSecureData->enrolled;
				$serverTransactionId = $threeDSecureData->serverTransactionId;
				$dsStartProtocolVersion = $threeDSecureData->directoryServerStartVersion;
				$dsEndProtocolVersion = $threeDSecureData->directoryServerEndVersion;
				$acsStartProtocolVersion = $threeDSecureData->acsStartVersion;
				$acsEndProtocolVersion = $threeDSecureData->acsEndVersion;
				$methodUrl = $threeDSecureData->issuerAcsUrl;
				$encodedMethodData = $threeDSecureData->payerAuthenticationRequest;

				$output_data['enrolled'] = $enrolled;

				if ($enrolled === true) {
					$output_data['serverTransactionId'] = $serverTransactionId;
					$output_data['methodUrl'] = $methodUrl;
					$output_data['methodData'] = $encodedMethodData;
				}
			}
		}
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($output_data));
	}
	
	public function apiSecure2InitiateAuthentication() {
		$this->load->language('extension/payment/globalpayments');
		
		$this->load->model('extension/payment/globalpayments');
		
		$input_data = json_decode(html_entity_decode(file_get_contents('php://input')), true);
				
		$output_data = array();
						
		if (isset($input_data['card'])) {
			$this->load->model('checkout/order');
			$this->load->model('localisation/zone');
			$this->load->model('localisation/country');
			
			$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
			
			// Setting
			$_config = new Config();
			$_config->load('globalpayments');
			
			$config_setting = $_config->get('globalpayments_setting');
		
			$setting = array_replace_recursive((array)$config_setting, (array)$this->config->get('payment_globalpayments_setting'));
			
			$merchant_id = $this->config->get('payment_globalpayments_merchant_id');
			$account_id = $this->config->get('payment_globalpayments_account_id');
			$secret = $this->config->get('payment_globalpayments_secret');
			$checkout = $this->config->get('payment_globalpayments_checkout');
			$environment = $this->config->get('payment_globalpayments_environment');
			$settlement_method = $this->config->get('payment_globalpayments_settlement_method');
			
			require_once DIR_SYSTEM . 'library/globalpayments/GlobalPayments.php';

			$servicesConfig = new GlobalPayments\Api\ServicesConfig();
		
			$servicesConfig->merchantId = $merchant_id;
			$servicesConfig->accountId = $account_id;
			$servicesConfig->sharedSecret = $secret;
			$servicesConfig->methodNotificationUrl = $this->url->link('extension/payment/globalpayments/apiSecure2MethodNotificationUrl', '', true);
			$servicesConfig->challengeNotificationUrl = $this->url->link('extension/payment/globalpayments/apiSecure2ChallengeNotificationUrl', '', true);
			$servicesConfig->merchantContactUrl = $this->url->link('information/contact', '', true);
			$servicesConfig->secure3dVersion = GlobalPayments\Api\Entities\Enums\Secure3dVersion::TWO;
						
			GlobalPayments\Api\ServicesContainer::configure($servicesConfig);

			$card = new GlobalPayments\Api\PaymentMethods\CreditCardData();
			
			$card->number = $input_data['card']['number'];			
			$card->expMonth = $input_data['card']['expiryMonth'];
			$card->expYear = $input_data['card']['expiryYear'];
			$card->cvn = $input_data['card']['securityCode'];
			$card->cardHolderName = $input_data['card']['cardHolderName'];

			$billingAddress = new GlobalPayments\Api\Entities\Address();
			
			$billingAddress->streetAddress1 = $order_info['payment_address_1'];
			$billingAddress->streetAddress2 = $order_info['payment_address_2'];
			$billingAddress->city = $order_info['payment_city'];
			$billingAddress->postalCode = $order_info['payment_postcode'];
			
			$telephone = '';
			$telephone_code = '';
			
			if ($order_info['payment_zone_id']) {
				$zone_info = $this->model_localisation_zone->getZone($order_info['payment_zone_id']);
			
				if ($zone_info) {
					$billingAddress->state = $zone_info['code'];
				}
			}
			
			if ($order_info['payment_country_id']) {
				$country_info = $this->model_localisation_country->getCountry($order_info['payment_country_id']);
			
				if ($country_info && isset($setting['country'][$country_info['iso_code_3']])) {
					$billingAddress->countryCode = $setting['country'][$country_info['iso_code_3']]['country_code'];

					$telephone = preg_replace('/[^0-9]/', '', $order_info['telephone']);
					$telephone = preg_replace('/^' . $setting['country'][$country_info['iso_code_3']]['phone_code'] . '/', '', $telephone);
					
					$telephone_code = $setting['country'][$country_info['iso_code_3']]['phone_code'];
				}
			}

			if ($this->cart->hasShipping()) {
				$shippingAddress = new GlobalPayments\Api\Entities\Address();
			
				$shippingAddress->streetAddress1 = $order_info['shipping_address_1'];
				$shippingAddress->streetAddress2 = $order_info['shipping_address_2'];
				$shippingAddress->city = $order_info['shipping_city'];
				$shippingAddress->postalCode = $order_info['shipping_postcode'];
						
				if ($order_info['shipping_zone_id']) {
					$zone_info = $this->model_localisation_zone->getZone($order_info['shipping_zone_id']);
			
					if ($zone_info) {
						$shippingAddress->state = $zone_info['code'];
					}
				}
			
				if ($order_info['shipping_country_id']) {
					$country_info = $this->model_localisation_country->getCountry($order_info['shipping_country_id']);
			
					if ($country_info && isset($setting['country'][$country_info['iso_code_3']])) {
						$shippingAddress->countryCode = $setting['country'][$country_info['iso_code_3']]['country_code'];
					}	
				}
			}
			
			$browserData = new GlobalPayments\Api\Entities\BrowserData();
			
			$browserData->acceptHeader = "text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8";
			$browserData->colorDepth = $input_data['browserData']['colorDepth'];
			$browserData->ipAddress = $this->request->server['REMOTE_ADDR'];
			$browserData->javaEnabled = $input_data['browserData']['javaEnabled'];
			$browserData->language = $input_data['browserData']['language'];
			$browserData->screenHeight = $input_data['browserData']['screenHeight'];
			$browserData->screenWidth = $input_data['browserData']['screenWidth'];
			$browserData->challengWindowSize = $input_data['challengeWindow']['windowSize'];
			$browserData->timeZone = $input_data['browserData']['timezoneOffset'];
			$browserData->userAgent = $input_data['browserData']['userAgent'];

			$threeDSecureData = new GlobalPayments\Api\Entities\ThreeDSecure();
			
			$threeDSecureData->serverTransactionId = $input_data['serverTransactionId'];

			try {
				if ($this->cart->hasShipping()) {
					$threeDSecureData = GlobalPayments\Api\Services\Secure3dService::initiateAuthentication($card, $threeDSecureData)
						->withAmount($order_info['total'])
						->withCurrency($order_info['currency_code'])
						->withOrderCreateDate(date('Y-m-d H:i:s'))
						->withCustomerEmail($order_info['email'])
						->withAddress($billingAddress, GlobalPayments\Api\Entities\Enums\AddressType::BILLING)
						->withAddress($shippingAddress, GlobalPayments\Api\Entities\Enums\AddressType::SHIPPING)
						->withBrowserData($browserData)
						->withMethodUrlCompletion(GlobalPayments\Api\Entities\Enums\MethodUrlCompletion::YES)
						->withMobileNumber($telephone_code, $telephone)
						->execute(GlobalPayments\Api\Entities\Enums\Secure3dVersion::TWO);
				} else {
					$threeDSecureData = GlobalPayments\Api\Services\Secure3dService::initiateAuthentication($card, $threeDSecureData)
						->withAmount($order_info['total'])
						->withCurrency($order_info['currency_code'])
						->withOrderCreateDate(date('Y-m-d H:i:s'))
						->withCustomerEmail($order_info['email'])
						->withAddress($billingAddress, GlobalPayments\Api\Entities\Enums\AddressType::BILLING)
						->withBrowserData($browserData)
						->withMethodUrlCompletion(GlobalPayments\Api\Entities\Enums\MethodUrlCompletion::YES)
						->withMobileNumber($telephone_code, $telephone)
						->execute(GlobalPayments\Api\Entities\Enums\Secure3dVersion::TWO);
				}
			} catch (GlobalPayments\Api\Entities\Exceptions\ApiException $exception) {
				$responseCode = $exception->responseCode;
				$responseMessage = $exception->responseMessage;
				
				$this->model_extension_payment_globalpayments->log($exception, $responseCode . ' ' . $responseMessage);
				
				$this->error['warning'] = $responseCode . ' ' . $responseMessage . ' ' . sprintf($this->language->get('error_payment'), $this->url->link('information/contact', '', true));
			}

			$status = $threeDSecureData->status;
			
			if ($status !== 'CHALLENGE_REQUIRED') {
				$authenticationValue = $threeDSecureData->authenticationValue; 
				$dsTransId = $threeDSecureData->directoryServerTransactionId;
				$messageVersion = $threeDSecureData->messageVersion;
				$eci = $threeDSecureData->eci;
				
				$output_data['status'] = $status;
			} else {
				$challengeRequestUrl = $threeDSecureData->issuerAcsUrl;
				$encodedCreq = $threeDSecureData->payerAuthenticationRequest;
				$challengeMandated = $threeDSecureData->challengeMandated;

				$output_data = array(
					'status' => $status,
					'challengeMandated' => $challengeMandated,
					'challenge' => array(
						'requestUrl' => $challengeRequestUrl,
						'encodedChallengeRequest' => $encodedCreq
					)
				);
			}
		}
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($output_data));
	}
	
	public function apiSecure2Authorization() {
		$this->load->language('extension/payment/globalpayments');
										
		$this->load->model('extension/payment/globalpayments');
		
		if (isset($this->request->post['authenticationData']) && $this->validate()) {
			$this->load->model('checkout/order');
			
			$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
		
			// Setting
			$_config = new Config();
			$_config->load('globalpayments');
			
			$config_setting = $_config->get('globalpayments_setting');
		
			$setting = array_replace_recursive((array)$config_setting, (array)$this->config->get('payment_globalpayments_setting'));
						
			$merchant_id = $this->config->get('payment_globalpayments_merchant_id');
			$account_id = $this->config->get('payment_globalpayments_account_id');
			$secret = $this->config->get('payment_globalpayments_secret');
			$checkout = $this->config->get('payment_globalpayments_checkout');
			$environment = $this->config->get('payment_globalpayments_environment');
			$settlement_method = $this->config->get('payment_globalpayments_settlement_method');
			$service = $setting['service'][$checkout][$environment];
			
			$authentication_data = json_decode(htmlspecialchars_decode($this->request->post['authenticationData']), true);
			
			require_once DIR_SYSTEM . 'library/globalpayments/GlobalPayments.php';
										
			if ($authentication_data['status'] !== 'CHALLENGE_REQUIRED') {
				$secure_scenario_code = strtolower($authentication_data['status']);
			
				if (isset($setting['secure_2_scenario'][$secure_scenario_code]) && isset($setting['checkout']['api']['secure_2_scenario'][$secure_scenario_code]) && !$setting['checkout']['api']['secure_2_scenario'][$secure_scenario_code]) {
					$this->error['warning'] = $this->language->get($setting['secure_2_scenario'][$secure_scenario_code]['error']) . ' ' . sprintf($this->language->get('error_payment'), $this->url->link('information/contact', '', true));
				}
			} elseif (isset($authentication_data['challenge']['response']['data']['transStatus'])) {
				if (($authentication_data['challenge']['response']['data']['transStatus'] != 'Y') && !$setting['checkout']['api']['secure_2_scenario']['authentication_failed']) {
					$this->error['warning'] = $this->language->get($setting['secure_2_scenario']['authentication_failed']['error']) . ' ' . sprintf($this->language->get('error_payment'), $this->url->link('information/contact', '', true));
				} else {
					$serverTransactionId = $authentication_data['challenge']['response']['data']['threeDSServerTransID'];

					$servicesConfig = new GlobalPayments\Api\ServicesConfig();
		
					$servicesConfig->merchantId = $merchant_id;
					$servicesConfig->accountId = $account_id;
					$servicesConfig->sharedSecret = $secret;
					$servicesConfig->serviceUrl = $service['url'];
					$servicesConfig->methodNotificationUrl = $this->url->link('extension/payment/globalpayments/apiSecure2MethodNotificationUrl', '', true);
					$servicesConfig->challengeNotificationUrl = $this->url->link('extension/payment/globalpayments/apiSecure2ChallengeNotificationUrl', '', true);
					$servicesConfig->merchantContactUrl = $this->url->link('information/contact', '', true);
					$servicesConfig->secure3dVersion = GlobalPayments\Api\Entities\Enums\Secure3dVersion::TWO;
									
					GlobalPayments\Api\ServicesContainer::configure($servicesConfig);
								
					$threeDSecureData = new GlobalPayments\Api\Entities\ThreeDSecure();
				
					try {
						$threeDSecureData = GlobalPayments\Api\Services\Secure3dService::getAuthenticationData()
							->withServerTransactionId($serverTransactionId)
							->execute(GlobalPayments\Api\Entities\Enums\Secure3dVersion::TWO);
					} catch (ApiException $e) { 
						$responseCode = $exception->responseCode;
						$responseMessage = $exception->responseMessage;
				
						$this->model_extension_payment_globalpayments->log($exception, $responseCode . ' ' . $responseMessage);
				
						$this->error['warning'] = $responseCode . ' ' . $responseMessage . ' ' . sprintf($this->language->get('error_payment'), $this->url->link('information/contact', '', true));
					}
				
					$authenticationValue = $threeDSecureData->authenticationValue; 
					$dsTransId = $threeDSecureData->directoryServerTransactionId;
					$messageVersion = $threeDSecureData->messageVersion;
					$eci = $threeDSecureData->eci;
				}
			}
			
			if (!$this->error) {
				$servicesConfig = new GlobalPayments\Api\ServicesConfig();
		
				$servicesConfig->merchantId = $merchant_id;
				$servicesConfig->accountId = $account_id;
				$servicesConfig->sharedSecret = $secret;
				$servicesConfig->serviceUrl = $service['url'];
									
				GlobalPayments\Api\ServicesContainer::configure($servicesConfig);
			
				$card = new GlobalPayments\Api\PaymentMethods\CreditCardData();
			
				$card->number = $this->request->post['card_number'];
				$card->cardHolderName = $this->request->post['card_holder_name'];
				$card->expMonth = $this->request->post['card_expire_date_month'];
				$card->expYear = $this->request->post['card_expire_date_year'];
				$card->cvn = $this->request->post['card_cvn'];
				
				if (isset($threeDSecureData)) {
					$card->threeDSecure = $threeDSecureData;
				}
			
				$response = new GlobalPayments\Api\Entities\Transaction();

				try {
					$response = $card->charge($order_info['total'])->withCurrency($order_info['currency_code'])->execute();
						
					$responseCode = $response->responseCode;
					$responseMessage = $response->responseMessage;
					$orderId = $response->orderId;
					$authCode = $response->authorizationCode;
					$paymentsReference = $response->transactionId;
								
					$this->model_extension_payment_globalpayments->addOrder($this->session->data['order_id'], $orderId);
										
					if ($responseCode == '00') {
						$this->model_checkout_order->addOrderHistory($this->session->data['order_id'], $this->config->get('config_order_status_id'));
					
						if ($settlement_method == 'auto') {
							$this->model_checkout_order->addOrderHistory($this->session->data['order_id'], $setting['order_status']['success_settled']['id'], $responseMessage);
						} else {
							$this->model_checkout_order->addOrderHistory($this->session->data['order_id'], $setting['order_status']['success_unsettled']['id'], $responseMessage);
						}
					} else {
						$this->error['warning'] = $responseCode . ' ' . $responseMessage . ' ' . sprintf($this->language->get('error_payment'), $this->url->link('information/contact', '', true));
					}
				} catch (GlobalPayments\Api\Entities\Exceptions\ApiException $exception) {
					$responseCode = $exception->responseCode;
					$responseMessage = $exception->responseMessage;
						
					if (($responseCode >= 100) && ($responseCode < 200)) {
						$this->model_checkout_order->addOrderHistory($this->session->data['order_id'], $setting['order_status']['decline']['id'], $responseMessage);
					} 
				
					if ($responseCode >= 200) {
						$this->model_checkout_order->addOrderHistory($this->session->data['order_id'], $setting['order_status']['failed']['id'], $responseMessage);
					}
				
					$this->model_extension_payment_globalpayments->log($exception, $responseCode . ' ' . $responseMessage);
					
					$this->error['warning'] = $responseCode . ' ' . $responseMessage . ' ' . sprintf($this->language->get('error_payment'), $this->url->link('information/contact', '', true));
				}
			}
		}	
		
		if (!$this->error) {
			$data['success'] = $this->url->link('checkout/success', '', true);
		}
		
		$data['error'] = $this->error;
				
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($data));
	}

	public function apiSecure2MethodNotificationUrl() {
		$this->load->model('extension/payment/globalpayments');
		
		if (isset($this->request->request['threeDSMethodData'])) {
			$threeDSMethodData = $this->request->request['threeDSMethodData'];
		
			try {
				$decodedThreeDSMethodData = base64_decode($threeDSMethodData);
				$data = json_decode($decodedThreeDSMethodData, true);
				
				$this->response->setOutput($this->load->view('extension/payment/globalpayments/api_secure_2_method_notification', $data));
			} catch (Exception $exception) {				
				$this->model_extension_payment_globalpayments->log($exception);
			}
		}
	}
	
	public function apiSecure2ChallengeNotificationUrl() {
		$this->load->model('extension/payment/globalpayments');
				
		if (isset($this->request->request['cres'])) {
			$cres = $this->request->request['cres'];
		
			try {
				$decodedString = base64_decode($cres);
				$data = json_decode($decodedString, true);

				$this->response->setOutput($this->load->view('extension/payment/globalpayments/api_secure_2_challenge_notification', $data));
			} catch (Exception $exception) {
				$this->model_extension_payment_globalpayments->log($exception);
			}
		}
	}
		
	public function apiSecure1Setup() {
		$this->load->language('extension/payment/globalpayments');
		
		$this->load->model('extension/payment/globalpayments');
					
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {							
			$this->load->model('checkout/order');
			
			$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
		
			// Setting
			$_config = new Config();
			$_config->load('globalpayments');
			
			$config_setting = $_config->get('globalpayments_setting');
		
			$setting = array_replace_recursive((array)$config_setting, (array)$this->config->get('payment_globalpayments_setting'));
						
			$merchant_id = $this->config->get('payment_globalpayments_merchant_id');
			$account_id = $this->config->get('payment_globalpayments_account_id');
			$secret = $this->config->get('payment_globalpayments_secret');
			$checkout = $this->config->get('payment_globalpayments_checkout');
			$environment = $this->config->get('payment_globalpayments_environment');
			$settlement_method = $this->config->get('payment_globalpayments_settlement_method');
			$service = $setting['service'][$checkout][$environment];
			
			require_once DIR_SYSTEM . 'library/globalpayments/GlobalPayments.php';

			$servicesConfig = new GlobalPayments\Api\ServicesConfig();
		
			$servicesConfig->merchantId = $merchant_id;
			$servicesConfig->accountId = $account_id;
			$servicesConfig->sharedSecret = $secret;
			$servicesConfig->serviceUrl = $service['url'];
			$servicesConfig->secure3dVersion = GlobalPayments\Api\Entities\Enums\Secure3dVersion::ONE;
						
			GlobalPayments\Api\ServicesContainer::configure($servicesConfig);
			
			$card = new GlobalPayments\Api\PaymentMethods\CreditCardData();
			
			$card->number = $this->request->post['card_number'];
			$card->cardHolderName = $this->request->post['card_holder_name'];
			$card->expMonth = $this->request->post['card_expire_date_month'];
			$card->expYear = $this->request->post['card_expire_date_year'];
			$card->cvn = $this->request->post['card_cvn'];
			
			try {
				$threeDSecureData = GlobalPayments\Api\Services\Secure3dService::checkEnrollment($card)->withAmount($order_info['total'])->withCurrency($order_info['currency_code'])->execute(GlobalPayments\Api\Entities\Enums\Secure3dVersion::ONE);
				
			} catch (GlobalPayments\Api\Entities\Exceptions\ApiException $exception) {
				$responseCode = $exception->responseCode;
				$responseMessage = $exception->responseMessage;
				
				$this->model_extension_payment_globalpayments->log($exception, $responseCode . ' ' . $responseMessage);
				
				$this->error['warning'] = $responseCode . ' ' . $responseMessage . ' ' . sprintf($this->language->get('error_payment'), $this->url->link('information/contact', '', true));
			}
			
			if (isset($threeDSecureData)) {
				$enrolled = $threeDSecureData->enrolled;
				$acsUrl = $threeDSecureData->issuerAcsUrl;
				
				if ($enrolled && $acsUrl) {
					$data['acsUrl'] = $threeDSecureData->issuerAcsUrl;
					$data['pareq'] = $threeDSecureData->payerAuthenticationRequest;
					$data['md'] = $threeDSecureData->getMerchantData()->toString();
					$data['termUrl'] = $this->url->link('extension/payment/globalpayments/apiSecure1ACSReturn', '', true);
				} else {
					$secure_scenario_code = '';
					
					if ($enrolled == 'N') {
						$secure_scenario_code = 'cardholder_not_enrolled';
					}
					
					if ($enrolled == 'U') {
						$secure_scenario_code = 'unable_to_verify_enrolment';
					}
					
					if (!$enrolled) {
						$secure_scenario_code = 'invalid_response_from_enrolment_server';
					}
			
					if (isset($setting['secure_1_scenario'][$secure_scenario_code]) && isset($setting['checkout']['api']['secure_1_scenario'][$secure_scenario_code]) && !$setting['checkout']['api']['secure_1_scenario'][$secure_scenario_code]) {
						$this->error['warning'] = $this->language->get($setting['secure_1_scenario'][$secure_scenario_code]['error']) . ' ' . sprintf($this->language->get('error_payment'), $this->url->link('information/contact', '', true));
					}
					
					if (!$this->error) {
						try {
							$response = $card->charge($order_info['total'])->withCurrency($order_info['currency_code'])->execute();
				
							$responseCode = $response->responseCode;
							$responseMessage = $response->responseMessage;
							$orderId = $response->orderId;
							$authCode = $response->authorizationCode;
							$paymentsReference = $response->transactionId;
								
							$this->model_extension_payment_globalpayments->addOrder($this->session->data['order_id'], $orderId);
										
							if ($responseCode == '00') {
								$this->model_checkout_order->addOrderHistory($this->session->data['order_id'], $this->config->get('config_order_status_id'));
					
								if ($settlement_method == 'auto') {
									$this->model_checkout_order->addOrderHistory($this->session->data['order_id'], $setting['order_status']['success_settled']['id'], $responseMessage);
								} else {
									$this->model_checkout_order->addOrderHistory($this->session->data['order_id'], $setting['order_status']['success_unsettled']['id'], $responseMessage);
								}
							} else {
								$this->error['warning'] = $responseCode . ' ' . $responseMessage . ' ' . sprintf($this->language->get('error_payment'), $this->url->link('information/contact', '', true));
							}
						} catch (GlobalPayments\Api\Entities\Exceptions\ApiException $exception) {
							$responseCode = $exception->responseCode;
							$responseMessage = $exception->responseMessage;
				
							if (($responseCode >= 100) && ($responseCode < 200)) {
								$this->model_checkout_order->addOrderHistory($this->session->data['order_id'], $setting['order_status']['decline']['id'], $responseMessage);
							} 
				
							if ($responseCode >= 200) {
								$this->model_checkout_order->addOrderHistory($this->session->data['order_id'], $setting['order_status']['failed']['id'], $responseMessage);
							}

							$this->model_extension_payment_globalpayments->log($exception, $responseCode . ' ' . $responseMessage);
				
							$this->error['warning'] = $responseCode . ' ' . $responseMessage . ' ' . sprintf($this->language->get('error_payment'), $this->url->link('information/contact', '', true));
						}
					}
					
					if (!$this->error) {
						$data['success'] = $this->url->link('checkout/success', '', true);
					}
				}
			}
		}
				
		$data['error'] = $this->error;
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($data));
	}
	
	public function apiSecure1ACSReturn() {
		$this->load->language('extension/payment/globalpayments');
		
		$this->load->model('extension/payment/globalpayments');
		
		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			$data['server'] = HTTPS_SERVER;
		} else {
			$data['server'] = HTTP_SERVER;
		}
					
		if (isset($this->request->post['MD']) && isset($this->request->post['PaRes'])) {					
			$this->load->model('checkout/order');
			
			$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
		
			// Setting
			$_config = new Config();
			$_config->load('globalpayments');
			
			$config_setting = $_config->get('globalpayments_setting');
		
			$setting = array_replace_recursive((array)$config_setting, (array)$this->config->get('payment_globalpayments_setting'));
						
			$merchant_id = $this->config->get('payment_globalpayments_merchant_id');
			$account_id = $this->config->get('payment_globalpayments_account_id');
			$secret = $this->config->get('payment_globalpayments_secret');
			$checkout = $this->config->get('payment_globalpayments_checkout');
			$environment = $this->config->get('payment_globalpayments_environment');
			$settlement_method = $this->config->get('payment_globalpayments_settlement_method');
			$service = $setting['service'][$checkout][$environment];
			
			require_once DIR_SYSTEM . 'library/globalpayments/GlobalPayments.php';

			$servicesConfig = new GlobalPayments\Api\ServicesConfig();
		
			$servicesConfig->merchantId = $merchant_id;
			$servicesConfig->accountId = $account_id;
			$servicesConfig->sharedSecret = $secret;
			$servicesConfig->serviceUrl = $service['url'];
			$servicesConfig->secure3dVersion = GlobalPayments\Api\Entities\Enums\Secure3dVersion::ONE;
						
			GlobalPayments\Api\ServicesContainer::configure($servicesConfig);
		
			$pares = $this->request->post['PaRes'];
			
			$md = GlobalPayments\Api\Entities\MerchantDataCollection::parse($this->request->post['MD']);
			
			try {
				$threeDSecureData = GlobalPayments\Api\Services\Secure3dService::getAuthenticationData()->withPayerAuthenticationResponse($pares)->withMerchantData($md)->execute();
			} catch (GlobalPayments\Api\Entities\Exceptions\ApiException $e) {
				$responseCode = $exception->responseCode;
				$responseMessage = $exception->responseMessage;
				
				$this->model_extension_payment_globalpayments->log($exception, $responseCode . ' ' . $responseMessage);
				
				$this->error['warning'] = $responseCode . ' ' . $responseMessage . ' ' . sprintf($this->language->get('error_payment'), $this->url->link('information/contact', '', true));
			}
			
			if (isset($threeDSecureData)) {
				$data['authentication_data']['status'] = $threeDSecureData->status;
				$data['authentication_data']['xid'] = $threeDSecureData->xid;
				$data['authentication_data']['cavv'] = $threeDSecureData->cavv;
				$data['authentication_data']['eci'] = $threeDSecureData->eci;
			}
		}
			
		$data['authentication_data']['error'] = $this->error;
		
		$data['authentication_data'] = json_encode($data['authentication_data']);
		
		$this->response->setOutput($this->load->view('extension/payment/globalpayments/api_secure_1_acs_return', $data));
	}
	
	public function apiSecure1Authorization() {
		$this->load->language('extension/payment/globalpayments');
										
		$this->load->model('extension/payment/globalpayments');
		
		if (isset($this->request->post['authenticationData']) && $this->validate()) {
			$this->load->model('checkout/order');
			
			$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
		
			// Setting
			$_config = new Config();
			$_config->load('globalpayments');
			
			$config_setting = $_config->get('globalpayments_setting');
		
			$setting = array_replace_recursive((array)$config_setting, (array)$this->config->get('payment_globalpayments_setting'));
						
			$merchant_id = $this->config->get('payment_globalpayments_merchant_id');
			$account_id = $this->config->get('payment_globalpayments_account_id');
			$secret = $this->config->get('payment_globalpayments_secret');
			$checkout = $this->config->get('payment_globalpayments_checkout');
			$environment = $this->config->get('payment_globalpayments_environment');
			$settlement_method = $this->config->get('payment_globalpayments_settlement_method');
			$service = $setting['service'][$checkout][$environment];
			
			$authentication_data = json_decode(htmlspecialchars_decode($this->request->post['authenticationData']), true);
			
			$secure_scenario_code = '';
			
			if ($authentication_data['status'] == 'A') {
				$secure_scenario_code = 'authentication_attempt_acknowledge';
			}
			
			if ($authentication_data['status'] == 'N') {
				$secure_scenario_code = 'incorrect_password_entered';
			}
			
			if ($authentication_data['status'] == 'U') {
				$secure_scenario_code = 'authentication_unavailable';
			}
			
			if (!$authentication_data['status']) {
				$secure_scenario_code = 'invalid_response_from_acs';
			}
			
			if (isset($setting['secure_1_scenario'][$secure_scenario_code]) && isset($setting['checkout']['api']['secure_1_scenario'][$secure_scenario_code]) && !$setting['checkout']['api']['secure_1_scenario'][$secure_scenario_code]) {
				$this->error['warning'] = $this->language->get($setting['secure_1_scenario'][$secure_scenario_code]['error']) . ' ' . sprintf($this->language->get('error_payment'), $this->url->link('information/contact', '', true));
			}
			
			if (!$this->error) {
				require_once DIR_SYSTEM . 'library/globalpayments/GlobalPayments.php';
				
				$servicesConfig = new GlobalPayments\Api\ServicesConfig();
		
				$servicesConfig->merchantId = $merchant_id;
				$servicesConfig->accountId = $account_id;
				$servicesConfig->sharedSecret = $secret;
				$servicesConfig->serviceUrl = $service['url'];
				$servicesConfig->secure3dVersion = GlobalPayments\Api\Entities\Enums\Secure3dVersion::ONE;
						
				GlobalPayments\Api\ServicesContainer::configure($servicesConfig);
			
				$card = new GlobalPayments\Api\PaymentMethods\CreditCardData();
			
				$card->number = $this->request->post['card_number'];
				$card->cardHolderName = $this->request->post['card_holder_name'];
				$card->expMonth = $this->request->post['card_expire_date_month'];
				$card->expYear = $this->request->post['card_expire_date_year'];
				$card->cvn = $this->request->post['card_cvn'];
				
				$threeDSecureInfo = new GlobalPayments\Api\Entities\EcommerceInfo();
				$threeDSecureInfo->cavv = $authentication_data['cavv'];
				$threeDSecureInfo->xid = $authentication_data['xid'];
				$threeDSecureInfo->eci = $authentication_data['eci'];
			
				try {
					$response = $card->charge($order_info['total'])->withEcommerceInfo($threeDSecureInfo)->withCurrency($order_info['currency_code'])->execute();
				
					$responseCode = $response->responseCode;
					$responseMessage = $response->responseMessage;
					$orderId = $response->orderId;
					$authCode = $response->authorizationCode;
					$paymentsReference = $response->transactionId;
								
					$this->model_extension_payment_globalpayments->addOrder($this->session->data['order_id'], $orderId);
					
					if ($responseCode == '00') {
						$this->model_checkout_order->addOrderHistory($this->session->data['order_id'], $this->config->get('config_order_status_id'));
					
						if ($settlement_method == 'auto') {
							$this->model_checkout_order->addOrderHistory($this->session->data['order_id'], $setting['order_status']['success_settled']['id'], $responseMessage);
						} else {
							$this->model_checkout_order->addOrderHistory($this->session->data['order_id'], $setting['order_status']['success_unsettled']['id'], $responseMessage);
						}
					} else {
						$this->error['warning'] = $responseCode . ' ' . $responseMessage . ' ' . sprintf($this->language->get('error_payment'), $this->url->link('information/contact', '', true));
					}
				} catch (GlobalPayments\Api\Entities\Exceptions\ApiException $exception) {
					$responseCode = $exception->responseCode;
					$responseMessage = $exception->responseMessage;
				
					if (($responseCode >= 100) && ($responseCode < 200)) {
						$this->model_checkout_order->addOrderHistory($this->session->data['order_id'], $setting['order_status']['decline']['id'], $responseMessage);
					} 
				
					if ($responseCode >= 200) {
						$this->model_checkout_order->addOrderHistory($this->session->data['order_id'], $setting['order_status']['failed']['id'], $responseMessage);
					}

					$this->model_extension_payment_globalpayments->log($exception, $responseCode . ' ' . $responseMessage);
				
					$this->error['warning'] = $responseCode . ' ' . $responseMessage . ' ' . sprintf($this->language->get('error_payment'), $this->url->link('information/contact', '', true));
				}
			}
		}
		
		if (!$this->error) {
			$data['success'] = $this->url->link('checkout/success', '', true);
		}
		
		$data['error'] = $this->error;
				
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($data));
	}
	
	public function apmRedirect() {		
		$this->load->language('extension/payment/globalpayments');
						
		$this->load->model('extension/payment/globalpayments');
		
		if (isset($this->request->request['ORDER_ID']) && isset($this->request->request['RESULT']) && isset($this->request->request['MESSAGE'])) {		
			$responseCode = $this->request->request['RESULT'];
			$responseMessage = $this->request->request['MESSAGE'];
			$orderId = $this->request->request['ORDER_ID'];
			
			sleep(3);
						
			$order = $this->model_extension_payment_globalpayments->getOrderByCode($orderId);
			
			$this->load->model('checkout/order');
			
			$order_info = $this->model_checkout_order->getOrder($order['order_id']);
			
			// Setting
			$_config = new Config();
			$_config->load('globalpayments');
			
			$config_setting = $_config->get('globalpayments_setting');
		
			$setting = array_replace_recursive((array)$config_setting, (array)$this->config->get('payment_globalpayments_setting'));
			
			$settlement_method = $this->config->get('payment_globalpayments_settlement_method');

			if ($order_info['order_status_id']) {
				if ((($settlement_method == 'auto') && ($order_info['order_status_id'] == $setting['order_status']['success_settled']['id'])) || (($settlement_method != 'auto') && ($order_info['order_status_id'] == $setting['order_status']['success_unsettled']['id']))) {
					$data['redirect_url'] = $this->url->link('checkout/success', '', true);
				} else {
					$data['redirect_url'] = $this->url->link('extension/payment/globalpayments/apmFailurePage', '', true);
				}
			} else {				
				$data['redirect_url'] = $this->url->link('extension/payment/globalpayments/apmInterimPage', '', true);
			}
			
			$this->response->setOutput($this->load->view('extension/payment/globalpayments/apm_redirect', $data));
		}
	}
			
	public function apmUpdateOrderStatus() {
		$this->load->language('extension/payment/globalpayments');
						
		$this->load->model('extension/payment/globalpayments');
		
		if (isset($this->request->request['orderid']) && isset($this->request->request['result']) && isset($this->request->request['message'])) {		
			$responseCode = $this->request->request['result'];
			$responseMessage = $this->request->request['message'];
			$orderId = $this->request->request['orderid'];
				
			$order = $this->model_extension_payment_globalpayments->getOrderByCode($orderId);
					
			// Setting
			$_config = new Config();
			$_config->load('globalpayments');
			
			$config_setting = $_config->get('globalpayments_setting');
		
			$setting = array_replace_recursive((array)$config_setting, (array)$this->config->get('payment_globalpayments_setting'));
			
			$settlement_method = $this->config->get('payment_globalpayments_settlement_method');
			
			$this->load->model('checkout/order');
							
			if ($responseCode == '00') {
				$this->model_checkout_order->addOrderHistory($order['order_id'], $this->config->get('config_order_status_id'));
				
				if ($settlement_method == 'auto') {
					$this->model_checkout_order->addOrderHistory($order['order_id'], $setting['order_status']['success_settled']['id'], $responseMessage);
				} else {
					$this->model_checkout_order->addOrderHistory($order['order_id'], $setting['order_status']['success_unsettled']['id'], $responseMessage);
				}
			} else {				
				if (($responseCode >= 100) && ($responseCode < 200)) {
					$this->model_checkout_order->addOrderHistory($order['order_id'], $setting['order_status']['decline']['id'], $responseMessage);
				} 
				
				if ($responseCode >= 200) {
					$this->model_checkout_order->addOrderHistory($order['order_id'], $setting['order_status']['failed']['id'], $responseMessage);
				}
				
				$this->model_extension_payment_globalpayments->log($this->request->request, $responseCode . ' ' . $responseMessage);
			}
		}
	}
			
	public function apmInterimPage() {
		$this->load->language('extension/payment/globalpayments');

		$this->document->setTitle($this->language->get('text_apm_interim_page_title'));

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home', '', true)
		);
		
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_cart'),
			'href' => $this->url->link('checkout/cart', '', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_title'),
			'href' => $this->url->link('extension/payment/globalpayments/apmInterimPage', '', true)
		);
		
		$data['text_title'] = $this->language->get('text_apm_interim_page_title');
		$data['text_message'] = $this->language->get('text_apm_interim_page_message');
		
		$data['apm_check_order_status_url'] = $this->url->link('extension/payment/globalpayments/apmCheckOrderStatus', '', true);
		
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('extension/payment/globalpayments/apm_interim_page', $data));
	}
	
	public function apmCheckOrderStatus() {		
		if (isset($this->session->data['order_id'])) {			
			$this->load->model('checkout/order');
			
			$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
			
			// Setting
			$_config = new Config();
			$_config->load('globalpayments');
			
			$config_setting = $_config->get('globalpayments_setting');
		
			$setting = array_replace_recursive((array)$config_setting, (array)$this->config->get('payment_globalpayments_setting'));
			
			$settlement_method = $this->config->get('payment_globalpayments_settlement_method');
			
			if ($order_info['order_status_id']) {
				if ((($settlement_method == 'auto') && ($order_info['order_status_id'] == $setting['order_status']['success_settled']['id'])) || (($settlement_method != 'auto') && ($order_info['order_status_id'] == $setting['order_status']['success_unsettled']['id']))) {
					$this->response->redirect($this->url->link('checkout/success', '', true));
				} else {
					$this->response->redirect($this->url->link('extension/payment/globalpayments/apmFailurePage', '', true));
				}
			} else {				
				$this->model_checkout_order->addOrderHistory($this->session->data['order_id'], $this->config->get('config_order_status_id'));
				
				$this->response->redirect($this->url->link('extension/payment/globalpayments/apmFinalPage', '', true));
			}
		}
	}
	
	public function apmFailurePage() {
		$this->load->language('extension/payment/globalpayments');

		$this->document->setTitle($this->language->get('text_apm_failure_page_title'));
		
		if (isset($this->session->data['order_id'])) {
			$this->cart->clear();

			unset($this->session->data['shipping_method']);
			unset($this->session->data['shipping_methods']);
			unset($this->session->data['payment_method']);
			unset($this->session->data['payment_methods']);
			unset($this->session->data['guest']);
			unset($this->session->data['comment']);
			unset($this->session->data['order_id']);
			unset($this->session->data['coupon']);
			unset($this->session->data['reward']);
			unset($this->session->data['voucher']);
			unset($this->session->data['vouchers']);
			unset($this->session->data['totals']);
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home', '', true)
		);
		
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_cart'),
			'href' => $this->url->link('checkout/cart', '', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_title'),
			'href' => $this->url->link('extension/payment/globalpayments/apmFailurePage', '', true)
		);
		
		$data['text_title'] = $this->language->get('text_apm_failure_page_title');
		$data['text_message'] = sprintf($this->language->get('text_apm_failure_page_message'), $this->url->link('information/contact', '', true));
		
		$data['continue'] = $this->url->link('common/home');
				
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('extension/payment/globalpayments/apm_failure_page', $data));
	}
	
	public function apmFinalPage() {
		$this->load->language('extension/payment/globalpayments');

		$this->document->setTitle($this->language->get('text_apm_final_page_title'));
		
		if (isset($this->session->data['order_id'])) {
			$this->cart->clear();

			unset($this->session->data['shipping_method']);
			unset($this->session->data['shipping_methods']);
			unset($this->session->data['payment_method']);
			unset($this->session->data['payment_methods']);
			unset($this->session->data['guest']);
			unset($this->session->data['comment']);
			unset($this->session->data['order_id']);
			unset($this->session->data['coupon']);
			unset($this->session->data['reward']);
			unset($this->session->data['voucher']);
			unset($this->session->data['vouchers']);
			unset($this->session->data['totals']);
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home', '', true)
		);
		
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_cart'),
			'href' => $this->url->link('checkout/cart', '', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_title'),
			'href' => $this->url->link('extension/payment/globalpayments/apmFinalPage', '', true)
		);

		$data['text_title'] = $this->language->get('text_apm_final_page_title');
		$data['text_message'] = $this->language->get('text_apm_final_page_message');
		
		$data['continue'] = $this->url->link('common/home');
		
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('extension/payment/globalpayments/apm_final_page', $data));
	}
		
	private function validate() {
		if (!isset($this->request->post['card_number']) || utf8_strlen($this->request->post['card_number']) < 1) {
			$this->error['warning'] = $this->language->get('error_warning');
			$this->error['card_number'] = $this->language->get('error_card_number');
		}
		
		if (!isset($this->request->post['card_holder_name']) || utf8_strlen($this->request->post['card_holder_name']) < 1) {
			$this->error['warning'] = $this->language->get('error_warning');
			$this->error['card_holder_name'] = $this->language->get('error_card_holder_name');
		}
		
		if (!isset($this->request->post['card_expire_date_month']) || utf8_strlen($this->request->post['card_expire_date_month']) < 1) {
			$this->error['warning'] = $this->language->get('error_warning');
			$this->error['card_expire_date_month'] = $this->language->get('error_card_expire_date_format');
		}
		
		if (!isset($this->request->post['card_expire_date_year']) || utf8_strlen($this->request->post['card_expire_date_year']) < 1) {
			$this->error['warning'] = $this->language->get('error_warning');
			$this->error['card_expire_date_year'] = $this->language->get('error_card_expire_date_format');
		}
		
		if (!isset($this->request->post['card_cvn']) || utf8_strlen($this->request->post['card_cvn']) < 1) {
			$this->error['warning'] = $this->language->get('error_warning');
			$this->error['card_cvn'] = $this->language->get('error_card_cvn');
		}
		
		return !$this->error;
	}
}