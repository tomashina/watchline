<?php
class ControllerExtensionModuleOrderReviews extends Controller {
	/**
	 * @property   String $module_path String containing the path expression for OrderReviews files.
	 * @property   String $call_model String containing the call to OrderReviews model.
	 */
	private $data = array();
	private $error = array();
	private $version;
	private $module_path;
	private $extensions_link;
	private $language_variables;
	private $moduleModel;
	private $moduleName;
	private $call_model;
	/**
	 * OrderReviews Controller Constructor
	 * initialize necessary dependencies from the OpenCart framework.
	 */
	public function __construct($registry){
		parent::__construct($registry);
		$this->load->config('isenselabs/orderreviews');
		$this->moduleName = $this->config->get('orderreviews_name');
		$this->call_model = $this->config->get('orderreviews_model');
		$this->module_path = $this->config->get('orderreviews_path');
		$this->version = $this->config->get('orderreviews_version');
		
		if (version_compare(VERSION, '2.3.0.0', '>=')) {			
			$this->extensions_link = $this->url->link('extension/extension', 'token=' . $this->session->data['token'].'&type=module', 'SSL');
		} else {
			$this->extensions_link = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');	
		}
			
		$this->load->model($this->module_path);
		$this->moduleModel = $this->{$this->call_model};
    	$this->language_variables = $this->load->language($this->module_path);

    	//Loading framework models
	 	$this->load->model('setting/store');
		$this->load->model('setting/setting');
        $this->load->model('localisation/language');
        if(VERSION >= '2.1.0.1'){
			$this->load->model('customer/customer_group');
		} else {
			$this->load->model('sale/customer_group');
		}

		$this->document->addScript('view/javascript/summernote/summernote.js');
		$this->document->addStyle('view/javascript/summernote/summernote.css');

		$this->data['module_path']     = $this->module_path;
		$this->data['moduleName']      = $this->moduleName;
		$this->data['moduleNameMail']	= $this->moduleName . 'MailTemplate';
		$this->data['moduleNameSmall'] = $this->moduleName;	    
	}

