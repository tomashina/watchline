<?php
class ControllerExtensionShippingBoxnow extends Controller {
	public function saveLocker() {
		$this->load->language('extension/shipping/boxnow');

		$json = array();

		if ($this->request->server['REQUEST_METHOD'] !== 'POST') {
			$json['error'] = $this->language->get('error_invalid_request');
		} else {
			$locker = $this->getLockerFromRequest();

			if ($locker['id'] === '') {
				unset($this->session->data['boxnow_locker']);
				$json['error'] = $this->language->get('error_locker');
			} else {
				$this->session->data['boxnow_locker'] = $locker;
				$json['success'] = true;
				$json['locker'] = $locker;
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	private function getLockerFromRequest() {
		$id = isset($this->request->post['boxnow_locker_id']) ? $this->request->post['boxnow_locker_id'] : '';
		$label = isset($this->request->post['boxnow_locker_label']) ? $this->request->post['boxnow_locker_label'] : '';
		$postcode = isset($this->request->post['boxnow_locker_postcode']) ? $this->request->post['boxnow_locker_postcode'] : '';

		return array(
			'id'       => utf8_substr(preg_replace('/[^A-Za-z0-9_-]/', '', trim((string)$id)), 0, 64),
			'label'    => utf8_substr(trim(strip_tags((string)$label)), 0, 255),
			'postcode' => utf8_substr(preg_replace('/[^0-9A-Za-z -]/', '', trim((string)$postcode)), 0, 32)
		);
	}
}
