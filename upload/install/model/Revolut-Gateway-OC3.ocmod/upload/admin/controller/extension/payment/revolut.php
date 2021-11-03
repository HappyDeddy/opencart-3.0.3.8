<?php
class ControllerExtensionPaymentRevolut extends Controller {
    private $error = array();

    public function index() {
        $this->load->language('extension/payment/revolut');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->document->addStyle('view/javascript/revolut/css/bootstrap-colorpicker.css');
        $this->document->addScript('view/javascript/revolut/js/bootstrap-colorpicker.js');

        $this->load->model('setting/setting');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $this->load->model('extension/payment/revolut');

            $this->model_extension_payment_revolut->updateWebhook($this->request->post['payment_revolut_api_key']);

            $this->model_setting_setting->editSetting('payment_revolut', $this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('extension/payment/revolut', 'user_token=' . $this->session->data['user_token'], true));
        }

        if ($this->config->get('payment_revolut_api_key') && !$this->config->get('payment_revolut_webhook_id')) {
            $data['error_webhook_not_setup'] = $this->language->get('error_webhook_not_setup');
        } else {
            $data['error_webhook_not_setup'] = '';
        }

        if (!$this->config->get('payment_revolut_completed_status_id')) {
            $data['error_order_statuses'] = $this->language->get('error_order_statuses');
        } else {
            $data['error_order_statuses'] = '';
        }

        if ($this->config->get('payment_revolut_api_key')) {
            $data['show_webhook_button'] = true;
        } else {
            $data['show_webhook_button'] = false;
        }

        $data['webhook_id'] = $this->config->get('payment_revolut_webhook_id');

