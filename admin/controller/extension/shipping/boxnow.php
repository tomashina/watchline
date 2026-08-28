<?php
class ControllerExtensionShippingBoxnow extends Controller {
	private $error = array();

	public function index() {
		$data = $this->load->language('extension/shipping/boxnow');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');
		$this->load->model('extension/shipping/boxnow');

		$this->model_extension_shipping_boxnow->installSchema();

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			if (!isset($this->request->post['boxnow_client_secret']) || trim((string)$this->request->post['boxnow_client_secret']) === '') {
				$this->request->post['boxnow_client_secret'] = (string)$this->config->get('boxnow_client_secret');
			}

			$this->model_setting_setting->editSetting('boxnow', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=shipping', true));
		}

		$data['error_warning'] = isset($this->error['warning']) ? $this->error['warning'] : '';

		$error_fields = array(
			'api_url',
			'widget_url',
			'tracking_url',
			'partner_id',
			'widget_partner_id',
			'origin_location_id',
			'client_id',
			'client_secret',
			'origin_name',
			'origin_email',
			'origin_phone',
			'order_prefix',
			'cost',
			'free_total',
			'compartment_size'
		);

		foreach ($error_fields as $field) {
			$data['error_' . $field] = isset($this->error[$field]) ? $this->error[$field] : '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=shipping', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/shipping/boxnow', 'token=' . $this->session->data['token'], true)
		);

		$data['action'] = $this->url->link('extension/shipping/boxnow', 'token=' . $this->session->data['token'], true);
		$data['cancel'] = $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=shipping', true);
		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		$defaults = array(
			'boxnow_api_url'            => 'https://api-production.boxnow.hr',
			'boxnow_widget_url'         => 'https://widget-cdn.boxnow.hr/map-widget/client/v5.js',
			'boxnow_tracking_url'       => 'https://track.boxnow.hr/?track={parcel}',
			'boxnow_partner_id'         => '',
			'boxnow_widget_partner_id'  => '',
			'boxnow_origin_location_id' => '',
			'boxnow_client_id'          => '',
			'boxnow_origin_name'        => $this->config->get('config_name'),
			'boxnow_origin_email'       => $this->config->get('config_email'),
			'boxnow_origin_phone'       => $this->config->get('config_telephone'),
			'boxnow_order_prefix'       => 'WATCHLINE-',
			'boxnow_compartment_size'   => '2',
			'boxnow_allow_return'       => '1',
			'boxnow_cost'               => '0.00',
			'boxnow_free_total'         => '',
			'boxnow_tax_class_id'       => '0',
			'boxnow_geo_zone_id'        => '0',
			'boxnow_status'             => '0',
			'boxnow_sort_order'         => '0'
		);

		foreach ($defaults as $key => $default) {
			if (isset($this->request->post[$key])) {
				$data[$key] = $this->request->post[$key];
			} else {
				$value = $this->config->get($key);
				$data[$key] = ($value !== null && $value !== '') ? $value : $default;
			}
		}

		// Never render the stored secret back into the page source. Leaving this
		// field empty keeps the previously saved value.
		$data['boxnow_client_secret'] = '';
		$data['client_secret_saved'] = trim((string)$this->config->get('boxnow_client_secret')) !== '';

		$this->load->model('localisation/tax_class');
		$data['tax_classes'] = $this->model_localisation_tax_class->getTaxClasses();

		$this->load->model('localisation/geo_zone');
		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/shipping/boxnow', $data));
	}

	public function install() {
		$this->load->model('extension/shipping/boxnow');
		$this->model_extension_shipping_boxnow->installSchema();
	}

