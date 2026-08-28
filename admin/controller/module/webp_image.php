<?php
class ControllerModuleWebpImage extends Controller {
    const MODULE = 'webp_image';
    const PREFIX = 'webp_image';
    const MOD_FILE = 'webp_image_converter';
    const LINK = 'module/webp_image';
    const OCID = 38648;

    private $error = array();
    private $token;
    private $extension_route;

    public function __construct($registry){
        parent::__construct($registry);

        if(VERSION >= '3.0.0.0'){
            $this->token = 'user_token='.$this->session->data['user_token'];
            $this->extension_route = 'marketplace/extension';
        } else if (VERSION >= '2.0.0.0') {
            $this->token = 'token='.$this->session->data['token'];
            $this->extension_route = 'extension/extension';
        } else {
            $this->token = 'token='.$this->session->data['token'];
            $this->extension_route = 'extension/module';
        }
    }

    public function index() {
      if (version_compare(VERSION, '2.3', '>=')) {
        $this->load->language('extension/module/webp_image');
      } else {
        $this->load->language('module/webp_image');
      }

        $this->document->setTitle($this->language->get('title'));

        $this->load->model('setting/setting');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $this->model_setting_setting->editSetting('webp_image', $this->request->post);

            if(VERSION >= '3.0.0.0'){
                $new_post = array();
                foreach ($this->request->post as $k => $v) {
                    $new_post['module_'.$k] = $v;
                }
                $this->model_setting_setting->editSetting('module_webp_image', $new_post);
            }

            $this->session->data['success'] = $this->language->get('text_success');

            if(VERSION >= '2.0.0.0'){
            $this->response->redirect($this->url->link($this->extension_route, $this->token . '&type=module', true));
            } else {
                $this->redirect($this->url->link($this->extension_route, $this->token . '&type=module', true));
            }
            
        }

        if (!function_exists('imagewebp')) {
          $this->error['warning'] = 'imagewebp() function is not available, try to contact your host to ask if they can enable webp support for GD, or try to change php version to higher version';
        }
        
        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }
        
        $data['heading_title'] = $this->language->get('title');
        $data['button_save'] = $this->language->get('button_save');
        $data['button_cancel'] = $this->language->get('button_cancel');
        $data['heading_title'] = $this->language->get('title');
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');
        $data['text_edit'] = $this->language->get('text_edit');
        $data['entry_status'] = $this->language->get('entry_status');
        $data['entry_header'] = $this->language->get('entry_header');
        $data['entry_content_top'] = $this->language->get('entry_content_top');
        $data['entry_content_bottom'] = $this->language->get('entry_content_bottom');
        $data['entry_footer'] = $this->language->get('entry_footer');
        $data['entry_quality'] = $this->language->get('entry_quality');
        $data['text_clear_webp_cache'] = $this->language->get('text_clear_webp_cache');

        $data['token'] = $this->token;


        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', $this->token, true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_extension'),
            'href' => $this->url->link($this->extension_route, $this->token . '&type=module', true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('title'),
            'href' => $this->url->link('module/webp_image', $this->token, true)
        );

        $data['action'] = $this->url->link('module/webp_image', $this->token, true);

        $data['cancel'] = $this->url->link($this->extension_route, $this->token . '&type=module', true);

        if (isset($this->request->post['webp_image_status'])) {
          $data['webp_image_status'] = $this->request->post['webp_image_status'];
        } else {
          $data['webp_image_status'] = $this->config->get('webp_image_status');
        }

        if (isset($this->request->post['webp_image_quality'])) {
          $data['webp_image_quality'] = $this->request->post['webp_image_quality'];
        } else {
          $data['webp_image_quality'] = $this->config->get('webp_image_quality');
        }
        
        if (isset($this->request->post['webp_image_header'])) {
          $data['webp_image_header'] = $this->request->post['webp_image_header'];
        } else {
          $data['webp_image_header'] = $this->config->get('webp_image_header');
        }
        
        if (isset($this->request->post['webp_image_content_top'])) {
          $data['webp_image_content_top'] = $this->request->post['webp_image_content_top'];
        } else {
          $data['webp_image_content_top'] = $this->config->get('webp_image_content_top');
        }
        
        if (isset($this->request->post['webp_image_content_bottom'])) {
          $data['webp_image_content_bottom'] = $this->request->post['webp_image_content_bottom'];
        } else {
          $data['webp_image_content_bottom'] = $this->config->get('webp_image_content_bottom');
        }
        
        if (isset($this->request->post['webp_image_footer'])) {
          $data['webp_image_footer'] = $this->request->post['webp_image_footer'];
        } else {
          $data['webp_image_footer'] = $this->config->get('webp_image_footer');
        }
        
        if(VERSION >= '2.0.0.0'){
        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        if(VERSION < '2.2.0.0'){
            $additional_extension = '.tpl';
        } else {
            $additional_extension = '';
        }

        if (VERSION >= '3.0.2.0') {
            $this->config->set('template_engine', 'template');
        }
        $view = $this->load->view('module/webp_image'.$additional_extension, $data);
        $this->response->setOutput($view);
      } else {
          foreach ($data as $k => $v) {
              $this->data[$k] = $v;
          }
          $this->template = 'module/webp_image15.tpl';
          $this->children = array(
              'common/header',
              'common/footer'
          );
          $this->response->setOutput($this->render());
      }
    }

    private function removeWebp($dir, &$count){
        $elements = glob($dir.'/*');
        foreach ($elements as $element) {
            if (is_dir($element)) {
                $this->removeWebp($element, $count);
            }
            if (is_file($element)) {
                if (substr($element, -4) == 'webp') {
                    unlink($element);
                    $count++;
                }
            }
        }
    }

    public function clearWebpCache(){
        $json = [];
        $count = 0;
        $this->removeWebp(DIR_IMAGE.'cache', $count);
        $json['count'] = $count;
        $json['success'] = 'Success! Webp cache was cleared!';
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    protected function validate() {
        if (!$this->user->hasPermission('modify', 'module/webp_image')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }
}