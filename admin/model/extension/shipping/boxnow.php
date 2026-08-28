<?php
class ModelExtensionShippingBoxnow extends Model {
	private $access_token = '';

	public function installSchema() {
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

	public function getOrder($order_id) {
		$this->installSchema();

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "boxnow_order` WHERE `order_id` = '" . (int)$order_id . "' LIMIT 1");

		return $query->num_rows ? $query->row : array();
	}

	public function createShipment($order_id) {
		$order_id = (int)$order_id;

		if ($order_id < 1) {
			throw new InvalidArgumentException($this->language->get('error_not_boxnow_order'));
		}

		$this->installSchema();

		$lock_name = 'watchline_boxnow_' . $order_id;
		$lock = $this->db->query("SELECT GET_LOCK('" . $this->db->escape($lock_name) . "', 10) AS acquired");

		if (!$lock->num_rows || (int)$lock->row['acquired'] !== 1) {
			throw new RuntimeException($this->language->get('error_processing'));
		}

		try {
			return $this->createShipmentUnlocked($order_id);
		} finally {
			$this->db->query("SELECT RELEASE_LOCK('" . $this->db->escape($lock_name) . "')");
		}
	}

	public function getLabel($order_id) {
		$boxnow_order = $this->getOrder($order_id);

		if (empty($boxnow_order['parcel_id'])) {
			throw new RuntimeException($this->language->get('error_missing_parcel'));
		}

		$pdf = $this->apiRequest('GET', '/api/v1/parcels/' . rawurlencode($boxnow_order['parcel_id']) . '/label.pdf', null, true);

		if (substr((string)$pdf, 0, 5) !== '%PDF-') {
			throw new RuntimeException($this->language->get('error_invalid_label'));
		}

		return $pdf;
	}

	public function getTrackingUrl($parcel_id) {
		$parcel_id = trim((string)$parcel_id);

		if ($parcel_id === '') {
			return '';
		}

		$url = trim((string)$this->getConfig('tracking_url', 'https://track.boxnow.hr/?track={parcel}'));

		if ($url === '') {
			return '';
		}

		if (strpos($url, '{parcel}') !== false) {
			return str_replace('{parcel}', rawurlencode($parcel_id), $url);
		}

		return rtrim($url, '/') . '/' . rawurlencode($parcel_id);
	}

	private function createShipmentUnlocked($order_id) {
		$boxnow_order = $this->getOrder($order_id);

		if (!$boxnow_order) {
			throw new RuntimeException($this->language->get('error_not_boxnow_order'));
		}

		if (!empty($boxnow_order['parcel_id'])) {
			$boxnow_order['existing'] = true;

			return $boxnow_order;
		}

		if (trim((string)$this->getConfig('client_id')) === '' || trim((string)$this->getConfig('client_secret')) === '') {
			throw new RuntimeException($this->language->get('error_credentials'));
		}

		$this->load->model('sale/order');

		$order_info = $this->model_sale_order->getOrder($order_id);

		if (!$order_info || $order_info['shipping_code'] !== 'boxnow.boxnow') {
			throw new RuntimeException($this->language->get('error_not_boxnow_order'));
		}

		$order_number = isset($boxnow_order['order_number']) ? trim((string)$boxnow_order['order_number']) : '';

		if ($order_number === '') {
			$order_number = $this->buildOrderNumber($order_id);
			$this->db->query("UPDATE `" . DB_PREFIX . "boxnow_order` SET `order_number` = '" . $this->db->escape($order_number) . "', `date_modified` = NOW() WHERE `order_id` = '" . $order_id . "'");
		}

		$payload = $this->buildDeliveryRequest($order_info, $boxnow_order, $order_number);

		// A timed-out delivery request may still have been accepted by BOX NOW.
		// Recover it by the unique order number before ever sending a second POST.
		if (in_array($boxnow_order['status'], array('creating', 'error'), true)) {
			$recovered = $this->findParcelByOrderNumber($order_number);

			if ($recovered) {
				$this->storeCreatedShipment($order_id, $recovered);

				return $this->getOrder($order_id);
			}
		}

		$this->db->query("UPDATE `" . DB_PREFIX . "boxnow_order` SET `status` = 'creating', `request_payload` = '" . $this->db->escape(json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)) . "', `date_modified` = NOW() WHERE `order_id` = '" . (int)$order_id . "'");