        if (!empty($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];
            unset($this->session->data['success']);
        } else {
            $data['success'] = '';
        }

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->error['api_key'])) {
            $data['error_api_key'] = $this->error['api_key'];
        } else {
            $data['error_api_key'] = '';
        }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_extension'),
            'href' => $this->url->link('extension/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('extension/payment/revolut', 'user_token=' . $this->session->data['user_token'], true)
        );

        $data['action'] = $this->url->link('extension/payment/revolut', 'user_token=' . $this->session->data['user_token'], true);

        $data['cancel'] = $this->url->link('extension/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true);

        if (isset($this->request->post['payment_revolut_api_key'])) {
            $data['payment_revolut_api_key'] = $this->request->post['payment_revolut_api_key'];
        } else {
            $data['payment_revolut_api_key'] = $this->config->get('payment_revolut_api_key');
        }

        if (isset($this->request->post['payment_revolut_test'])) {
            $data['payment_revolut_test'] = $this->request->post['payment_revolut_test'];
        } else {
            $data['payment_revolut_test'] = $this->config->get('payment_revolut_test');
        }

        if (isset($this->request->post['payment_revolut_capture_mode'])) {
            $data['payment_revolut_capture_mode'] = $this->request->post['payment_revolut_capture_mode'];
        } else {
            $data['payment_revolut_capture_mode'] = $this->config->get('payment_revolut_capture_mode');
        }

        if (isset($this->request->post['payment_revolut_capture_status_id'])) {
            $data['payment_revolut_capture_status_id'] = $this->request->post['payment_revolut_capture_status_id'];
        } else {
            $data['payment_revolut_capture_status_id'] = $this->config->get('payment_revolut_capture_status_id');
        }

        if (isset($this->request->post['payment_revolut_payment_title'])) {
            $data['payment_revolut_payment_title'] = $this->request->post['payment_revolut_payment_title'];
        } else {
            $data['payment_revolut_payment_title'] = $this->config->get('payment_revolut_payment_title');
        }

        if (isset($this->request->post['payment_revolut_payment_view'])) {
            $data['payment_revolut_payment_view'] = $this->request->post['payment_revolut_payment_view'];
        } else {
            $data['payment_revolut_payment_view'] = $this->config->get('payment_revolut_payment_view');
        }

        if (isset($this->request->post['payment_revolut_total'])) {
            $data['payment_revolut_total'] = $this->request->post['payment_revolut_total'];
        } else {
            $data['payment_revolut_total'] = $this->config->get('payment_revolut_total');
        }

        if (isset($this->request->post['payment_revolut_customise'])) {
            $data['payment_revolut_customise'] = $this->request->post['payment_revolut_customise'];
        } else {
            $data['payment_revolut_customise'] = $this->config->get('payment_revolut_customise');
        }

        if (isset($this->request->post['payment_revolut_background_colour'])) {
            $data['payment_revolut_background_colour'] = $this->request->post['payment_revolut_background_colour'];
        } elseif ($this->config->get('payment_revolut_background_colour')) {
            $data['payment_revolut_background_colour'] = $this->config->get('payment_revolut_background_colour');
        } else {
            $data['payment_revolut_background_colour'] = '#ffffff';
        }

        if (isset($this->request->post['payment_revolut_font_colour'])) {
            $data['payment_revolut_font_colour'] = $this->request->post['payment_revolut_font_colour'];
        } elseif ($this->config->get('payment_revolut_font_colour')) {
            $data['payment_revolut_font_colour'] = $this->config->get('payment_revolut_font_colour');
        } else {
            $data['payment_revolut_font_colour'] = '#848484';
        }

        if (isset($this->request->post['payment_revolut_logo_theme'])) {
            $data['payment_revolut_logo_theme'] = $this->request->post['payment_revolut_logo_theme'];
        } else {
            $data['payment_revolut_logo_theme'] = $this->config->get('payment_revolut_logo_theme');
        }

        if (isset($this->request->post['payment_revolut_pending_status_id'])) {
            $data['payment_revolut_pending_status_id'] = $this->request->post['payment_revolut_pending_status_id'];
        } else {
            $data['payment_revolut_pending_status_id'] = $this->config->get('payment_revolut_pending_status_id');
        }

        if (isset($this->request->post['payment_revolut_authorised_status_id'])) {
            $data['payment_revolut_authorised_status_id'] = $this->request->post['payment_revolut_authorised_status_id'];
        } else {
            $data['payment_revolut_authorised_status_id'] = $this->config->get('payment_revolut_authorised_status_id');
        }

        if (isset($this->request->post['payment_revolut_completed_status_id'])) {
            $data['payment_revolut_completed_status_id'] = $this->request->post['payment_revolut_completed_status_id'];
        } else {
            $data['payment_revolut_completed_status_id'] = $this->config->get('payment_revolut_completed_status_id');
        }

        if (isset($this->request->post['payment_revolut_processing_status_id'])) {
            $data['payment_revolut_processing_status_id'] = $this->request->post['payment_revolut_processing_status_id'];
        } else {
            $data['payment_revolut_processing_status_id'] = $this->config->get('payment_revolut_processing_status_id');
        }

        if (isset($this->request->post['payment_revolut_refunded_status_id'])) {
            $data['payment_revolut_refunded_status_id'] = $this->request->post['payment_revolut_refunded_status_id'];
        } else {
            $data['payment_revolut_refunded_status_id'] = $this->config->get('payment_revolut_refunded_status_id');
        }

        if (isset($this->request->post['payment_revolut_cancelled_status_id'])) {
            $data['payment_revolut_cancelled_status_id'] = $this->request->post['payment_revolut_cancelled_status_id'];
        } else {
            $data['payment_revolut_cancelled_status_id'] = $this->config->get('payment_revolut_cancelled_status_id');
        }

        if (isset($this->request->post['payment_revolut_failed_status_id'])) {
            $data['payment_revolut_failed_status_id'] = $this->request->post['payment_revolut_failed_status_id'];
        } else {
            $data['payment_revolut_failed_status_id'] = $this->config->get('payment_revolut_failed_status_id');
        }

        $this->load->model('localisation/order_status');

        $data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

        if (isset($this->request->post['payment_revolut_geo_zone_id'])) {
            $data['payment_revolut_geo_zone_id'] = $this->request->post['payment_revolut_geo_zone_id'];
        } else {
            $data['payment_revolut_geo_zone_id'] = $this->config->get('payment_revolut_geo_zone_id');
        }

        $this->load->model('localisation/geo_zone');

        $data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

        if (isset($this->request->post['payment_revolut_status'])) {
            $data['payment_revolut_status'] = $this->request->post['payment_revolut_status'];
        } else {
            $data['payment_revolut_status'] = $this->config->get('payment_revolut_status');
        }

        if (isset($this->request->post['payment_revolut_sort_order'])) {
            $data['payment_revolut_sort_order'] = $this->request->post['payment_revolut_sort_order'];
        } else {
            $data['payment_revolut_sort_order'] = $this->config->get('payment_revolut_sort_order');
        }

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $data['user_token'] = $this->session->data['user_token'];

        $this->response->setOutput($this->load->view('extension/payment/revolut', $data));
    }

    public function install() {
        $this->load->model('extension/payment/revolut');

        $this->model_extension_payment_revolut->install();

        $this->load->model('setting/event');

        $this->model_setting_event->addEvent('payment_revolut', 'catalog/model/checkout/order/addOrderHistory/after', 'extension/payment/revolut/eventPostModelAddOrderHistory');
    }

    public function uninstall() {
        $this->load->model('extension/payment/revolut');

        $this->model_extension_payment_revolut->uninstall();

        $this->load->model('setting/event');
        
        $this->model_setting_event->deleteEventByCode('payment_revolut');
    }

    public function setupWebhook() {
        $json = array();

        $this->load->model('extension/payment/revolut');
        $this->load->language('extension/payment/revolut');

        if (isset($this->request->get['setup'])) {
            $http_code = $this->model_extension_payment_revolut->setUpWebhook();

            if ($http_code == '200') {
                $json['success'] = $this->language->get('text_webhook_success');
            } else {
                $json['error'] = $this->getErrorMessage($http_code);
            }
        } elseif (isset($this->request->get['delete'])) {
            $http_code = $this->model_extension_payment_revolut->deleteWebhook();

            if ($http_code == '204') {
                $json['success'] = $this->language->get('text_webhook_delete_success');
            } else {
                $json['error'] = $this->getErrorMessage($http_code);
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function order() {
        if ($this->config->get('payment_revolut_status')) {
            $this->load->model('sale/order');
            $this->load->model('extension/payment/revolut');

            $revolut_order = $this->model_extension_payment_revolut->getOrder($this->request->get['order_id']);

            if (!empty($revolut_order)) {
                $data = $this->load->language('extension/payment/revolut');

                $data['revolut_order'] = $revolut_order;

                $order_info = $this->model_sale_order->getOrder($this->request->get['order_id']);

                $data['order_id'] = $this->request->get['order_id'];
                $data['user_token'] = $this->session->data['user_token'];

                return $this->load->view('extension/payment/revolut_order', $data);
            }
        }
    }

    public function refund() {
        $json = array();

        $this->load->language('extension/payment/revolut');

        if (isset($this->request->get['order_id']) && !empty($this->request->post['refund_amount'])) {
            $this->load->model('sale/order');
            $this->load->model('extension/payment/revolut');

            if (!is_numeric($this->request->post['refund_amount'])) {
                $json['error'] = $this->language->get('error_invalid_refund_amount');
            }

            if (!$json) {
                $revolut_order_info = $this->model_extension_payment_revolut->getOrder($this->request->get['order_id']);

                if ($revolut_order_info) {
                    $http_code = $this->model_extension_payment_revolut->createRefund($revolut_order_info, $this->request->post['refund_amount']);

                    if ($http_code == '200' || $http_code == '201') {
                        $json['success'] = sprintf($this->language->get('text_refund_success'), $revolut_order_info['order_id']);
                    } else {
                        $json['error'] = $this->getErrorMessage($http_code);
                    }
                } else {
                    $json['error'] = $this->language->get('error_invalid_request');
                }
            }
        } else {
            $json['error'] = $this->language->get('error_invalid_request');
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    private function validate() {
        if (!$this->user->hasPermission('modify', 'extension/payment/revolut')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if (!$this->request->post['payment_revolut_api_key']) {
            $this->error['api_key'] = $this->language->get('error_api_key');
        }

        return !$this->error;
    }

    private function getErrorMessage($http_code) {
        $this->load->language('extension/payment/revolut');

        if ($this->language->get('error_' . $http_code) == 'error_' . $http_code) {
            return sprintf($this->language->get('error_unknown'), $http_code);
        } else {
            return $this->language->get('error_' . $http_code);
        }
    }
}