    public function index() { 
    	$this->document->addStyle('view/stylesheet/'.$this->moduleName.'/'.$this->moduleName.'.css');
		$this->document->addScript('view/javascript/'.$this->moduleName.'/nprogress.js');

		if(!$this->moduleModel->checkDbColumn('orderreviews_log', 'review_id')){
            $this->moduleModel->update();
        }

		if(!$this->moduleModel->checkDbTable('orderreviews_mail_log')){
            $this->moduleModel->update392();
        }

		$this->document->setTitle($this->language->get('heading_title').' '.$this->version);

		$this->data['call_model'] = $this->call_model;

		foreach ($this->language_variables as $code => $languageVariable) {
		    $this->data[$code] = $languageVariable;
		}
		
		if(!$this->moduleModel->checkDbTable('orderreviews_setting')){
			$dataStores		= array_merge(array(0 => array('store_id' => '0', 'name' => $this->config->get('config_name') . ' (' . $this->data['text_default'].')', 'url' => HTTP_SERVER, 'ssl' => HTTPS_SERVER)), $this->model_setting_store->getStores());

			$moduleSettings = array();

			foreach ($dataStores  as $st) {
				$moduleSettingsStores				= $this->model_setting_setting->getSetting($this->moduleName, $st['store_id']);
				$moduleSettings[$st['store_id']]	= (isset($moduleSettingsStores[$this->moduleName])) ? $moduleSettingsStores[$this->moduleName] : array();
			}

            $this->moduleModel->update3101($moduleSettings);
        }

        if(!isset($this->request->get['store_id'])) {
           $this->request->get['store_id'] = 0; 
        }
		
		//Check order reviews version compatibility
    	$this->moduleModel->checkOrderReviewsVersionCompatibility();
		$this->moduleModel->checkForTable();

		$this->data['catalogURL']      = $this->getCatalogURL();
        $store = $this->getCurrentStore($this->request->get['store_id']);
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) { 	
            if (!empty($_POST['OaXRyb1BhY2sgLSBDb21'])) {
                $this->request->post[$this->moduleName]['LicensedOn'] = $_POST['OaXRyb1BhY2sgLSBDb21'];
            }
            if (!empty($_POST['cHRpbWl6YXRpb24ef4fe'])) {
                $this->request->post[$this->moduleName]['License'] = json_decode(base64_decode($_POST['cHRpbWl6YXRpb24ef4fe']), true);
            }

			$this->moduleModel->editSetting($this->moduleName, $this->request->post, $this->request->post['store_id']);
			$this->session->data['success'] = $this->language->get('text_success');
			$this->response->redirect($this->url->link($this->module_path, 'store_id='.$this->request->post['store_id'] . '&token=' . $this->session->data['token'], 'SSL'));
        }
		
		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}
		
		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

        $this->data['breadcrumbs']   = array();
        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL'),
        );
        $this->data['breadcrumbs'][] = array(
            'text' => version_compare(VERSION, '2.3.0.0', '>=')? $this->language->get('text_extension') :  $this->language->get('text_module') ,
            'href' => $this->extensions_link
        );
        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link($this->module_path, 'token=' . $this->session->data['token'], 'SSL'),
        );

        
		$this->data['heading_title']  = $this->language->get('heading_title').' '.$this->version;
		
		$this->data['currency']		  = $this->config->get('config_currency');
		$this->data['stores']		  = array_merge(array(0 => array('store_id' => '0', 'name' => $this->config->get('config_name') . ' (' . $this->data['text_default'].')', 'url' => HTTP_SERVER, 'ssl' => HTTPS_SERVER)), $this->model_setting_store->getStores());
		$this->data['languages']      = $this->model_localisation_language->getLanguages();
        //2.2.0.0 language flag image fix
		foreach ($this->data['languages'] as $key => $value) {
			if(version_compare(VERSION, '2.2.0.0', "<")) {
				$this->data['languages'][$key]['flag_url'] = 'view/image/flags/'.$this->data['languages'][$key]['image'];
			} else {
				$this->data['languages'][$key]['flag_url'] = 'language/'.$this->data['languages'][$key]['code'].'/'.$this->data['languages'][$key]['code'].'.png"';
			}
		}
		$this->data['store']          = $store;
		$this->data['token']          = $this->session->data['token'];
		$this->data['action']         = $this->url->link($this->module_path, 'token=' . $this->session->data['token'], 'SSL');
		$this->data['cancel']         = $this->extensions_link;
		$this->data['moduleSettings'] = $this->moduleModel->getSetting($this->moduleName, $store['store_id']);
		$this->data['moduleData']     = (isset($this->data['moduleSettings'][$this->moduleName])) ? $this->data['moduleSettings'][$this->moduleName] : array();
		$this->data['orderStatuses']  = $this->getAllOrderStatuses();

		if (isset($this->data['moduleData']['ReviewMail'])) {
			foreach ($this->data['moduleData']['ReviewMail'] as $key => $value) {
				$this->data['moduleData']['ReviewMail'][$key]['products']= !empty($this->data['moduleData']['ReviewMail'][$key]['products'])?$this->moduleModel->getProductsInIDArray($this->data['moduleData']['ReviewMail'][$key]['products']):array();
				$this->data['moduleData']['ReviewMail'][$key]['categories']= !empty($this->data['moduleData']['ReviewMail'][$key]['categories'])?$this->moduleModel->getCategoriesByID($this->data['moduleData']['ReviewMail'][$key]['categories']):array();
			}
		}

		if(VERSION >= '2.1.0.1'){
			$this->data['customer_groups'] = $this->model_customer_customer_group->getCustomerGroups();
		} else {
			$this->data['customer_groups'] = $this->model_sale_customer_group->getCustomerGroups();
		}
		
		$this->data['e_mail']          = $this->config->get('config_email');

		$this->data['cronCmdArgs'] = '';
		if ($_SERVER['HTTPS'] == 'on' || $_SERVER['SERVER_PORT'] == 443) {
			$this->data['cronCmdArgs'] .= ' https';
		} else {
			$this->data['cronCmdArgs'] .= ' http';
		}
		
		$this->data['header']          = $this->load->controller('common/header');
		$this->data['column_left']     = $this->load->controller('common/column_left');
		$this->data['footer']          = $this->load->controller('common/footer');
	
		$this->response->setOutput($this->load->view($this->module_path.'.tpl', $this->data));
    }
	
	protected function validateForm() {
		if (!$this->user->hasPermission('modify', $this->module_path)) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		return !$this->error;
	}
	
	public function get_review_settings() {		
		
		if(VERSION >= '2.1.0.1'){
			$this->load->model('customer/customer_group');
			$this->data['customer_groups'] = $this->model_customer_customer_group->getCustomerGroups();
		} else {
			$this->load->model('sale/customer_group');
			$this->data['customer_groups'] = $this->model_sale_customer_group->getCustomerGroups();
		}
		
		$this->data['currency']			= $this->config->get('config_currency');	
		$this->data['languages']		= $this->model_localisation_language->getLanguages();
		foreach ($this->data['languages'] as $key => $value) {
			if(version_compare(VERSION, '2.2.0.0', "<")) {
				$this->data['languages'][$key]['flag_url'] = 'view/image/flags/'.$this->data['languages'][$key]['image'];
			} else {
				$this->data['languages'][$key]['flag_url'] = 'language/'.$this->data['languages'][$key]['code'].'/'.$this->data['languages'][$key]['code'].'.png"';
			}
		}
		$this->data['reviewmail']['id'] = $this->request->get['reviewmail_id'];
		$store_id                       = $this->request->get['store_id'];
		$this->data['data']             = $this->moduleModel->getSetting($this->moduleName, $store_id);
		$this->data['moduleName']       = $this->moduleName;
		$this->data['moduleNameMail']	= $this->moduleName . 'MailTemplate';
		$this->data['moduleData']       = (isset($this->data['data'][$this->moduleName])) ? $this->data['data'][$this->moduleName] : array();
		$this->data['orderStatuses']    = $this->getAllOrderStatuses();
		$this->data['newAddition']      = true;
		$this->data['token']			= $this->session->data['token'];
		
		
		$this->response->setOutput($this->load->view($this->module_path.'/tab_reviewtab.tpl', $this->data));
	}

	

	public function getAllSentCoupons()
	{
        if (!empty($this->request->get['page'])) {
            $page = (int) $this->request->get['page'];
        } else {
			$page = 1;	
		}
			
		if(!isset($this->request->get['store_id'])) {
           $this->request->get['store_id'] = 0;
        } 
				
		$this->data['url_link']   = $this->url;
		$this->data['sale_order'] = $this->model_sale_order;
		
		$this->data['store_id']   = $this->request->get['store_id'];
		$this->data['token']      = $this->session->data['token'];
		$this->data['limit']      = 10; // $this->config->get('config_limit_admin')
		$this->data['total']      = $this->moduleModel->getTotalCoupons($this->request->get['store_id']);
		
	    $pagination					= new Pagination();
        $pagination->total			= $this->data['total'];
        $pagination->page			= $page;
        $pagination->limit			= $this->data['limit']; 
        $pagination->url			= $this->url->link($this->module_path.'/getAllSentCoupons','token=' . $this->session->data['token'].'&page={page}&store_id='.$this->request->get['store_id'], 'SSL');

		$this->data['pagination']	= $pagination->render();
        $this->data['sources']			= $this->moduleModel->getAllGeneratedCoupons($page, $this->data['limit'], $this->request->get['store_id']);


		$this->data['results'] 			= sprintf($this->language->get('text_pagination'), ($this->data['total']) ? (($page - 1) * $this->data['limit']) + 1 : 0, ((($page - 1) * $this->data['limit']) > ($this->data['total'] - $this->data['limit'])) ? $this->data['total'] : ((($page - 1) * $this->data['limit']) + $this->data['limit']), $this->data['total'], ceil($this->data['total'] / $this->data['limit']));
		$this->data['token']      = $this->session->data['token'];
        $this->data['store_id']   = $this->request->get['store_id']; 
		
		$this->response->setOutput($this->load->view($this->module_path.'/view_all_coupons.tpl', $this->data));
	}

	public function getAllReviews()
	{	
		
		$this->load->model('catalog/product');
        if (!empty($this->request->get['page'])) {
            $page = (int) $this->request->get['page'];
        } else {
			$page = 1;	
		}
			
		if(!isset($this->request->get['store_id'])) {
           $this->request->get['store_id'] = 0;
        } 
				
		$this->data['url_link']   = $this->url;
		$this->data['sale_order'] = $this->model_sale_order;
		
		$this->data['store_id']   = $this->request->get['store_id'];
		$this->data['token']      = $this->session->data['token'];
		$this->data['limit']      = 10; // $this->config->get('config_limit_admin')
		$this->data['total']      = $this->moduleModel->getTotalReviews($this->request->get['store_id']);

		$module_name    = $this->moduleName;
		$store_id       = isset($this->request->get['store_id']) ? $this->request->get['store_id'] : 0;
		$module_setting = $this->moduleModel->getSetting($module_name, $store_id);
        $this->data['setting'] = (isset($module_setting[$module_name])) ? $module_setting[$module_name] : array();
		$this->data['text_agree'] = $this->language->get('text_agree');
		$this->data['text_privacy_policy'] = $this->language->get('text_privacy_policy');
		$this->data['text_empty_name'] = $this->language->get('text_empty_name');
		
	    $pagination					= new Pagination();
        $pagination->total			= $this->data['total'];
        $pagination->page			= $page;
        $pagination->limit			= $this->data['limit']; 
        $pagination->url			= $this->url->link($this->module_path.'/getAllReviews','token=' . $this->session->data['token'].'&page={page}&store_id='.$this->request->get['store_id'], 'SSL');

		$this->data['pagination']	= $pagination->render();
        $this->data['sources']			= $this->moduleModel->getAllReviews($page, $this->data['limit'], $this->request->get['store_id']);

        foreach ($this->data['sources'] as $key => $source) {
            $product_description = $this->model_catalog_product->getProductDescriptions($source['review_product_id']);
            if ($product_description) {
                $this->data['sources'][$key]['name'] = $product_description[$this->config->get('config_language_id')]['name'];
                $this->data['sources'][$key]['url'] = $this->url->link('catalog/product/edit', 'product_id='.$source['review_product_id'].'&token=' . $this->session->data['token'], 'SSL');
            } else {
                $this->data['sources'][$key]['name'] = 'Not Provided';
                $this->data['sources'][$key]['url'] = $this->url->link('common/home' , '', 'SSL');
            }
        }

		$this->data['results'] 			= sprintf($this->language->get('text_pagination'), ($this->data['total']) ? (($page - 1) * $this->data['limit']) + 1 : 0, ((($page - 1) * $this->data['limit']) > ($this->data['total'] - $this->data['limit'])) ? $this->data['total'] : ((($page - 1) * $this->data['limit']) + $this->data['limit']), $this->data['total'], ceil($this->data['total'] / $this->data['limit']));
		$this->data['token']      = $this->session->data['token'];
        $this->data['store_id']   = $this->request->get['store_id']; 
		
		$this->response->setOutput($this->load->view($this->module_path.'/view_reviews_log.tpl', $this->data));
		
	}
	
	
	public function getAllOrderStatuses() {
		$query = 'SELECT * FROM ' . DB_PREFIX . 'order_status WHERE language_id='.$this->config->get('config_language_id');
		return $this->db->query($query)->rows;
	}

	// Remove all expired coupons
	public function removeallexpiredcoupons() {
		$date_end = date('Y-m-d', time() - 60 * 60 * 24);
		if (isset($this->request->post['remove']) && ($this->request->post['remove']==true)) {
		
			$run_query = $this->db->query("DELETE FROM `" . DB_PREFIX . "coupon` WHERE `name` LIKE '%OrderReviews Coupon [%' AND `date_end`<='".$date_end."'");
			if ($run_query) echo "Success!";
		}
	}
	
    public function install() {
	    $this->moduleModel->install();
    }
    
    public function uninstall() {
        $this->load->model('design/layout');
		
		$this->model_setting_setting->deleteSetting($this->moduleName,0);
		$stores=$this->model_setting_store->getStores();
		foreach ($stores as $store) {
			$this->model_setting_setting->deleteSetting($this->moduleName, $store['store_id']);
		}
        $this->moduleModel->uninstall();
    }
	
    private function getCatalogURL() {
        if (isset($_SERVER['HTTPS']) && (($_SERVER['HTTPS'] == 'on') || ($_SERVER['HTTPS'] == '1'))) {
            $storeURL = HTTPS_CATALOG;
        } else {
            $storeURL = HTTP_CATALOG;
        } 
        return $storeURL;
    }

    private function getServerURL() {
        if (isset($_SERVER['HTTPS']) && (($_SERVER['HTTPS'] == 'on') || ($_SERVER['HTTPS'] == '1'))) {
            $storeURL = HTTPS_SERVER;
        } else {
            $storeURL = HTTP_SERVER;
        } 
        return $storeURL;
    }

    private function getCurrentStore($store_id) {    
        if($store_id && $store_id != 0) {
            $store = $this->model_setting_store->getStore($store_id);
        } else {
            $store['store_id'] = 0;
            $store['name'] = $this->config->get('config_name');
            $store['url'] = $this->getCatalogURL(); 
        }
        return $store;
    }

	public function getlog() {
		foreach ($this->language_variables as $code => $languageVariable) {
		    $this->data[$code] = $languageVariable;
		}
		$this->load->model('sale/order');
		if (!empty($this->request->get['page'])) {
			$page = (int) $this->request->get['page'];
		} else {
			$page = 1;
		}

		if(!isset($this->request->get['store_id'])) {
		   $this->request->get['store_id'] = 0;
		}

		$this->data['url_link']   = $this->url;
		$this->data['sale_order'] = $this->model_sale_order;

		$this->data['store_id']   = $this->request->get['store_id'];
		$this->data['token']      = $this->session->data['token'];
		$this->data['limit']      = 8; // $this->config->get('config_limit_admin')
		$this->data['total']      = $this->moduleModel->getTotalLog($this->request->get['store_id']);

		$pagination					= new Pagination();
		$pagination->total			= $this->data['total'];
		$pagination->page			= $page;
		$pagination->limit			= $this->data['limit'];
		$pagination->url			= $this->url->link('extension/module/'.$this->module_path.'/getlog','token=' . $this->session->data['token'].'&page={page}&store_id='.$this->request->get['store_id'], 'SSL');
		$this->data['pagination']			= $pagination->render();
		$this->data['sources']			= $this->moduleModel->viewLogs($page, $this->data['limit'], $this->request->get['store_id']);

		$this->data['results'] 			= sprintf($this->language->get('text_pagination'), ($this->data['total']) ? (($page - 1) * $this->data['limit']) + 1 : 0, ((($page - 1) * $this->data['limit']) > ($this->data['total'] - $this->data['limit'])) ? $this->data['total'] : ((($page - 1) * $this->data['limit']) + $this->data['limit']), $this->data['total'], ceil($this->data['total'] / $this->data['limit']));
		$this->data['store_id']   = $this->request->get['store_id'];

		foreach ($this->data['sources'] as $key => $src) {
			$this->data['sources'][$key]['order_data'] = $this->model_sale_order->getOrder($src['order_id']);
		}

		$this->response->setOutput($this->load->view($this->module_path.'/view_log', $this->data));
	}

	public function deleteLogEntry(){
		$data = $this->request->post['selected_log_entries'];
		$store_id = $this->request->post['store_id'];
		$this->moduleModel->deleteLogEntry($data, $store_id);
	}
}