		try {
			$response = $this->apiRequest('POST', '/api/v1/delivery-requests', $payload);
			$this->storeCreatedShipment($order_id, $response);
		} catch (Exception $exception) {
			$recovered = $this->findParcelByOrderNumber($order_number);

			if ($recovered) {
				$this->storeCreatedShipment($order_id, $recovered);

				return $this->getOrder($order_id);
			}

			$this->db->query("UPDATE `" . DB_PREFIX . "boxnow_order` SET `status` = 'error', `response_payload` = '" . $this->db->escape($exception->getMessage()) . "', `date_modified` = NOW() WHERE `order_id` = '" . (int)$order_id . "'");
			throw $exception;
		}

		return $this->getOrder($order_id);
	}

	private function findParcelByOrderNumber($order_number) {
		try {
			$response = $this->apiRequest('GET', '/api/v1/parcels?limit=50&orderNumber=' . rawurlencode($order_number));
		} catch (Exception $exception) {
			return array();
		}

		$candidates = array();

		if (!empty($response['data']) && is_array($response['data'])) {
			$candidates = $response['data'];
		} elseif (!empty($response['parcels']) && is_array($response['parcels'])) {
			$candidates = $response['parcels'];
		}

		foreach ($candidates as $candidate) {
			if (!is_array($candidate)) {
				continue;
			}

			$candidate_order_number = isset($candidate['orderNumber']) ? trim((string)$candidate['orderNumber']) : '';
			$parcel_id = isset($candidate['id']) ? trim((string)$candidate['id']) : (isset($candidate['parcelId']) ? trim((string)$candidate['parcelId']) : '');

			if ($parcel_id !== '' && ($candidate_order_number === '' || $candidate_order_number === (string)$order_number)) {
				return array(
					'referenceNumber' => isset($candidate['referenceNumber']) ? (string)$candidate['referenceNumber'] : '',
					'parcels'         => array(array('id' => $parcel_id)),
					'recovered'       => $candidate
				);
			}
		}

		return array();
	}

	private function storeCreatedShipment($order_id, $response) {
		$parcel_id = !empty($response['parcels'][0]['id']) ? trim((string)$response['parcels'][0]['id']) : '';
		$reference_number = isset($response['referenceNumber']) ? trim((string)$response['referenceNumber']) : '';

		if ($parcel_id === '') {
			throw new RuntimeException($this->language->get('error_missing_parcel_id'));
		}

		$response_payload = json_encode($response, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

		$this->db->query("UPDATE `" . DB_PREFIX . "boxnow_order` SET `parcel_id` = '" . $this->db->escape($parcel_id) . "', `reference_number` = '" . $this->db->escape($reference_number) . "', `status` = 'created', `response_payload` = '" . $this->db->escape($response_payload) . "', `date_modified` = NOW() WHERE `order_id` = '" . (int)$order_id . "'");
	}

	private function buildDeliveryRequest($order_info, $boxnow_order, $order_number) {
		$total = number_format((float)$order_info['total'], 2, '.', '');
		$is_cod = $this->isCashOnDelivery($order_info);
		$item = array(
			'id'     => $order_number . '-1',
			'name'   => 'Order ' . $order_number,
			'value'  => $total,
			'weight' => 0
		);

		$compartment_size = (int)$this->getConfig('compartment_size', '2');

		if ($compartment_size > 0) {
			$item['compartmentSize'] = $compartment_size;
		}

		return array(
			'orderNumber'         => $order_number,
			'invoiceValue'        => $total,
			'paymentMode'         => $is_cod ? 'cod' : 'prepaid',
			'amountToBeCollected' => $is_cod ? $total : '0.00',
			'allowReturn'         => (bool)$this->getConfig('allow_return', '1'),
			'origin'              => array(
				'contactNumber' => $this->normalizePhone($this->getConfig('origin_phone', $this->config->get('config_telephone'))),
				'contactEmail'  => $this->getConfig('origin_email', $this->config->get('config_email')),
				'contactName'   => $this->getConfig('origin_name', $this->config->get('config_name')),
				'locationId'    => (string)$this->getConfig('origin_location_id')
			),
			'destination'         => array(
				'contactNumber' => $this->normalizePhone($order_info['telephone']),
				'contactEmail'  => $order_info['email'],
				'contactName'   => trim($order_info['firstname'] . ' ' . $order_info['lastname']),
				'locationId'    => $boxnow_order['locker_id']
			),
			'items'               => array($item)
		);
	}

	private function buildOrderNumber($order_id) {
		return utf8_substr(trim((string)$this->getConfig('order_prefix', 'WATCHLINE-')) . (int)$order_id, 0, 128);
	}

	private function getAccessToken() {
		if ($this->access_token !== '') {
			return $this->access_token;
		}

		$response = $this->apiRequest('POST', '/api/v1/auth-sessions', array(
			'grant_type'    => 'client_credentials',
			'client_id'     => $this->getConfig('client_id'),
			'client_secret' => $this->getConfig('client_secret')
		), false, false);

		if (empty($response['access_token'])) {
			throw new RuntimeException($this->language->get('error_access_token'));
		}

		$this->access_token = (string)$response['access_token'];

		return $this->access_token;
	}

	private function apiRequest($method, $endpoint, $payload = null, $binary = false, $authenticated = true) {
		if (!function_exists('curl_init')) {
			throw new RuntimeException($this->language->get('error_curl'));
		}

		$url = rtrim($this->getConfig('api_url', 'https://api-production.boxnow.hr'), '/') . $endpoint;
		$headers = array('Accept: ' . ($binary ? 'application/pdf' : 'application/json'));
		$partner_id = trim((string)$this->getConfig('partner_id'));

		if ($partner_id !== '') {
			$headers[] = 'X-PartnerID: ' . $partner_id;
		}

		if ($authenticated) {
			$headers[] = 'Authorization: Bearer ' . $this->getAccessToken();
		}

		if ($payload !== null) {
			$encoded_payload = json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

			if ($encoded_payload === false) {
				throw new RuntimeException($this->language->get('error_json_encode'));
			}

			$headers[] = 'Content-Type: application/json';
		}

		$curl = curl_init($url);

		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 15);
		curl_setopt($curl, CURLOPT_TIMEOUT, 45);

		if ($payload !== null) {
			curl_setopt($curl, CURLOPT_POSTFIELDS, $encoded_payload);
		}

		$body = curl_exec($curl);
		$curl_error = curl_error($curl);
		$http_code = (int)curl_getinfo($curl, CURLINFO_HTTP_CODE);

		curl_close($curl);

		if ($body === false || $curl_error !== '') {
			throw new RuntimeException('BOX NOW API: ' . $curl_error);
		}

		if ($http_code < 200 || $http_code >= 300) {
			throw new RuntimeException($this->formatApiError($http_code, $body));
		}

		if ($binary) {
			return $body;
		}

		$decoded = json_decode($body, true);

		if (!is_array($decoded)) {
			throw new RuntimeException($this->language->get('error_invalid_json'));
		}

		return $decoded;
	}

	private function formatApiError($http_code, $body) {
		$decoded = json_decode($body, true);
		$message = '';

		if (is_array($decoded)) {
			foreach (array('message', 'error', 'detail', 'title') as $key) {
				if (!empty($decoded[$key]) && is_scalar($decoded[$key])) {
					$message = trim((string)$decoded[$key]);
					break;
				}
			}
		}

		if ($message === '') {
			$message = trim(strip_tags((string)$body));
		}

		$message = utf8_substr($message, 0, 500);

		return 'BOX NOW API HTTP ' . (int)$http_code . ($message !== '' ? ': ' . $message : '');
	}

	private function getConfig($key, $default = '') {
		$value = $this->config->get('boxnow_' . $key);

		return ($value !== null && $value !== '') ? $value : $default;
	}

	private function normalizePhone($phone) {
		$phone = preg_replace('/[^0-9+]/', '', (string)$phone);

		if (strpos($phone, '00') === 0) {
			$phone = '+' . substr($phone, 2);
		}

		if (strpos($phone, '0') === 0) {
			$phone = '+385' . substr($phone, 1);
		}

		if ($phone !== '' && strpos($phone, '+') !== 0) {
			$phone = '+' . $phone;
		}

		return $phone;
	}

	private function isCashOnDelivery($order_info) {
		$payment_code = strtolower((string)$order_info['payment_code']);
		$payment_method = strtolower((string)$order_info['payment_method']);

		return strpos($payment_code, 'cod') !== false || strpos($payment_method, 'cash') !== false || strpos($payment_method, 'pouze') !== false;
	}
}
