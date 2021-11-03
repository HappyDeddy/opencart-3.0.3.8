<?php
class ModelExtensionPaymentGlobalPayments extends Model {
	public function getMethod($address, $total) {
		$this->load->language('extension/payment/globalpayments');

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('payment_globalpayments_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");

		if ($this->config->get('payment_globalpayments_total') > 0 && $this->config->get('payment_globalpayments_total') > $total) {
			$status = false;
		} elseif (!$this->config->get('payment_globalpayments_geo_zone_id')) {
			$status = true;
		} elseif ($query->num_rows) {
			$status = true;
		} else {
			$status = false;
		}

		$method_data = array();

		if ($status) {
			$method_data = array(
				'code'       => 'globalpayments',
				'title'      => $this->language->get('text_title'),
				'terms'      => '',
				'sort_order' => $this->config->get('payment_globalpayments_sort_order')
			);
		}

		return $method_data;
	}
	
	public function addOrder($order_id, $order_code) {
		$this->db->query("UPDATE " . DB_PREFIX . "globalpayments_order SET order_code = '" . $this->db->escape($order_code) . "' WHERE order_id = '" . (int)$order_id . "'");
		
		if (!$this->db->countAffected()) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "globalpayments_order SET order_id = '" . (int)$order_id . "', order_code = '" . $this->db->escape($order_code) . "'");
		}
	}
	
	public function getOrder($order_id) {
		$this->db->query("SELECT * FROM " . DB_PREFIX . "globalpayments_order WHERE order_id = '" . (int)$order_id . "'");
		
		return $query->row;
	}
	
	public function getOrderByCode($order_code) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "globalpayments_order WHERE order_code = '" . $this->db->escape($order_code) . "'");
		
		return $query->row;
	}
	
	public function log($data, $title = null) {
		if ($this->config->get('payment_globalpayments_debug')) {
			$log = new Log('globalpayments.log');
			$log->write('GlobalPayments debug (' . $title . '): ' . json_encode($data));
		}
	}
}