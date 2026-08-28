<?php

/**
 * @package		MazaTheme
 * @author		Jay padaliya
 * @copyright           Copyright (c) 2017, TemplateMaza
 * @license		One domain license
 * @link		http://www.templatemaza.com
 */
class ModelExtensionMazaCron extends model {
        /**
         * Call admin url
         * @param String $route
         * @param String $param
         * @return String
         */
        public function callToAdmin($route, $param = '') {
                if($_SERVER['HTTPS']){
                    $request = HTTPS_SERVER . 'admin/index.php?route=' . $route . '&_cron&token=' . $this->session->data['token'];
                }  else {
                    $request = HTTP_SERVER . 'admin/index.php?route=' . $route . '&_cron&token=' . $this->session->data['token'];
                }

                if($param){
                    $request .= '&' . $param;
                }

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $request);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_TIMEOUT, 60);
                curl_setopt($ch, CURLOPT_COOKIE, 'default=' . $this->session->getId() . ';' . session_name() . '=' . session_id());
                $responce = curl_exec($ch);
                if($responce === false && curl_errno($ch) != 28){
                    echo curl_error($ch);
                }
                
                curl_close($ch);
                
                return $responce;
        }
        
        public function login(){
                if (!isset($this->request->get['username']) || !isset($this->request->get['password']) || !$this->user->login($this->request->get['username'], html_entity_decode($this->request->get['password'], ENT_QUOTES, 'UTF-8'))) {
                    return false;
                } else {
                    $this->session->data['token'] = token(32);
                }

                session_write_close();

                return true;
        }
}
