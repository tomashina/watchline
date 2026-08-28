<?php
class ControllerCommonHome extends Controller {
	public function index() {
		$this->document->setTitle($this->config->get('config_meta_title'));
		$this->document->setDescription($this->config->get('config_meta_description'));
		$this->document->setKeywords($this->config->get('config_meta_keyword'));

		if (isset($this->request->get['route'])) {
			$this->document->addLink($this->config->get('config_url'), 'canonical');
		}

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');

		// Remove only the old intentionally hidden page-builder H1. Visible
		// editorial headings are preserved, while the template owns the main H1.
		$hidden_h1 = '/<h1\b(?=[^>]*visibility\s*:\s*hidden)(?=[^>]*font-size\s*:\s*0(?:px)?)[^>]*>.*?<\/h1>/is';
		$data['content_top'] = preg_replace($hidden_h1, '', $data['content_top']);
		$data['content_bottom'] = preg_replace($hidden_h1, '', $data['content_bottom']);
		$data['heading_title'] = 'Satovi, sunčane naočale i nakit – Watch Line';
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('common/home', $data));
	}
}
