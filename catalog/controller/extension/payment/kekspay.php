<?php
require_once DIR_APPLICATION . 'controller/extension/payment/kekspay/autoload.php';

use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;

class ControllerExtensionPaymentKeksPay extends Controller
{
    public function index()
    {
        $this->load->model('checkout/order');
        
        $this->load->language('extension/payment/kekspay');
        
        $order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
        
        if ( ! $this->config->get('kekspay_test')) {
          //  $data['action'] = 'https://ewa.erstebank.hr/tps/';
            $data['action'] = 'https://kekspay.hr/pay/';
        } else {
            //$data['action'] = 'https://dttlinuxdev.erste.hr/tps';
            $data['action'] = 'https://kekspay.hr/sokolpay/';
        }
        
        if ($this->request->server['HTTPS']) {
            $data['logo'] = HTTPS_SERVER . 'image/payment/keks-logo.svg';
        } else {
            $data['logo'] = HTTP_SERVER . 'image/payment/keks-logo.svg';
        }
        
        if ($this->request->server['HTTPS']) {
            $success_url = HTTPS_SERVER . 'index.php?route=extension/payment/kekspay/success';
            $fail_url    = HTTPS_SERVER . 'index.php?route=extension/payment/kekspay/fail';
        } else {
            $success_url = HTTP_SERVER . 'index.php?route=extension/payment/kekspay/success';
            $fail_url    = HTTP_SERVER . 'index.php?route=extension/payment/kekspay/fail';
        }
        
        $store_name = $this->config->get('kekspay_shop_title') != '' ? $this->config->get('kekspay_shop_title') : 'Trgovina';
        
        $data['qr_code']     = 1;
        $data['cid']         = $this->config->get('kekspay_cid');
        $data['tid']         = $this->config->get('kekspay_tid');
        $data['bill_id']     = $this->config->get('kekspay_cid') . time() . $order_info['order_id'];
        $data['amount']      = number_format($order_info['total'], 2, '.', '');
        $data['store']       = rawurlencode($store_name);
        $data['success_url'] = rawurlencode($success_url);
        $data['fail_url']    = rawurlencode($fail_url);
        
        $data['button_confirm'] = $this->language->get('kekspay_btn_confirm');
        $data['order_id']       = $order_info['order_id'];
        
       $options = new QROptions([
            'version'          => 6,
            'quietzoneSize'    => 4,
            'eccLevel'         => QRCode::ECC_L,
            'imageTransparent' => false,
        ]);
        
        $qrdata = [
            "qr_type" => 1,
            "cid"     => $this->config->get('kekspay_cid'),
            "tid"     => $this->config->get('kekspay_tid'),
            "bill_id" => $this->config->get('kekspay_cid') . time() . $order_info['order_id'],
            "amount"  => number_format($order_info['total'], 2, '.', ''),
            "store"   => rawurlencode($store_name),
        ];
        
        $qrcode = new QRCode($options);
        
        $data['qrcode'] = $qrcode->render(json_encode($qrdata));
        
        // return $this->load->view('extension/payment/kekspay', $data);

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/extension/payment/kekspay.tpl')) {
            return $this->load->view($this->config->get('config_template') . '/template/extension/payment/kekspay.tpl', $data);
        } else {
            return $this->load->view('extension/payment/kekspay.tpl', $data);
        } 
        
        $this->render();
      
        
       
    }
    
    
    public function callback()
    {
         if ($this->request->get['user'] != 'watchline' && $this->request->get['pass'] != 'L8CkA->e7ASDfYAn.!4') {
            return $this->response(1, 'Failed');
        }

        $json_response = json_decode(file_get_contents('php://input'), true);
        $this->log->write($json_response);

        if ($this->responseFault($json_response)) {
            return $this->response(1, 'Failed');
        }
        
        $order_id = substr($json_response['bill_id'], 16);
        
        $this->db->query("UPDATE " . DB_PREFIX . "order SET keks_data = '" . serialize($json_response) . "' WHERE order_id = '" . (int)$order_id . "'");

        $this->load->model('checkout/order');

        if ( ! $json_response['status']) {
            $this->model_checkout_order->addOrderHistory($order_id, $this->config->get('kekspay_order_status_id'), '', true);
            $this->log->write('Accepted response from KEKS.....');
            return $this->response(0, 'Accepted');
            
        } else {
            $this->model_checkout_order->addOrderHistory($order_id, $this->config->get('config_order_status_id'), '', true);
            $this->log->write('Failed response from KEKS.......');
            return $this->response(1, 'Failed');
        }
        
    }
    
    
    public function check()
    {
        $json           = [];
        $json['status'] = 0;
    
        $this->load->model('checkout/order');
        $order_info = $this->model_checkout_order->getOrder($this->request->post['order_id']);
        
        if ($order_info['order_status_id']) {
            $json['redirect'] = $this->url->link('checkout/checkout');
            $json['status']   = 1;
            
            if ($order_info['order_status_id'] == $this->config->get('kekspay_order_status_id')) {
                $json['redirect'] = $this->url->link('checkout/success');
            }
        }
        
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
    
    
    public function success()
    {
        $json_response = json_decode(file_get_contents('php://input'), true);
    }
    
    
    public function fail()
    {
        $json_response = json_decode(file_get_contents('php://input'), true);
    }


    private function responseFault($response)
    {
        if ( ! isset($response['bill_id']) && ! isset($response['tid']) && ! isset($response['status']) && $response['tid'] != $this->config->get('kekspay_tid')) {
            return true;
        }

        return false;
    }


    private function response($status, $message)
    {
        $this->response->addHeader('Content-Type: application/json');
        return $this->response->setOutput(json_encode([
            'status'  => $status,
            'message' => $message
        ]));
    }
}

?>