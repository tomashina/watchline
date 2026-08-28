<?php
class ControllerExtensionModuleCpnshipping extends Controller {
	private $error = array(); 

	public function index() {   
		$this->language->load('extension/module/cpnshipping');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('cpnshipping', $this->request->post);		

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/extension', 'token=' . $this->session->data['token'], 'SSL'));
			
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['entry_api_user'] = $this->language->get('entry_api_user');
		$data['entry_api_key'] = $this->language->get('entry_api_key');

		$data['text_info'] = $this->language->get('text_info');

		$data['entry_sender_name'] = $this->language->get('entry_sender_name');
		$data['entry_sender_address'] = $this->language->get('entry_sender_address');
		$data['entry_sender_zipcode'] = $this->language->get('entry_sender_zipcode');
		$data['entry_sender_city'] = $this->language->get('entry_sender_city');
		$data['entry_sender_country'] = $this->language->get('entry_sender_country');

		
		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
		$data['action'] = $this->url->link('extension/module/cpnshipping', 'token=' . $this->session->data['token'], 'SSL');

		$data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');

 		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['image'])) {
			$data['error_image'] = $this->error['image'];
		} else {
			$data['error_image'] = array();
		}

  		$data['breadcrumbs'] = array();

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_module'),
			'href'      => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('extension/module/cpnshipping', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);

		$data['action'] = $this->url->link('extension/module/cpnshipping', 'token=' . $this->session->data['token'], 'SSL');

		$data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');

		$data['modules'] = array();

		if (isset($this->request->post['cpnshipping'])) {
			$data['cpnshipping'] = $this->request->post['cpnshipping'];
		} elseif ($this->config->get('cpnshipping')) { 
			$data['cpnshipping'] = $this->config->get('cpnshipping');
		}


		$this->load->model('design/layout');

		$data['layouts'] = $this->model_design_layout->getLayouts();

		$this->load->model('design/banner');

		$data['banners'] = $this->model_design_banner->getBanners();

		$this->template = 'extension/module/cpnshipping.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		//$this->response->setOutput($this->render());
		$this->response->setOutput($this->load->view('extension/module/cpnshipping.tpl', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/cpnshipping')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}	

		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}

	public function import(){
		$json = array();
	
			$spnshipping = $this->config->get('cpnshipping');
			$params = array(
			        'api_user' => $spnshipping['api_user'],
			        'api_key'  => $spnshipping['api_key']
			);

			
            	$token = $output['token'];

            	// load data from order_id
            	$order_id = $this->request->get['order_id'];

            		$pcount 	= $this->request->get['pcount'];
					$brojracuna = $this->request->get['brojracuna'];
					$iznos 		= $this->request->get['iznos'];
            		$this->load->model('sale/order');
            		$order_info = $this->model_sale_order->getOrder($order_id);


           

		if($order_info['payment_code'] =='cod'){
			if ($iznos=='') {
				$mani = $order_info['total'];
				$mani =  number_format((float)$mani, 2, '.', '');
			} else {
				$mani = $iznos;
			}
		} else {
			$mani=0;
		}


			try 
				{
					//Test ClientNumber:
					$clientNumber = '380007182'; //!!!NOT FOR CUSTOMER TESTING, USE YOUR OWN, USE YOUR OWN!!!
					//Test username:
					$username = "watchline.am@gmail.com"; //!!!NOT FOR CUSTOMER TESTING, USE YOUR OWN, USE YOUR OWN!!!
					//Test password:
					$pwd = "Mirela0901ado"; //!!!NOT FOR CUSTOMER TESTING, USE YOUR OWN, USE YOUR OWN!!!
					$password = hash('sha512', $pwd, true);

					$parcels = []; 
					$parcel = new StdClass();
					$parcel->ClientNumber = $clientNumber;
					$parcel->ClientReference = "WATCH LINE AM";
					$parcel->CODAmount = $mani;
					$parcel->CODReference = $brojracuna;
					$parcel->Content = "WATCH LINE AM - Satovi i naočale";
					$parcel->Count = 1;
					$deliveryAddress = new StdClass();
					$deliveryAddress->ContactEmail = $order_info['email'];
					$deliveryAddress->ContactName = $order_info['shipping_firstname'] . ' ' . $order_info['shipping_lastname'];
					$deliveryAddress->ContactPhone = $order_info['telephone'];
					$deliveryAddress->Name = $order_info['shipping_firstname'] . ' ' . $order_info['shipping_lastname'];
					$deliveryAddress->Street = $order_info['shipping_address_1'];
					$deliveryAddress->HouseNumber = $order_info['shipping_address_2'];
					$deliveryAddress->City = $order_info['shipping_city'];
					$deliveryAddress->ZipCode = $order_info['shipping_postcode'];
					$deliveryAddress->CountryIsoCode = "HR";
					$deliveryAddress->HouseNumberInfo = "/b";
					$parcel->DeliveryAddress = $deliveryAddress;
					$pickupAddress = new StdClass();
					$pickupAddress->ContactName = "WATCH LINE AM d.o.o.";
					$pickupAddress->ContactPhone = "+385994517277 ";
					$pickupAddress->ContactEmail = "info@watchline.hr";
					$pickupAddress->Name = "Adnan Sefić";
					$pickupAddress->Street = "Trg A. Starčevića ";
					$pickupAddress->HouseNumber = "7";
					$pickupAddress->City = "Zagreb";
					$pickupAddress->ZipCode = "10000";
					$pickupAddress->CountryIsoCode = "HR";
					$pickupAddress->HouseNumberInfo = "/a";
					$parcel->PickupAddress = $pickupAddress;
					$parcel->PickupDate = date('Y-m-d');
					$service1 = new StdClass();
					$service1->Code = "FDS";
					$parameter1 = new StdClass();
					$parameter1->StringValue = $order_info['email'];
					$service1->FDSParameter = $parameter1;
					$service2 = new StdClass();
					$service2->Code = "DPV";
					$parameter2 = new StdClass();
					$parameter2->StringValue = "WATCH LINE";
					$parameter2->DecimalValue = "500";
					$service2->DPVParameter = $parameter2;



					$services = [];
					$services[] = $service1;
					$services[] = $service2;
					$parcel->ServiceList = $services;
					
					$parcels[] = $parcel;

					//The service URL:
					$wsdl = "https://api.mygls.hr/ParcelService.svc?singleWsdl";

					$soapOptions = array('soap_version'   => SOAP_1_1
									   , 'stream_context' => stream_context_create(array('ssl' => array('cafile' => 'cacert.pem'))));

				

					$this->PrepareLabels($username,$password,$parcels,$wsdl,$soapOptions,$order_id);
				} 
				catch (Exception $e) 
				{
				    echo $e->getMessage();
				}













		
	}

	public function PrepareLabels($username,$password,$parcels,$wsdl,$soapOptions,$order_id)
		{
			//Test request:
			$prepareLabelsRequest = array('Username' => $username,
			                              'Password' => $password,
										  'ParcelList' => $parcels);
										  
			$request = array ("prepareLabelsRequest" => $prepareLabelsRequest);
										
			//Service client creation:
			$client = new SoapClient($wsdl,$soapOptions);
			
			//Service calling:
			$response = $client->PrepareLabels($request);


			print_r($response);
			
			$parcelIdList = [];
			if($response != null && count((array)$response->PrepareLabelsResult->PrepareLabelsError) == 0 && count((array)$response->PrepareLabelsResult->ParcelInfoList) > 0)
			{
				$parcelIdList[] = $response->PrepareLabelsResult->ParcelInfoList->ParcelInfo->ParcelId;

				 //agmedia
				$this->db->query("UPDATE `" . DB_PREFIX . "order` SET printed = 1 WHERE order_id = '" . (int)$order_id . "'");

				//$this->db->query("UPDATE `" . DB_PREFIX . "order` SET printed = 1 WHERE order_id = '9'");
			}
			
			//Test request:
			$getPrintedLabelsRequest = array('Username' => $username,
			                                 'Password' => $password,
										     'ParcelIdList' => $parcelIdList,
											 'PrintPosition' => 1,
											 'ShowPrintDialog' => 0);
											 
			return $getPrintedLabelsRequest;
		}


}
?>