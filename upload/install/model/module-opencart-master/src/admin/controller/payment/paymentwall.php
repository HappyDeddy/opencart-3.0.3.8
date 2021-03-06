<?php

class ControllerPaymentPaymentwall extends Controller
{
    private $error = array();

    // Generate default configs
    public function install()
    {
        $this->load->model('setting/setting');
        $defaultConfigs = $this->model_setting_setting->getSetting('config');
        $this->model_setting_setting->editSetting('paymentwall', array(
            'paymentwall_complete_status' => $defaultConfigs['config_complete_status_id'],
            'paymentwall_cancel_status' => 7,
            'paymentwall_test' => 0,
            'paymentwall_delivery' => 1,
            'paymentwall_status' => 1,
            'paymentwall_sort_order' => 1,
        ));
    }

    public function uninstall()
    {
        $this->load->model('setting/setting');
        $this->model_setting_setting->deleteSetting('paymentwall');
    }

    /**
     * Index action
     */
    public function index()
    {
        $this->load->model('setting/setting');
        $this->load->model('payment/paymentwall');
        $this->load->language('payment/paymentwall');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $this->model_setting_setting->editSetting('paymentwall', $this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'));
        }

        $translationData = $this->prepareTranslationData();
        $viewData = $this->prepareViewData();
        $data = array_merge($translationData, $viewData);

        $this->response->setOutput($this->load->view('payment/paymentwall.tpl', $data));
    }

    /**
     * @param $key
     * @param $default
     * @return mixed
     */
    protected function getPostData($key, $default)
    {
        return isset($this->request->post[$key]) ? $this->request->post[$key] : $default;
    }

    protected function prepareTranslationData()
    {
        return array(
            'text_edit' => $this->language->get('text_edit'),
            'text_enabled' => $this->language->get('text_enabled'),
            'text_disabled' => $this->language->get('text_disabled'),
            'text_yes' => $this->language->get('text_yes'),
            'text_no' => $this->language->get('text_no'),           

            'entry_key' => $this->language->get('entry_key'),
            'entry_secret' => $this->language->get('entry_secret'),
            'entry_widget' => $this->language->get('entry_widget'),
            'entry_transaction' => $this->language->get('entry_transaction'),
            'entry_total' => $this->language->get('entry_total'),
            'entry_order_status' => $this->language->get('entry_order_status'),
            'entry_geo_zone' => $this->language->get('entry_geo_zone'),
            'entry_status' => $this->language->get('entry_status'),
            'entry_sort_order' => $this->language->get('entry_sort_order'),
            'entry_complete_status' => $this->language->get('entry_complete_status'),
            'entry_cancel_status' => $this->language->get('entry_cancel_status'),
            'entry_test' => $this->language->get('entry_test'),
            'entry_delivery' => $this->language->get('entry_delivery'),
            'entry_success_url' => $this->language->get('entry_success_url'),
            'entry_sort_order' => $this->language->get('entry_sort_order'),
            'entry_active' => $this->language->get('entry_active'),

            'button_save' => $this->language->get('button_save'),
            'button_cancel' => $this->language->get('button_cancel')
        );
    }

    /**
     * Prepare view data
     */
    protected function prepareViewData()
    {
        $this->document->setTitle($this->language->get('heading_title'));

        $data = array();

        $data['heading_title'] = $this->language->get('heading_title');

        $data['statuses'] = $this->model_payment_paymentwall->getAllStatuses();

        $data['error_warning'] = isset($this->error['warning']) ? $this->error['warning'] : '';
        $data['error_key'] = isset($this->error['key']) ? $this->error['key'] : '';
        $data['error_secret'] = isset($this->error['secret']) ? $this->error['secret'] : '';
        $data['error_widget'] = isset($this->error['widget']) ? $this->error['widget'] : '';

        $data['breadcrumbs'] = array(
            array(
                'text' => $this->language->get('text_home'),
                'href' => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
                'separator' => false
            ),
            array(
                'text' => $this->language->get('text_payment'),
                'href' => $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'),
                'separator' => ' :: '
            ),
            array(
                'text' => $this->language->get('heading_title'),
                'href' => $this->url->link('payment/paymentwall', 'token=' . $this->session->data['token'], 'SSL'),
                'separator' => ' :: '
            )
        );

        $data['action'] = $this->url->link('payment/paymentwall', 'token=' . $this->session->data['token'], 'SSL');
        $data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL');

        $data['paymentwall_key'] = $this->getPostData(
            'paymentwall_key',
            $this->config->get('paymentwall_key'));

        $data['paymentwall_secret'] = $this->getPostData(
            'paymentwall_secret',
            $this->config->get('paymentwall_secret'));

        $data['paymentwall_widget'] = $this->getPostData(
            'paymentwall_widget',
            $this->config->get('paymentwall_widget'));

        $data['paymentwall_complete_status'] = $this->getPostData(
            'paymentwall_complete_status',
            $this->config->get('paymentwall_complete_status'));

        $data['paymentwall_cancel_status'] = $this->getPostData(
            'paymentwall_cancel_status',
            $this->config->get('paymentwall_cancel_status'));

        $data['paymentwall_delivery'] = $this->getPostData(
            'paymentwall_delivery',
            $this->config->get('paymentwall_delivery'));

        $data['paymentwall_success_url'] = $this->getPostData(
            'paymentwall_success_url',
            $this->config->get('paymentwall_success_url'));

        $data['paymentwall_test'] = $this->getPostData(
            'paymentwall_test',
            $this->config->get('paymentwall_test'));

        $data['paymentwall_status'] = $this->getPostData(
            'paymentwall_status',
            $this->config->get('paymentwall_status'));

        $data['paymentwall_sort_order'] = $this->getPostData(
            'paymentwall_sort_order',
            $this->config->get('paymentwall_sort_order'));

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        return $data;
    }

    /**
     * Validator
     * @return bool
     */
    protected function validate()
    {
        if (!$this->user->hasPermission('modify', 'payment/paymentwall')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if (!$this->request->post['paymentwall_key']) {
            $this->error['key'] = $this->language->get('error_key');
        }

        if (!$this->request->post['paymentwall_secret']) {
            $this->error['secret'] = $this->language->get('error_secret');
        }

        if (!$this->request->post['paymentwall_widget']) {
            $this->error['widget'] = $this->language->get('error_widget');
        }

        return empty($this->error);
    }
}
