<?php
class ControllerExtensionMazaCommonColumnLeft extends Controller {
        public function module($data) {
                if (isset($this->request->get['token']) && isset($this->session->data['token']) && ($this->request->get['token'] == $this->session->data['token'])) {
                        $this->load->language('extension/maza/common/column_left');
                        
                        $this->load->model('tool/image');
                        $this->load->model('extension/module');
                        
                        $code = $data['code'];
                        
			// Menu
                        $data['modules'][] = array(
                                'id'       => 'mz-menu-add',
                                'icon'     => 'fa-plus-circle',
                                'name'     => $this->language->get('text_add_module'),
                                'active'   => (!isset($this->request->get['module_id']) && $this->request->get['route'] == 'extension/module/' . $code)?TRUE: FALSE,
                                'href'     => $this->url->link('extension/module/' . $code, 'token=' . $this->session->data['token'], true),
                                'children' => array()
                        );
                        
                        // module list
                        $modules = $this->model_extension_module->getModulesByCode($code);
                        foreach ($modules as $module) {
                            $data['modules'][] = array(
                                    'id'       => 'mz-menu-edit-' . $module['module_id'],
                                    'icon'     => 'fa-edit',
                                    'name'     => $module['name'],
                                    'active'   => (isset($this->request->get['module_id']) && $this->request->get['module_id'] === $module['module_id'])?TRUE: FALSE,
                                    'href'     => $this->url->link('extension/module/' . $code, 'token=' . $this->session->data['token'] . '&module_id=' . $module['module_id'], true),
                                    'children' => array()
                            );
                        }
                        
                        $data = array_merge($data, $this->language->all());
                        
                        return $this->load->view('extension/maza/common/column_left', $data);
                }
                
                
        }
}