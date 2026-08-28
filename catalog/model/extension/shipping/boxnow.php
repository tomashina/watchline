<?php
class ModelExtensionShippingBoxnow extends Model {
	public function getQuote($address) {
		$this->load->language('extension/shipping/boxnow');

		$status = $this->isCroatianAddress($address) && trim((string)$this->config->get('boxnow_widget_partner_id')) !== '';
		$geo_zone_id = (int)$this->config->get('boxnow_geo_zone_id');

		if ($status && $geo_zone_id) {
			$country_id = isset($address['country_id']) ? (int)$address['country_id'] : 0;
			$zone_id = isset($address['zone_id']) ? (int)$address['zone_id'] : 0;
			$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone_to_geo_zone` WHERE `geo_zone_id` = '" . $geo_zone_id . "' AND `country_id` = '" . $country_id . "' AND (`zone_id` = '" . $zone_id . "' OR `zone_id` = '0')");

			if (!$query->num_rows) {
				$status = false;
			}
		}

		$method_data = array();

		if ($status) {
			$cost = (float)$this->config->get('boxnow_cost');
			$free_total = (float)$this->config->get('boxnow_free_total');

			if ($free_total > 0 && $this->cart->getSubTotal() >= $free_total) {
				$cost = 0;
			}

			$currency_code = isset($this->session->data['currency']) ? $this->session->data['currency'] : $this->config->get('config_currency');

			$quote_data = array();
			$quote_data['boxnow'] = array(
				'code'         => 'boxnow.boxnow',
				'title'        => $this->language->get('text_description'),
				'cost'         => $cost,
				'tax_class_id' => (int)$this->config->get('boxnow_tax_class_id'),
				'text'         => $this->currency->format($this->tax->calculate($cost, $this->config->get('boxnow_tax_class_id'), $this->config->get('config_tax')), $currency_code)
			);

			$method_data = array(
				'code'       => 'boxnow',
				'title'      => $this->language->get('text_title'),
				'quote'      => $quote_data,
				'sort_order' => (int)$this->config->get('boxnow_sort_order'),
				'error'      => false
			);
		}

		return $method_data;
	}

	public function saveOrderLocker($order_id, $locker) {
		$order_id = (int)$order_id;
		$locker_id = $this->sanitizeLockerId(isset($locker['id']) ? $locker['id'] : '');

		if ($order_id < 1 || $locker_id === '') {
			return;
		}

		$this->installSchema();

		$order_number = utf8_substr(trim((string)$this->config->get('boxnow_order_prefix')) . $order_id, 0, 128);
		$locker_label = utf8_substr(trim(strip_tags(isset($locker['label']) ? $locker['label'] : '')), 0, 255);
		$locker_postcode = utf8_substr(preg_replace('/[^0-9A-Za-z -]/', '', trim(isset($locker['postcode']) ? $locker['postcode'] : '')), 0, 32);

		$this->db->query("INSERT INTO `" . DB_PREFIX . "boxnow_order` SET `order_id` = '" . $order_id . "', `order_number` = '" . $this->db->escape($order_number) . "', `locker_id` = '" . $this->db->escape($locker_id) . "', `locker_label` = '" . $this->db->escape($locker_label) . "', `locker_postcode` = '" . $this->db->escape($locker_postcode) . "', `date_added` = NOW(), `date_modified` = NOW() ON DUPLICATE KEY UPDATE `locker_id` = VALUES(`locker_id`), `locker_label` = VALUES(`locker_label`), `locker_postcode` = VALUES(`locker_postcode`), `date_modified` = NOW()");
	}

	private function installSchema() {
		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "boxnow_order` (
			`order_id` int(11) NOT NULL,
			`order_number` varchar(128) NOT NULL DEFAULT '',
			`locker_id` varchar(64) NOT NULL,
			`locker_label` varchar(255) NOT NULL DEFAULT '',
			`locker_postcode` varchar(32) NOT NULL DEFAULT '',
			`parcel_id` varchar(64) NOT NULL DEFAULT '',
			`reference_number` varchar(128) NOT NULL DEFAULT '',
			`status` varchar(64) NOT NULL DEFAULT '',
			`request_payload` mediumtext NULL,
			`response_payload` mediumtext NULL,
			`date_added` datetime NOT NULL,
			`date_modified` datetime NOT NULL,
			PRIMARY KEY (`order_id`),
			KEY `parcel_id` (`parcel_id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8");
	}

	private function isCroatianAddress($address) {
		if (!empty($address['iso_code_2'])) {
			return strtoupper($address['iso_code_2']) === 'HR';
		}

		if (empty($address['country_id'])) {
			return false;
		}

		$this->load->model('localisation/country');
		$country_info = $this->model_localisation_country->getCountry((int)$address['country_id']);

		return !empty($country_info['iso_code_2']) && strtoupper($country_info['iso_code_2']) === 'HR';
	}

	private function sanitizeLockerId($locker_id) {
		return utf8_substr(preg_replace('/[^A-Za-z0-9_-]/', '', trim((string)$locker_id)), 0, 64);
	}
}
