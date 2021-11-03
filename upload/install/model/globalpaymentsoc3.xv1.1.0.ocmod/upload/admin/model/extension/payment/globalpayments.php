<?php
class ModelExtensionPaymentGlobalPayments extends Model {
		
	public function refreshOrders() {
		$orders_id = array();
		
		$query = $this->db->query("SELECT order_id FROM " . DB_PREFIX . "order");
		
		foreach ($query->rows as $order) {
			$orders_id[$order['order_id']] = $order['order_id'];
		}
		
		$query = $this->db->query("SELECT order_id FROM " . DB_PREFIX . "globalpayments_order");
		
		foreach ($query->rows as $order) {
			if (!isset($orders_id[$order['order_id']])) {
				$this->db->query("DELETE FROM " . DB_PREFIX . "globalpayments_order WHERE order_id = '" . (int)$order['order_id'] . "'");
			}
		}
	}
	
	public function installExtension() {
		$this->db->query("CREATE TABLE IF NOT EXISTS " . DB_PREFIX . "globalpayments_order (order_id INT(11) NOT NULL, order_code VARCHAR(26) NOT NULL, PRIMARY KEY (order_id), KEY order_code (order_code)) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci");
	}
	
	public function uninstallExtension() {
		$this->db->query("DROP TABLE IF EXISTS " . DB_PREFIX . "globalpayments_order");
	}
}
