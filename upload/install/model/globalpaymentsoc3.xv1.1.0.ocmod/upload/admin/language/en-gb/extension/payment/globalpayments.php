<?php
// Heading
$_['heading_title']		 									= 'Global Payments';

// Text
$_['text_globalpayments']		 							= '<a target="_BLANK" href="https://www.globalpaymentsinc.com/en-us"><img src="view/image/payment/globalpayments.png" alt="Global Payments" title="Global Payments" style="border: 1px solid #EEEEEE;" /></a>';
$_['text_extensions']     									= 'Extensions';
$_['text_edit']          									= 'Edit Global Payments';
$_['text_general']				 	 						= 'General';
$_['text_order_status']				 						= 'Order Status';
$_['text_checkout_hpp']										= 'HPP';
$_['text_checkout_api']										= 'API';
$_['text_support']			 	 							= 'If you have an existing Global Payments account you can contact our support team at ecomsupport@globalpay.com to recieve your integration details. If you would like to request a test account, this can be requested through our <a href="https://developer.globalpay.com/" target="_blank" class="alert-link">Developer Portal</a> by signing in and selecting the my apps section.';
$_['text_production']			 	 						= 'Production / Live';
$_['text_sandbox']			 								= 'Sandbox';
$_['text_delay']			 	 							= 'Delay';
$_['text_auto']			 									= 'Auto';
$_['text_multi']			 								= 'Multi';
$_['text_success_settled_status']							= 'Success - settled';
$_['text_success_unsettled_status']							= 'Success - not settled';
$_['text_decline_status']									= 'Decline';
$_['text_failed_status']									= 'Failed';
$_['text_embedded']											= 'Embedded';
$_['text_lightbox']											= 'Lightbox';
$_['text_align_left']										= 'Align Left';
$_['text_align_center']										= 'Align Center';
$_['text_align_right']										= 'Align Right';
$_['text_small']			 								= 'Small';
$_['text_medium']			 	 							= 'Medium';
$_['text_large']			 	 							= 'Large';
$_['text_responsive']			 	 						= 'Responsive';
$_['text_accept']			 	 							= 'Accept';
$_['text_decline']			 	 							= 'Decline';
$_['text_recommended']			 	 						= '(recommended)';
$_['text_3ds2_authentication_could_not_be_performed']		= 'Authentication could not be perfomed';
$_['text_3ds2_authentication_failed']						= 'Authentication failed';
$_['text_3ds2_authentication_issuer_rejected']				= 'Authentication issuer rejected';
$_['text_3ds2_authentication_attempted_but_not_successful']	= 'Authentication attempted but not successful';
$_['text_3ds1_cardholder_not_enrolled']						= 'Cardholder Not Enrolled';
$_['text_3ds1_unable_to_verify_enrolment']					= 'Unable to Verify Enrolment';
$_['text_3ds1_invalid_response_from_enrolment_server']		= 'Invalid response from Enrolment Server';
$_['text_3ds1_enrolled_but_invalid_response_from_acs']		= 'Enrolled but invalid response from ACS';
$_['text_3ds1_authentication_attempt_acknowledge']			= 'Authentication Attempt Acknowledge';
$_['text_3ds1_incorrect_password_entered']					= 'Incorrect Password entered';
$_['text_3ds1_authentication_unavailable']					= 'Authentication Unavailable';
$_['text_3ds1_invalid_response_from_acs']					= 'Invalid Response from ACS';

// Entry
$_['entry_merchant_id']				 						= 'Merchant ID';
$_['entry_account_id']				 						= 'Account ID';
$_['entry_secret']					 						= 'Shared secret';
$_['entry_checkout']										= 'Checkout';
$_['entry_environment']				 						= 'Environment';
$_['entry_debug']	 										= 'Debug';
$_['entry_settlement_method']	 							= 'Settlement Method';
$_['entry_total']		 									= 'Total';
$_['entry_geo_zone']	 									= 'Geo Zone';
$_['entry_status']		 									= 'Status';
$_['entry_sort_order']	 									= 'Sort Order';
$_['entry_form_align']     									= 'Form Align';
$_['entry_form_size'] 										= 'Form Size';
$_['entry_secure_status'] 									= '3D Secure Status';
$_['entry_secure_2_scenario'] 								= '3D Secure 2 Scenarios';
$_['entry_secure_1_scenario'] 								= '3D Secure 1 Scenarios';

// Help
$_['help_total']		 									= 'The checkout total the order must reach before this payment method becomes active';
$_['help_secure_status'] 									= '3D Secure enables you to authenticate card holders through card issuers. It reduces the likelihood of fraud when you use supported cards and improves transaction perfomance. A successful 3D Secure authentication can shift liability for chargebacks due to fraud from you -the merchant- to the card issuer.';
$_['help_secure_2_scenario'] 								= '3D Secure authentication is perfomed only if the card is enrolled for the service. In scenarios where the 3D Secure authentication has not been successful, you have the option to complete the payment at your own risk, meaning that you -the merchant- will be liable in case of a chargeback.';
$_['help_secure_1_scenario'] 								= '3D Secure authentication is perfomed only if the card is enrolled for the service. In scenarios where the 3D Secure authentication has not been successful, you have the option to complete the payment at your own risk, meaning that you -the merchant- will be liable in case of a chargeback.';

// Success
$_['success_save']		 									= 'Success: You have modified Global Payments!';

// Error
$_['error_permission']	 									= 'Warning: You do not have permission to modify payment Global Payments!';
$_['error_warning']          		 						= 'Warning: Please check the form carefully for errors!';
$_['error_merchant_id']				 						= 'Merchant ID is required';
$_['error_account_id']				 						= 'Account ID is required';
$_['error_secret']					 						= 'Shared secret is required';