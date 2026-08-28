<?php 
class ControllerExtensionPaymentWSPay extends Controller {
	private $error = array(); 

	public function index() {
		$this->load->language('extension/payment/wspay');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('wspay', $this->request->post);				

			$this->session->data['success'] = $this->language->get('text_success');

           $this->response->redirect($this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=payment', true));
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_all_zones'] = $this->language->get('text_all_zones');
		$data['text_yes'] = $this->language->get('text_yes');
		$data['text_no'] = $this->language->get('text_no');
        $data['text_successful'] = $this->language->get('text_successful');
        $data['text_declined'] = $this->language->get('text_declined');
        $data['text_off'] = $this->language->get('text_off');

        $data['text_edit'] = $this->language->get('text_edit');
        $data['help_entry_callback'] = $this->language->get('help_entry_callback');
        $data['help_entry_total'] = $this->language->get('help_entry_total');

		$data['entry_merchant'] = $this->language->get('entry_merchant');
		$data['entry_password'] = $this->language->get('entry_password');
		$data['entry_callback'] = $this->language->get('entry_callback');
		$data['entry_authorisationtype'] = $this->language->get('entry_authorisationtype');
		$data['entry_authorisationtype0'] = $this->language->get('entry_authorisationtype0');
		$data['entry_authorisationtype1'] = $this->language->get('entry_authorisationtype1');
		$data['entry_test'] = $this->language->get('entry_test');
		$data['entry_total'] = $this->language->get('entry_total');
		$data['entry_order_status'] = $this->language->get('entry_order_status');
		$data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['merchant'])) {
			$data['error_merchant'] = $this->error['merchant'];
		} else {
			$data['error_merchant'] = '';
		}

		if (isset($this->error['password'])) {
			$data['error_password'] = $this->error['password'];
		} else {
			$data['error_password'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=payment', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/payment/wspay', 'token=' . $this->session->data['token'], true)
		);

		$data['action'] = $this->url->link('extension/payment/wspay', 'token=' . $this->session->data['token'], true);

		
		$data['cancel'] = $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=payment', true);

		if (isset($this->request->post['wspay_merchant'])) {
			$data['wspay_merchant'] = $this->request->post['wspay_merchant'];
		} else {
			$data['wspay_merchant'] = $this->config->get('wspay_merchant');
		}

		if (isset($this->request->post['wspay_password'])) {
			$data['wspay_password'] = $this->request->post['wspay_password'];
		} else {
			$data['wspay_password'] = $this->config->get('wspay_password');
		}

        
        if (isset($this->request->post['wspay_authorisationtype'])) {
			$data['wspay_authorisationtype'] = $this->request->post['wspay_authorisationtype'];
		} else {
			$data['wspay_authorisationtype'] = $this->config->get('wspay_authorisationtype');
		}


		$data['callback'] = HTTP_CATALOG . 'index.php?route=extension/payment/wspay/callback';

		if (isset($this->request->post['wspay_test'])) {
			$data['wspay_test'] = $this->request->post['wspay_test'];
		} else {
			$data['wspay_test'] = $this->config->get('wspay_test');
		}


		if (isset($this->request->post['wspay_password'])) {
			$data['wspay_password'] = $this->request->post['wspay_password'];
		} else {
			$data['wspay_password'] = $this->config->get('wspay_password');
		}

		if (isset($this->request->post['wspay_total'])) {
			$data['wspay_total'] = $this->request->post['wspay_total'];
		} else {
			$data['wspay_total'] = $this->config->get('wspay_total');
		} 

		if (isset($this->request->post['wspay_order_status_id'])) {
			$data['wspay_order_status_id'] = $this->request->post['wspay_order_status_id'];
		} else {
			$data['wspay_order_status_id'] = $this->config->get('wspay_order_status_id');
		} 

		$this->load->model('localisation/order_status');

		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		if (isset($this->request->post['wspay_geo_zone_id'])) {
			$data['wspay_geo_zone_id'] = $this->request->post['wspay_geo_zone_id'];
		} else {
			$data['wspay_geo_zone_id'] = $this->config->get('wspay_geo_zone_id');
		} 

		$this->load->model('localisation/geo_zone');

		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		if (isset($this->request->post['wspay_status'])) {
			$data['wspay_status'] = $this->request->post['wspay_status'];
		} else {
			$data['wspay_status'] = $this->config->get('wspay_status');
		}

		if (isset($this->request->post['wspay_sort_order'])) {
			$data['wspay_sort_order'] = $this->request->post['wspay_sort_order'];
		} else {
			$data['wspay_sort_order'] = $this->config->get('wspay_sort_order');
		}

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('extension/payment/wspay.tpl', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/payment/wspay')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->post['wspay_merchant']) {
			$this->error['merchant'] = $this->language->get('error_merchant');
		}

		if (!$this->request->post['wspay_password']) {
			$this->error['password'] = $this->language->get('error_password');
		}

        return !$this->error;

		/*if (!$this->error) {
			return true;
		} else {
			return false;
		}*/
	}
}
?>