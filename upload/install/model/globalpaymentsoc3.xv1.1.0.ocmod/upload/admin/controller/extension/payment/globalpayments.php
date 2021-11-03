<?php
class ControllerExtensionPaymentGlobalPayments extends Controller {
	private $error = array();
	
	public function index() {
		$this->load->language('extension/payment/globalpayments');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');
				
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('payment_globalpayments', $this->request->post);
														
			$this->session->data['success'] = $this->language->get('success_save');

			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true));
		}
		
		$this->load->model('extension/payment/globalpayments');
		
		$this->model_extension_payment_globalpayments->refreshOrders();
				
		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
		
		if (isset($this->error['error_merchant_id'])) {
			$data['error_merchant_id'] = $this->error['error_merchant_id'];
		} else {
			$data['error_merchant_id'] = '';
		}
		
		if (isset($this->error['error_account_id'])) {
			$data['error_account_id'] = $this->error['error_account_id'];
		} else {
			$data['error_account_id'] = '';
		}

		if (isset($this->error['error_secret'])) {
			$data['error_secret'] = $this->error['error_secret'];
		} else {
			$data['error_secret'] = '';
		}
			
		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extensions'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/payment/globalpayments', 'user_token=' . $this->session->data['user_token'], true)
		);
						
		$data['action'] = $this->url->link('extension/payment/globalpayments', 'user_token=' . $this->session->data['user_token'], true);
		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true);
				
		if (isset($this->request->post['payment_globalpayments_merchant_id'])) {
			$data['merchant_id'] = $this->request->post['payment_globalpayments_merchant_id'];
		} else {
			$data['merchant_id'] = $this->config->get('payment_globalpayments_merchant_id');
		}
		
		if (isset($this->request->post['payment_globalpayments_account_id'])) {
			$data['account_id'] = $this->request->post['payment_globalpayments_account_id'];
		} else {
			$data['account_id'] = $this->config->get('payment_globalpayments_account_id');
		}

		if (isset($this->request->post['payment_globalpayments_secret'])) {
			$data['secret'] = $this->request->post['payment_globalpayments_secret'];
		} else {
			$data['secret'] = $this->config->get('payment_globalpayments_secret');
		}
		
		if (isset($this->request->post['payment_globalpayments_checkout'])) {
			$data['checkout'] = $this->request->post['payment_globalpayments_checkout'];
		} else {
			$data['checkout'] = $this->config->get('payment_globalpayments_checkout');
		}
								
		if (isset($this->request->post['payment_globalpayments_environment'])) {
			$data['environment'] = $this->request->post['payment_globalpayments_environment'];
		} else {
			$data['environment'] = $this->config->get('payment_globalpayments_environment');
		}
										
		if (isset($this->request->post['payment_globalpayments_debug'])) {
			$data['debug'] = $this->request->post['payment_globalpayments_debug'];
		} else {
			$data['debug'] = $this->config->get('payment_globalpayments_debug');
		}
		
		if (isset($this->request->post['payment_globalpayments_settlement_method'])) {
			$data['settlement_method'] = $this->request->post['payment_globalpayments_settlement_method'];
		} else {
			$data['settlement_method'] = $this->config->get('payment_globalpayments_settlement_method');
		}

		if (isset($this->request->post['payment_globalpayments_total'])) {
			$data['total'] = $this->request->post['payment_globalpayments_total'];
		} else {
			$data['total'] = $this->config->get('payment_globalpayments_total');
		}

		if (isset($this->request->post['payment_globalpayments_geo_zone_id'])) {
			$data['geo_zone_id'] = $this->request->post['payment_globalpayments_geo_zone_id'];
		} else {
			$data['geo_zone_id'] = $this->config->get('payment_globalpayments_geo_zone_id');
		}

		$this->load->model('localisation/geo_zone');

		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		if (isset($this->request->post['payment_globalpayments_status'])) {
			$data['status'] = $this->request->post['payment_globalpayments_status'];
		} else {
			$data['status'] = $this->config->get('payment_globalpayments_status');
		}

		if (isset($this->request->post['payment_globalpayments_sort_order'])) {
			$data['sort_order'] = $this->request->post['payment_globalpayments_sort_order'];
		} else {
			$data['sort_order'] = $this->config->get('payment_globalpayments_sort_order');
		}
		
		$this->load->model('localisation/order_status');

		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
		
		// Setting 		
		$_config = new Config();
		$_config->load('globalpayments');
		
		$data['setting'] = $_config->get('globalpayments_setting');
						
		if (isset($this->request->post['payment_globalpayments_setting'])) {
			$data['setting'] = array_replace_recursive((array)$data['setting'], (array)$this->request->post['payment_globalpayments_setting']);
		} else {
			$data['setting'] = array_replace_recursive((array)$data['setting'], (array)$this->config->get('payment_globalpayments_setting'));
		}
							
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/payment/globalpayments', $data));
	}
	
	public function install() {
		$this->load->model('extension/payment/globalpayments');
		
		$this->model_extension_payment_globalpayments->installExtension();
	}
	
	public function uninstall() {
		$this->load->model('extension/payment/globalpayments');
		
		$this->model_extension_payment_globalpayments->uninstallExtension();
	}
					
	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/payment/globalpayments')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!isset($this->request->post['payment_globalpayments_merchant_id']) || strlen($this->request->post['payment_globalpayments_merchant_id']) <= 1 || strlen($this->request->post['payment_globalpayments_merchant_id']) > 50) {
			$this->error['warning'] = $this->language->get('error_warning');
			$this->error['error_merchant_id'] = $this->language->get('error_merchant_id');
		}
		
		if (!isset($this->request->post['payment_globalpayments_account_id']) || strlen($this->request->post['payment_globalpayments_account_id']) <= 1 || strlen($this->request->post['payment_globalpayments_account_id']) > 50) {
			$this->error['warning'] = $this->language->get('error_warning');
			$this->error['error_account_id'] = $this->language->get('error_account_id');
		}

		if (!isset($this->request->post['payment_globalpayments_secret']) || strlen($this->request->post['payment_globalpayments_secret']) <= 1 || strlen($this->request->post['payment_globalpayments_secret']) > 50) {
			$this->error['warning'] = $this->language->get('error_warning');
			$this->error['error_secret'] = $this->language->get('error_secret');
		}
				
		return !$this->error;
	}
}