	public function createShipment() {
		$this->load->language('extension/shipping/boxnow');

		$json = array();
		$order_id = isset($this->request->get['order_id']) ? (int)$this->request->get['order_id'] : 0;

		if ($this->request->server['REQUEST_METHOD'] !== 'POST') {
			$json['error'] = $this->language->get('error_invalid_request');
		} elseif (!$this->user->hasPermission('modify', 'extension/shipping/boxnow') || !$this->user->hasPermission('modify', 'sale/order')) {
			$json['error'] = $this->language->get('error_permission');
		} elseif ($order_id < 1) {
			$json['error'] = $this->language->get('error_not_boxnow_order');
		} else {
			try {
				$this->load->model('extension/shipping/boxnow');
				$shipment = $this->model_extension_shipping_boxnow->createShipment($order_id);

				$json['success'] = !empty($shipment['existing']) ? $this->language->get('text_shipment_exists') : $this->language->get('text_shipment_created');
				$json['parcel_id'] = isset($shipment['parcel_id']) ? $shipment['parcel_id'] : '';
				$json['reference_number'] = isset($shipment['reference_number']) ? $shipment['reference_number'] : '';
				$json['tracking_url'] = $this->model_extension_shipping_boxnow->getTrackingUrl($json['parcel_id']);
				$json['label'] = str_replace('&amp;', '&', $this->url->link('extension/shipping/boxnow/label', 'token=' . $this->session->data['token'] . '&order_id=' . $order_id, true));
			} catch (Exception $exception) {
				$json['error'] = $exception->getMessage();
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function label() {
		$this->load->language('extension/shipping/boxnow');

		$order_id = isset($this->request->get['order_id']) ? (int)$this->request->get['order_id'] : 0;

		if (!$this->user->hasPermission('access', 'extension/shipping/boxnow') || !$this->user->hasPermission('access', 'sale/order')) {
			$this->response->redirect($this->url->link('error/permission', 'token=' . $this->session->data['token'], true));
			return;
		}

		try {
			$this->load->model('extension/shipping/boxnow');
			$pdf = $this->model_extension_shipping_boxnow->getLabel($order_id);

			$this->response->addHeader('Content-Type: application/pdf');
			$this->response->addHeader('Content-Disposition: inline; filename="boxnow-' . $order_id . '.pdf"');
			$this->response->setOutput($pdf);
		} catch (Exception $exception) {
			$this->session->data['error_warning'] = $exception->getMessage();
			$this->response->redirect($this->url->link('sale/order/info', 'token=' . $this->session->data['token'] . '&order_id=' . $order_id, true));
		}
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/shipping/boxnow')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		$status = !empty($this->request->post['boxnow_status']);
		$api_url = isset($this->request->post['boxnow_api_url']) ? trim($this->request->post['boxnow_api_url']) : '';
		$widget_url = isset($this->request->post['boxnow_widget_url']) ? trim($this->request->post['boxnow_widget_url']) : '';
		$tracking_url = isset($this->request->post['boxnow_tracking_url']) ? trim($this->request->post['boxnow_tracking_url']) : '';

		if (!$this->isHttpsUrl($api_url)) {
			$this->error['api_url'] = $this->language->get('error_api_url');
		}

		if (!$this->isHttpsUrl($widget_url)) {
			$this->error['widget_url'] = $this->language->get('error_widget_url');
		}

		if ($tracking_url !== '' && !$this->isHttpsUrl(str_replace('{parcel}', 'parcel-id', $tracking_url))) {
			$this->error['tracking_url'] = $this->language->get('error_tracking_url');
		}

		$cost = isset($this->request->post['boxnow_cost']) ? trim($this->request->post['boxnow_cost']) : '';
		$free_total = isset($this->request->post['boxnow_free_total']) ? trim($this->request->post['boxnow_free_total']) : '';

		if ($cost === '' || !is_numeric($cost) || (float)$cost < 0) {
			$this->error['cost'] = $this->language->get('error_cost');
		}

		if ($free_total !== '' && (!is_numeric($free_total) || (float)$free_total < 0)) {
			$this->error['free_total'] = $this->language->get('error_free_total');
		}

		$compartment_size = isset($this->request->post['boxnow_compartment_size']) ? (string)$this->request->post['boxnow_compartment_size'] : '';

		if (!in_array($compartment_size, array('0', '1', '2', '3'), true)) {
			$this->error['compartment_size'] = $this->language->get('error_compartment_size');
		}

		if ($status) {
			$required = array(
				'partner_id',
				'widget_partner_id',
					'origin_location_id',
					'client_id',
					'origin_name',
					'origin_email',
					'origin_phone',
					'order_prefix'
			);

			foreach ($required as $field) {
				$key = 'boxnow_' . $field;

				if (!isset($this->request->post[$key]) || trim((string)$this->request->post[$key]) === '') {
					$this->error[$field] = $this->language->get('error_required');
				}
			}

			if (!empty($this->request->post['boxnow_partner_id']) && !preg_match('/^\d+$/', trim($this->request->post['boxnow_partner_id']))) {
				$this->error['partner_id'] = $this->language->get('error_partner_id');
			}

			if (!empty($this->request->post['boxnow_widget_partner_id']) && !preg_match('/^\d+$/', trim($this->request->post['boxnow_widget_partner_id']))) {
				$this->error['widget_partner_id'] = $this->language->get('error_partner_id');
			}

			if (!empty($this->request->post['boxnow_origin_email']) && !filter_var(trim($this->request->post['boxnow_origin_email']), FILTER_VALIDATE_EMAIL)) {
				$this->error['origin_email'] = $this->language->get('error_email');
			}

			if (!empty($this->request->post['boxnow_order_prefix']) && !preg_match('/^[A-Za-z0-9._-]{1,64}$/', trim($this->request->post['boxnow_order_prefix']))) {
				$this->error['order_prefix'] = $this->language->get('error_order_prefix');
			}

			$posted_secret = isset($this->request->post['boxnow_client_secret']) ? trim((string)$this->request->post['boxnow_client_secret']) : '';
			$saved_secret = trim((string)$this->config->get('boxnow_client_secret'));

			if ($posted_secret === '' && $saved_secret === '') {
				$this->error['client_secret'] = $this->language->get('error_required');
			}
		}

		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_validation');
		}

		return !$this->error;
	}

	private function isHttpsUrl($url) {
		if (!filter_var($url, FILTER_VALIDATE_URL)) {
			return false;
		}

		$scheme = strtolower((string)parse_url($url, PHP_URL_SCHEME));

		return $scheme === 'https';
	}
}
