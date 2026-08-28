<?php

class ControllerFeedRestApi extends Controller {


	private $debugIt = false;
	public function products() {

		$this->checkPlugin();

		$this->load->model('catalog/product');
	
		//$json = array('success' => true, 'products' => array());

		/*check category id parameter*/
		if (isset($this->request->get['category'])) {
			$category_id = $this->request->get['category'];
		} else {
			$category_id = 0;
		}
		$productsjson = array();

		$products = $this->model_catalog_product->getProducts(array(
			'filter_category_id'        => $category_id
		));

		foreach ($products as $product) {

			if ($product['image']) {
				$image = $product['image'];
			} else {
				$image = false;
			}

			if ((float)$product['special']) {
				$special = $this->currency->format($this->tax->calculate($product['special'], $product['tax_class_id'], $this->config->get('config_tax')));
			} else {
				$special = false;
			}

			$productsjson[] = array(
					'id'			=> $product['product_id'],
					'name'			=> $product['name'],
					'description'	=> $product['description'],
					'stock_status'  => $product['stock_status'],
					'manufacturer'=> $product['manufacturer'],
					'quantity'     => $product['quantity'],
					 'reviews'     => $product['reviews'],
					'price'			=> $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax'))),
					'href'			=> $this->url->link('product/product', 'product_id=' . $product['product_id']),
					'thumb'			=> $image,
					'special'		=> $special,
					'rating'		=> $product['rating']
			);
		}
		if(count($productsjson)){
			$json['success'] 	= true;
			$json['products'] 	= $productsjson;
		}else {
			$json['success'] 	= false;
		}

		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';
		} else {
			$this->response->setOutput(json_encode($json));
		}
	}
	public function getProduct() {
		$this->checkPlugin();

		$this->load->model('catalog/product');
	
		//$json = array('success' => true, 'products' => array());

		/*check category id parameter*/
		if (isset($this->request->get['p_id'])) {
			$p_id = $this->request->get['p_id'];
		} else {
			$p_id = 0;
		}
		$pro_data =array();
		if($p_id != 0){
		$product = $this->model_catalog_product->getProduct($p_id);
			if ($product['image']) {
				$image = $product['image'];
			} else {
				$image = 'placeholder.png';
			}

			if ((float)$product['special']) {
				$special = $this->currency->format($this->tax->calculate($product['special'], $product['tax_class_id'], $this->config->get('config_tax')));
			} else {
				$special = false;
			}
			if(!empty($product)){
				$pro_data = array(
					'id'			=> $product['product_id'],
					'name'			=> $product['name'],
					'description'	=> $product['description'],
					'meta_title'	=> $product['meta_title'],
					'meta_description'	=> $product['meta_description'],
					'meta_keyword'	=> $product['meta_keyword'],
					'tag'	=> $product['tag'],
					'model'	=> $product['model'],
					'sku'	=> $product['sku'],
					'upc'	=> $product['upc'],
					'ean'	=> $product['ean'],
					'jan'	=> $product['jan'],
					'isbn'	=> $product['isbn'],
					'mpn'	=> $product['mpn'],
					'location'	=> $product['location'],
					'quantity'	=> $product['quantity'],
					'stock_status'	=> $product['stock_status'],
					'manufacturer_id'	=> $product['manufacturer_id'],
					'manufacturer'	=> $product['manufacturer'],
					'reward'	=> $product['reward'],
					'points'	=> $product['points'],
					'date_available'	=> $product['date_available'],
					'tax_class_id'	=> $product['tax_class_id'],
					'weight_class_id'	=> $product['weight_class_id'],
					'length'	=> $product['length'],
					'width'	=> $product['width'],
					'height'	=> $product['height'],
					'length_class_id'	=> $product['length_class_id'],
					'subtract'	=> $product['subtract'],
					'reviews'	=> $product['reviews'],
					'minimum'	=> $product['minimum'],
					'sort_order'	=> $product['sort_order'],
					'status'	=> $product['status'],
					'date_added'	=> $product['date_added'],
					'date_modified'	=> $product['date_modified'],
					'viewed'	=> $product['viewed'],
					
					'price'			=> $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax'))),
					'href'			=> $this->url->link('product/product', 'product_id=' . $product['product_id']),
					'thumb'			=> $image,
					'special'		=> $special,
					'rating'		=> $product['rating']
			);
			}
		
		}
		if(count($pro_data)){
			$json['success'] 	= true;
			$json['product'] 	= $pro_data;
		}else {
			$json['success'] 	= false;
		}
		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';
		} else {
			$this->response->setOutput(json_encode($json));
		}
	}	//seo
	public function getrelaProduct() {
		$this->checkPlugin();

		$this->load->model('catalog/product');
	
		//$json = array('success' => true, 'products' => array());

		/*check category id parameter*/
		if (isset($this->request->get['p_id'])) {
			$p_id = $this->request->get['p_id'];
		} else {
			$p_id = 0;
		}
		$pro_data =array();
		//echo $p_id;die();
		if($p_id != 0){
			$results = $this->model_catalog_product->getProductRelated($p_id);
			$this->load->model('tool/image');
			foreach ($results as $result) {
				if ($result['image']) {
					$image = $this->model_tool_image->resize($result['image'], $this->config->get('config_image_related_width'), $this->config->get('config_image_related_height'));
				} else {
					$image = $this->model_tool_image->resize('placeholder.png', $this->config->get('config_image_related_width'), $this->config->get('config_image_related_height'));
				}

				if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
					$price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')));
				} else {
					$price = false;
				}

				if ((float)$result['special']) {
					$special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')));
				} else {
					$special = false;
				}

				if ($this->config->get('config_tax')) {
					$tax = $this->currency->format((float)$result['special'] ? $result['special'] : $result['price']);
				} else {
					$tax = false;
				}

				if ($this->config->get('config_review_status')) {
					$rating = (int)$result['rating'];
				} else {
					$rating = false;
				}

				$pro_data[] = array(
					'product_id'  => $result['product_id'],
					'thumb'       => $image,
					'name'        => $result['name'],
					'description' => utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get('config_product_description_length')) . '..',
					'price'       => $price,
					'special'     => $special,
					'tax'         => $tax,
					'minimum'     => $result['minimum'] > 0 ? $result['minimum'] : 1,
					'rating'      => $rating,
					'href'        => $this->url->link('product/product', 'product_id=' . $result['product_id'])
				);
			}
		}
		if(count($pro_data)){
			$json['success'] 	= true;
			$json['relatedproduct'] 	= $pro_data;
		}else {
			$json['success'] 	= false;
		}
		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';
		} else {
			$this->response->setOutput(json_encode($json));
		}
	}
	
	
	public function getOptionbyid() {
		$this->checkPlugin();

		$this->load->model('catalog/product');
	
		/*check category id parameter*/
		if (isset($this->request->get['p_id'])) {
			$p_id = $this->request->get['p_id'];
		} else {
			$p_id = 0;
		}
		$optionsdata =array();
		if($p_id != 0){
		$product_info = $this->model_catalog_product->getProduct($p_id);
		foreach ($this->model_catalog_product->getProductOptions($this->request->get['p_id']) as $option) {
				$product_option_value_data = array();
				
				foreach ($option['product_option_value'] as $option_value) {
					if (!$option_value['subtract'] || ($option_value['quantity'] > 0)) {
						if ((($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) && (float)$option_value['price']) {
						$price = $this->currency->format($this->tax->calculate($option_value['price'], $product_info['tax_class_id'], $this->config->get('config_tax') ? 'P' : false));
						} else {
							$price = false;
						}

						$product_option_value_data[] = array(
							'product_option_value_id' => $option_value['product_option_value_id'],
							'option_value_id'         => $option_value['option_value_id'],
							'name'                    => $option_value['name'],
							'image'                   => $option_value['image'],
							'price'                   => $price,
							'price_prefix'            => $option_value['price_prefix'],
							'opt_image'           	  => $option_value['opt_image']
						);
					}
				}
				
				$optionsdata[] = array(
					'product_option_id'    => $option['product_option_id'],
					'product_option_value' => $product_option_value_data,
					'option_id'            => $option['option_id'],
					'name'                 => $option['name'],
					'type'                 => $option['type'],
					'value'                => $option['value'],
					'required'             => $option['required']
				);
			}
		}
			//$json['products_option'] = $optionsdata;
		if(count($optionsdata)){
			$json['success'] 	= true;
			$json['products_option'] 	= $optionsdata;
		}else {
			$json['success'] 	= false;
		}
		

		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';
		} else {
			$this->response->setOutput(json_encode($json));
		}
	}	//seo
	public function getProdadditional() {
		$this->checkPlugin();

		$this->load->model('catalog/product');
	
		//$json = array('success' => true, 'products_prodadditional' => array());

		/*check category id parameter*/
		if (isset($this->request->get['p_id'])) {
			$p_id = $this->request->get['p_id'];
		} else {
			$p_id = 0;
		}
		$add_prod_data = array();
		
		if($p_id != 0){ 
		$product_info = $this->model_catalog_product->getProduct($p_id);
		/*start discount*/
		$discounts = $this->model_catalog_product->getProductDiscounts($p_id);
		$discountsdata = array();
		foreach ($discounts as $discount) {
				$discountsdata[] = array(
					'quantity' => $discount['quantity'],
					'price'    => $this->currency->format($this->tax->calculate($discount['price'], $product_info['tax_class_id'], $this->config->get('config_tax')))
				);
			}
		/*end discount*/
			
		/*start review*/
		$this->load->model('catalog/review');
		$results = $this->model_catalog_review->getReviewsByProductId($p_id);
		$reviewsdata = array();
		foreach ($results as $result) {
			$reviewsdata[] = array(
				'author'     => $result['author'],
				'text'       => nl2br($result['text']),
				'rating'     => (int)$result['rating'],
				'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added']))
			);
		}
		/*end review*/	
		
		/*start additional image*/
		$add_imgs = $this->model_catalog_product->getProductImages($p_id);
		/*end additional image*/
		$add_prod_data = array('add_imgs'=>$add_imgs,'reviewsdata'=>$reviewsdata,'discountsdata'=>$discountsdata);
		}
		if(count($add_prod_data)){
			$json['success'] 	= true;
			$json['products_prodadditional'] 	= $add_prod_data;
		}else {
			$json['success'] 	= false;
		}
		
		//$json['products_prodadditional'] = $add_prod_data;
		

		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';
		} else {
			$this->response->setOutput(json_encode($json));
		}
	}	//seo
	public function getorderbycus() {

		$this->checkPlugin();
	
		$orderData['orders'] = array();

		$this->load->model('account/order');

		/*check offset parameter*/
		if (isset($this->request->get['offset']) && $this->request->get['offset'] != "" && ctype_digit($this->request->get['offset'])) {
			$offset = $this->request->get['offset'];
		} else {
			$offset 	= 0;
		}

		/*check limit parameter*/
		if (isset($this->request->get['limit']) && $this->request->get['limit'] != "" && ctype_digit($this->request->get['limit'])) {
			$limit = $this->request->get['limit'];
		} else {
			$limit 	= 10000;
		}
		if (isset($this->request->post['customer_id']) && $this->request->post['customer_id'] != "") {
			$customer_id = $this->request->post['customer_id'];
		} else {
			$customer_id 	= 0;
		}
		
		/*get all orders of user*/
		$results = array();
		if($customer_id != 0){
			$results = $this->getOrdersapi($offset, $limit,$customer_id);
		}
		
		$orders = array();

		if(count($results)){
			foreach ($results as $result) {

				$product_total = $this->model_account_order->getTotalOrderProductsByOrderId($result['order_id']);
				$voucher_total = $this->model_account_order->getTotalOrderVouchersByOrderId($result['order_id']);

				$orders[] = array(
						'order_id'		=> $result['order_id'],
						'name'			=> $result['firstname'] . ' ' . $result['lastname'],
						'status'		=> $result['status'],
						'date_added'	=> $result['date_added'],
						'products'		=> ($product_total + $voucher_total),
						'total'			=> $result['total'],
						'currency_code'	=> $result['currency_code'],
						'currency_value'=> $result['currency_value'],
				);
			}

			$json['success'] 	= true;
			$json['orders'] 	= $orders;
		}else {
			$json['success'] 	= false;
		}
		
		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';

		} else {
			$this->response->setOutput(json_encode($json));
		}
	}	//seo
	public function getorderbyid() {

		$this->checkPlugin();
	
		$orderbyid = array();

		$this->load->model('account/order');
		$this->load->model('tool/upload');

		if (isset($this->request->post['order_id']) && $this->request->post['order_id'] != "") {
			$order_id = $this->request->post['order_id'];
		} else {
			$order_id 	= 0;
		}
		$orderfull_data = array();
		if($order_id != 0){
		$order_info = array();
		$order_info = $this->getOrderidapi($order_id);
		$products = array();
		$products = $this->model_account_order->getOrderProducts($order_id);
		foreach ($products as $product) {
				$option_data = array();

				$options = $this->model_account_order->getOrderOptions($order_id, $product['order_product_id']);

				foreach ($options as $option) {
					if ($option['type'] != 'file') {
						$value = $option['value'];
					} else {
						$upload_info = $this->model_tool_upload->getUploadByCode($option['value']);

						if ($upload_info) {
							$value = $upload_info['name'];
						} else {
							$value = '';
						}
					}

					$option_data[] = array(
						'name'  => $option['name'],
						'value' => (utf8_strlen($value) > 20 ? utf8_substr($value, 0, 20) . '..' : $value),
						'order_option_id'=>$option['order_option_id'],
						'order_product_id'=>$option['order_product_id'],
						'product_option_id'=>$option['product_option_id'],
						'product_option_value_id'=>$option['product_option_value_id'],
					);
				}
				$this->load->model('catalog/product');

				$product_info = $this->model_catalog_product->getProduct($product['product_id']);

				if ($product_info) {
					$reorder = $this->url->link('account/order/reorder', 'order_id=' . $order_id . '&order_product_id=' . $product['order_product_id'], 'SSL');
				} else {
					$reorder = '';
				}

				$productsdata[] = array(
					'product_id'     => $product['product_id'],
					'order_product_id'     => $product['order_product_id'],
					'order_id'     => $order_id,					
					'tax'     => $product['tax'],
					'name'     => $product['name'],
					'model'    => $product['model'],
					'option'   => $option_data,
					'quantity' => $product['quantity'],
					'price'    => $this->currency->format($product['price'] + ($this->config->get('config_tax') ? $product['tax'] : 0), $order_info['currency_code'], $order_info['currency_value']),
					'total'    => $this->currency->format($product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $order_info['currency_code'], $order_info['currency_value'])
				);
			}
		$vouchersdata = array();
		$vouchers = $this->model_account_order->getOrderVouchers($order_id);
		foreach ($vouchers as $voucher) {
				$vouchersdata[] = array(
					'description' => $voucher['description'],
					'amount'      => $this->currency->format($voucher['amount'], $order_info['currency_code'], $order_info['currency_value'])
				);
			}
		$totalsdata = array();
		$totals = $this->model_account_order->getOrderTotals($order_id);
		foreach ($totals as $total) {
			$totalsdata[] = array(
				'title' => $total['title'],
				'text'  => $this->currency->format($total['value'], $order_info['currency_code'], $order_info['currency_value']),
			);
		}
		$historiesdata = array();
		$his_data = $this->model_account_order->getOrderHistories($this->request->post['order_id']);
		foreach ($his_data as $result) {
			$historiesdata[] = array(
				'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'status'     => $result['status'],
				'comment'    => $result['notify'] ? nl2br($result['comment']) : ''
			);
		}
		$orderfull_data = array('order_info'=>$order_info,'products'=>$productsdata,'vouchersdata'=>$vouchersdata,'totals'=>$totals,'historiesdata'=>$historiesdata);
		}
		if(count($orderfull_data)){
			$json['success'] 	= true;
			$json['orders'] 	= $orderfull_data;
		}else {
			$json['success'] 	= false;
		}
		
		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';

		} else {
			$this->response->setOutput(json_encode($json));
		}
	}	//seo
	public function getorderproduct() {

		$this->checkPlugin();
	
		$orderbyid = array();

		$this->load->model('account/order');
		$this->load->model('tool/upload');

		if (isset($this->request->post['order_id']) && $this->request->post['order_id'] != "") {
			$order_id = $this->request->post['order_id'];
		} else {
			$order_id 	= 0;
		}
		
		$order_info = array();
		$products = array();
		if($order_id != 0){
		$order_info = $this->model_account_order->getOrder($order_id);
		$products = $this->model_account_order->getOrderProducts($order_id);
		foreach ($products as $product) {
				$option_data = array();

				$options = $this->model_account_order->getOrderOptions($order_id, $product['order_product_id']);

				foreach ($options as $option) {
					if ($option['type'] != 'file') {
						$value = $option['value'];
					} else {
						$upload_info = $this->model_tool_upload->getUploadByCode($option['value']);

						if ($upload_info) {
							$value = $upload_info['name'];
						} else {
							$value = '';
						}
					}

					$option_data[] = array(
						'name'  => $option['name'],
						'value' => (utf8_strlen($value) > 20 ? utf8_substr($value, 0, 20) . '..' : $value)
					);
				}
				$this->load->model('catalog/product');

				$product_info = $this->model_catalog_product->getProduct($product['product_id']);

				if ($product_info) {
					$reorder = $this->url->link('account/order/reorder', 'order_id=' . $order_id . '&order_product_id=' . $product['order_product_id'], 'SSL');
				} else {
					$reorder = '';
				}

				$productsdata[] = array(
					'name'     => $product['name'],
					'model'    => $product['model'],
					'option'   => $option_data,
					'quantity' => $product['quantity'],
					'price'    => $this->currency->format($product['price'] + ($this->config->get('config_tax') ? $product['tax'] : 0), $order_info['currency_code'], $order_info['currency_value']),
					'total'    => $this->currency->format($product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $order_info['currency_code'], $order_info['currency_value'])
				);
			}
		}
		//$orderfull_data = array('products'=>$products);
		if(count($products)){
			$json['success'] 	= true;
			$json['ordersproducts'] 	= $products;
		}else {
			$json['success'] 	= false;
		}
		
		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';

		} else {
			$this->response->setOutput(json_encode($json));
		}
	}	//seo
	public function success() {

		$this->checkPlugin();
		
		$json['success'] 	= true;
		
		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';

		} else {
			$this->response->setOutput(json_encode($json));
		}
	}	//seo
	public function failure() {

		$this->checkPlugin();
		
		$order_id = $this->request->post['order_id'];
		$order_status_id = $this->request->post['order_status_id'];
		$json['success'] 	= false;
		if(isset($order_id) && $order_id != '' && isset($order_status_id) && $order_status_id != ''){
					$this->db->query("UPDATE `" . DB_PREFIX . "order` SET order_status_id = '" . (int)$order_status_id . "', date_modified = NOW() WHERE order_id = '" . (int)$order_id . "'");
				$json['success'] 	= true;
		}
		
		
		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';

		} else {
			$this->response->setOutput(json_encode($json));
		}
	}	//seo
	
	public function gettotalbyid() {

		$this->checkPlugin();
	
		$orderbyid = array();

		$this->load->model('account/order');
		$this->load->model('tool/upload');

		if (isset($this->request->post['order_id']) && $this->request->post['order_id'] != "") {
			$order_id = $this->request->post['order_id'];
		} else {
			$order_id 	= 0;
		}
		$totalsdata = array();
		if($order_id != 0){
		$order_info = array();
		$order_info = $this->model_account_order->getOrder($order_id);
		$totalsdata = array();
		$totals = $this->model_account_order->getOrderTotals($order_id);
		foreach ($totals as $total) {
			$totalsdata[] = array(
				'title' => $total['title'],
				'text'  => $this->currency->format($total['value'], $order_info['currency_code'], $order_info['currency_value']),
			);
		}
		}
		
		if(count($totalsdata)){
			$json['success'] 	= true;
			$json['totalsdata'] 	= $totalsdata;
		}else {
			$json['success'] 	= false;
		}
		
		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';

		} else {
			$this->response->setOutput(json_encode($json));
		}
	}	//seo
	public function getCategories() {
	
		$this->checkPlugin();
		if (isset($this->request->get['cate_id']) && $this->request->get['cate_id'] != "") {
			$cate_id = $this->request->get['cate_id'];
		}
		$this->load->model('catalog/category');
		if(isset($cate_id) && $cate_id != ''){
			$category_info = $this->model_catalog_category->getCategory($cate_id);
		}else{
			$data['categories'] = array();
			$categories = $this->model_catalog_category->getCategories(0);
			foreach ($categories as $category) {
			$children_data = array();

			/*if ($category['category_id']) {
				$children = $this->model_catalog_category->getCategories($category['category_id']);
				foreach($children as $child) {
					$filter_data = array('filter_category_id' => $child['category_id'], 'filter_sub_category' => true);

					$children_data[] = array(
						'category_id' => $child['category_id'],
						'name' => $child['name'],
						'image'=>$child['image'],
						'description'=>$child['description'],
						'parent_id'=>$child['parent_id'],
						'status'=>$child['status'],
					);
				}
			}*/

			$filter_data = array(
				'filter_category_id'  => $category['category_id'],
				'filter_sub_category' => true
			);

			$category_info[] = array(
				'category_id' => $category['category_id'],
				'name'        => $category['name'],
				'image'=>$category['image'],
				'description'=>$category['description'],
				'parent_id'=>$category['parent_id'],
				'status'=>$category['status']
				//'children'    => $children_data,
				//'href'        => $this->url->link('product/category', 'path=' . $category['category_id'])
			);
		}
		}
		
		
		if(count($category_info)){
			$json['success'] 	= true;
			$json['category_info'] 	= $category_info;
		}else {
			$json['success'] 	= false;
		}
		
		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';

		} else {
			$this->response->setOutput(json_encode($json));
		}
	}	//seo
	public function getchildCategories() {
	
		$this->checkPlugin();
		if (isset($this->request->get['cate_id']) && $this->request->get['cate_id'] != "") {
			$cate_id = $this->request->get['cate_id'];
		}
		$this->load->model('catalog/category');
		$children_data = array();
		$category_info = array();
		if ($cate_id) {
				$children = $this->model_catalog_category->getCategories($cate_id);
				foreach($children as $child) {
					$filter_data = array('filter_category_id' => $child['category_id'], 'filter_sub_category' => true);

					$category_info[] = array(
						'category_id' => $child['category_id'],
						'name' => $child['name'],
						'image'=>$child['image'],
						'description'=>$child['description'],
						'parent_id'=>$child['parent_id'],
						'status'=>$child['status'],
					);
				}
			}
		if(count($category_info)){
			$json['success'] 	= true;
			$json['category_info'] 	= $category_info;
		}else {
			$json['success'] 	= false;
		}
		
		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';

		} else {
			$this->response->setOutput(json_encode($json));
		}
	}	//seo
	public function getbannerbyname() {
		$this->checkPlugin();
		
		$results = array();
		if (isset($this->request->get['bannername']) && $this->request->get['bannername'] != "") {
			$bannername = $this->request->get['bannername'];
			$this->load->model('design/banner');
			$results = $this->getBannerapi($bannername);
		}
		if(count($results)){
			$json['success'] 	= true;
			$json['banners'] 	= $results;
		}else {
			$json['success'] 	= false;
		}
		
		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';

		} else {
			$this->response->setOutput(json_encode($json));
		}
	}	//seo
	public function getmanufacture() {
	
		$this->checkPlugin();
		$this->load->model('catalog/manufacturer');
		$results = $this->model_catalog_manufacturer->getManufacturers();
		if(count($results)){
			$json['success'] 	= true;
			$json['manufacturer'] 	= $results;
		}else {
			$json['success'] 	= false;
		}
		
		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';

		} else {
			$this->response->setOutput(json_encode($json));
		}
	}	//seo
	public function returnlist() {
		$this->checkPlugin();
		$results =array();
		
		if(isset($this->request->post['customer_id']) && $this->request->post['customer_id'] != ''){
			$customer_id = $this->request->post['customer_id'];
			if($customer_id){
				$results1 = $this->getReturnsapi($customer_id);
				
				if(!empty($results1)){
				foreach ($results1 as $result) {
					$data['results'][] = array(
						'return_id'  => $result['return_id'],
						'order_id'   => $result['order_id'],
						'name'       => $result['firstname'] . ' ' . $result['lastname'],
						'status'     => $result['status'],
						'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added']))
						//'href'       => $this->url->link('account/return/info', 'return_id=' . $result['return_id'] . $url, 'SSL')
					);
				}
				}else{
				$results = 'null';
				}
			}else{
				$results = 'null';
				
			}
		
		
		if(count($results1)){
			$json['success'] 	= true;
			$json['returns'] 	= $data;
		}else {
			$json['success'] 	= false;
		}
		
		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';

		} else {
			$this->response->setOutput(json_encode($json));
		}
		}
	}
	public function returnbyid() {
		$this->checkPlugin();
		$results =array();
		
		if(isset($this->request->post['customer_id']) && $this->request->post['customer_id'] != ''){
			$customer_id = $this->request->post['customer_id'];
			$return_id = $this->request->post['return_id'];
			if($customer_id && $return_id){
				$results = $this->getReturnapi($return_id,$customer_id);
			}else{
				$results = 'null';
				
			}
		
		
		if(count($results)){
			$json['success'] 	= true;
			$json['return'] 	= $results;
		}else {
			$json['success'] 	= false;
		}
		
		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';

		} else {
			$this->response->setOutput(json_encode($json));
		}
		}
	}
	
	public function login() {
		$this->checkPlugin();
		$results =array();
		
		if(isset($this->request->post['email']) && isset($this->request->post['password']) && $this->request->post['email'] != '' && $this->request->post['password'] != ''){
			$email = $this->request->post['email'];
			$password = $this->request->post['password'];
			$this->load->model('account/customer');
			$status = $this->customer->login($email, $password);
			if($status == true){
			$results = $this->model_account_customer->getCustomer($this->customer->getId());
			$state = 'true';
		}else{ $results = array('userName & password Failed'); $state = 'False'; }
		}
		
		
		if(count($results)){
			$json['success'] 	= $state;
			$json['userdata'] 	= $results;
		}else {
			$json['success'] 	= false;
		}
		
		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';

		} else {
			$this->response->setOutput(json_encode($json));
		}
	}	//seo
	public function register() {
		$this->checkPlugin();
		$this->load->model('account/customer');
		$results = array();
		if($this->request->post){
			$customer_info = $this->model_account_customer->getCustomerByEmail($this->request->post['email']);
			if(empty($customer_info)){
				$customer_id = $this->model_account_customer->addCustomer($this->request->post);
				if($customer_id){
					//$results = $customer_id;
					//$this->customer->login($this->request->post['email'], $this->request->post['password']);
					$results = $this->model_account_customer->getCustomer($customer_id);
					}else{$results = 'Not Complete Register';}
				}else{$results = 'Email Alredy Exist';}	
		}
		
		if(count($results)){
			$json['success'] 	= true;
			$json['userdata'] 	= $results;
		}else {
			$json['success'] 	= false;
		}
		
		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';

		} else {
			$this->response->setOutput(json_encode($json));
		}
	}	//seo
	public function editCustomer() {
		$this->checkPlugin();
		$this->load->model('account/customer');
		$this->load->language('account/edit');//customer_id
		$results = array();
		if(isset($this->request->post) && isset($this->request->post['customer_id']) &&  $this->request->post != '' && $this->request->post['customer_id'] != ''){
			$this->editCustomers($this->request->post);
			$results = $this->language->get('text_success');
		}
		if(count($results)){
			$json['success'] 	= true;
			$json['userdata'] 	= $results;
		}else {
			$json['success'] 	= false;
		}
		
		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';

		} else {
			$this->response->setOutput(json_encode($json));
		}
	}	//seo
	public function editPassword() {
		$this->checkPlugin();
		$results = array();
		$this->load->model('account/customer');
		$this->load->language('account/edit');
		$email = '';
		$password = '';
		$results = array();
		if(isset($this->request->post['email']) && isset($this->request->post['new_password']) && isset($this->request->post['old_password']) && $this->request->post['email'] && $this->request->post['new_password'] && $this->request->post['old_password']){
			
			$email = $this->request->post['email'];
			$old_pass = $this->request->post['old_password'];
			
			$status = $this->customer->login($email, $old_pass);
			if($status == true){
				$password = $this->request->post['new_password'];
				$this->model_account_customer->editPassword($email, $password);
				$results = $this->language->get('text_success');
			}else{$results = $this->language->get('Old Password Wrong');}
		}
		if(count($results)){
			$json['success'] 	= true;
			$json['result'] 	= $results;
		}else {
			$json['success'] 	= false;
		}
		
		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';

		} else {
			$this->response->setOutput(json_encode($json));
		}
	}	//seo
	
	public function getaddress() {
		$this->checkPlugin();
		$this->load->model('account/address');
		$results = array();
		if(isset($this->request->post['customer_id']) && $this->request->post['customer_id'] != ''){
			$customer_id = $this->request->post['customer_id'];
			$results = $this->getAddressesapi($customer_id);
		}
		if(count($results)){
			$json['success'] 	= true;
			$json['addressesdata'] 	= $results;
		}else {
			$json['success'] 	= false;
		}
		
		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';

		} else {
			$this->response->setOutput(json_encode($json));
		}
	}	//seo
	public function getaddressbyid() {
		$this->checkPlugin();
		$this->load->model('account/address');
		$results = array();
		if(isset($this->request->post['address_id']) && $this->request->post['address_id'] != ''){
			$address_id = $this->request->post['address_id'];
			$results = $this->getAddressidapi($address_id);
		}
		if(count($results)){
			$json['success'] 	= true;
			$json['addressdata'] 	= $results;
		}else {
			$json['success'] 	= false;
		}
		
		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';

		} else {
			$this->response->setOutput(json_encode($json));
		}
	}
	public function addaddress() {
		$this->checkPlugin();
		$this->load->model('account/address');
		$results = array();
		if($this->request->post && $this->request->post != ''){
			$address_id = $this->addAddressapi($this->request->post);
			$results = $this->getAddressapi($address_id,$this->request->post['customer_id']);
		}
		if(count($results)){
			$json['success'] 	= true;
			$json['addressesdata'] 	= $results;
		}else {
			$json['success'] 	= false;
		}
		
		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';

		} else {
			$this->response->setOutput(json_encode($json));
		}
	}	//seo
	public function editaddress() {
		$this->checkPlugin();
		$this->load->model('account/address');
		//$customer_id = $this->request->post['customer_id'];
		$results = array();
		if($this->request->post && $this->request->post != ''){
			if($this->editAddressapi($this->request->post)){
				$results = 'Successfully Update Address';
			}
		}
		if(count($results)){
			$json['success'] 	= true;
			$json['addressesdata'] 	= $results;
		}else {
			$json['success'] 	= false;
		}
		
		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';

		} else {
			$this->response->setOutput(json_encode($json));
		}
	}	//seo
	public function deleteaddress() {
		$this->checkPlugin();
		$this->load->model('account/address');
		$customer_id = 0;
		$address_id = 0;
		if(isset($this->request->post['customer_id']) && isset($this->request->post['address_id'])){
			$customer_id = $this->request->post['customer_id'];
			$address_id = $this->request->post['address_id'];
		}
		
		if($address_id != '' && $customer_id != '' && $this->deleteAddressapi($address_id,$customer_id)){
			$results = 'successfully Delete';
		}else {$results = array();}
		if(count($results)){
			$json['success'] 	= true;
			$json['delete'] 	= $results;
		}else {
			$json['success'] 	= false;
		}
		
		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';

		} else {
			$this->response->setOutput(json_encode($json));
		}
	} //seo
	public function getCountries() {
		$this->checkPlugin();
		$this->load->model('localisation/country');
		$results = $this->model_localisation_country->getCountries();
		if(count($results)){
			$json['success'] 	= true;
			$json['Countries'] 	= $results;
		}else {
			$json['success'] 	= false;
		}
		
		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';

		} else {
			$this->response->setOutput(json_encode($json));
		}
	}  //seo
	public function getCountry() {
		$this->checkPlugin();
		$this->load->model('localisation/country');
		if (isset($this->request->get['country_id']) && $this->request->get['country_id'] != "") {
			$country_id = $this->request->get['country_id'];
		} else {
			$country_id 	= 0;
		}
		$results = $this->model_localisation_country->getCountry($country_id);
		if(count($results)){
			$json['success'] 	= true;
			$json['Country'] 	= $results;
		}else {
			$json['success'] 	= false;
		}
		
		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';

		} else {
			$this->response->setOutput(json_encode($json));
		}
	} //seo
	public function getZonesByCountryId() {
		$this->checkPlugin();
		$this->load->model('localisation/zone');
		if (isset($this->request->get['country_id']) && $this->request->get['country_id'] != "") {
			$country_id = $this->request->get['country_id'];
		} else {
			$country_id 	= 0;
		}
		$results = $this->model_localisation_zone->getZonesByCountryId($country_id);
		if(count($results)){
			$json['success'] 	= true;
			$json['Zones'] 	= $results;
		}else {
			$json['success'] 	= false;
		}
		
		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';

		} else {
			$this->response->setOutput(json_encode($json));
		}
	} //seo
	public function getZone() {
		$this->checkPlugin();
		$this->load->model('localisation/zone');
		if (isset($this->request->get['zone_id']) && $this->request->get['zone_id'] != "") {
			$zone_id = $this->request->get['zone_id'];
		} else {
			$zone_id 	= 0;
		}
		$results = $this->model_localisation_zone->getZone($zone_id);
		if(count($results)){
			$json['success'] 	= true;
			$json['zone'] 	= $results;
		}else {
			$json['success'] 	= false;
		}
		
		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';

		} else {
			$this->response->setOutput(json_encode($json));
		}
	} //seo
	public function getLanguages() {
		$this->checkPlugin();
		$this->load->model('localisation/language');
		$results = $this->model_localisation_language->getLanguages();
		if(count($results)){
			$json['success'] 	= true;
			$json['Languages'] 	= $results;
		}else {
			$json['success'] 	= false;
		}
		
		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';

		} else {
			$this->response->setOutput(json_encode($json));
		}
	} //seo
	public function getLanguage() {
		$this->checkPlugin();
		$this->load->model('localisation/language');
		if (isset($this->request->get['code']) && $this->request->get['code'] != "") {
			$code = $this->request->get['code'];
		} else {
			$code 	= '';
		}
		$results = $this->getLanguageapi($code);
		if(count($results)){
			$json['success'] 	= true;
			$json['Language'] 	= $results;
		}else {
			$json['success'] 	= false;
		}
		
		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';

		} else {
			$this->response->setOutput(json_encode($json));
		}
	}  //seo
	public function getSettings() {
		$this->checkPlugin();
		$this->load->model('setting/setting');
		$results = $this->getSettingsapi();
		if(count($results)){
			$json['success'] 	= true;
			$json['Settings'] 	= $results;
		}else {
			$json['success'] 	= false;
		}
		
		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';

		} else {
			$this->response->setOutput(json_encode($json));
		}
	} //seo
	public function getSetting($scode = '') {
		if(isset($scode) && $scode != ''){ $code = $scode; }
		$this->checkPlugin();
		$this->load->model('setting/setting');
		if (isset($this->request->get['code']) && $this->request->get['code'] != "") {
			$code = $this->request->get['code'];
		} else {
			$code 	= 0;
		}
		$results = $this->model_setting_setting->getSetting($code);
		if(count($results)){
			$json['success'] 	= true;
			$json['Setting'] 	= $results;
		}else {
			$json['success'] 	= false;
		}
		
		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';

		} else {
			$this->response->setOutput(json_encode($json));
		}
	} //seo
	public function getCurrencies() {
		$this->checkPlugin();
		$this->load->model('localisation/currency');
		$results = $this->model_localisation_currency->getCurrencies();
		if(count($results)){
			$json['success'] 	= true;
			$json['Currencies'] 	= $results;
		}else {
			$json['success'] 	= false;
		}
		
		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';

		} else {
			$this->response->setOutput(json_encode($json));
		}
	} //seo
	public function getCurrencyByCode() {
		$this->checkPlugin();
		$this->load->model('localisation/currency');
		if (isset($this->request->get['curr_code']) && $this->request->get['curr_code'] != "") {
			$curr_code = $this->request->get['curr_code'];
		} else {
			$curr_code 	= 0;
		}
		$results = $this->model_localisation_currency->getCurrencyByCode($curr_code);
		if(count($results)){
			$json['success'] 	= true;
			$json['Currencie'] 	= $results;
		}else {
			$json['success'] 	= false;
		}
		
		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';

		} else {
			$this->response->setOutput(json_encode($json));
		}
	} //seo
	public function addorder() {
		$this->checkPlugin();
		$this->load->model('checkout/order');
		if(isset($this->request->post['addorder'])){
			$addorder = htmlspecialchars_decode($this->request->post['addorder']);
		}
		
		
		$results = array();
		if(isset($addorder) && $addorder != ''){
		$dataorder1 = json_decode($addorder);
		
		$results1 = array();
		$dataorder = $this->convertobjtoarray($dataorder1,$results1);
		//$dataorder = json_decode(json_encode($addorder), true);
		
			if(isset($dataorder) && $dataorder != ''){
				$order_id = $this->model_checkout_order->addOrder($dataorder);
				if($order_id && $order_id != ''){
					$this->db->query("UPDATE `" . DB_PREFIX . "order` SET order_status_id = '" . (int)$dataorder['order_status_id'] . "', date_modified = NOW() WHERE order_id = '" . (int)$order_id . "'");
				}
				$results = $this->model_checkout_order->getOrder($order_id);
			}else{
				$results = array();
			}
		}
		if(count($results)){
			$json['success'] 	= true;
			$json['order'] 	= $results;
		}else {
			$json['success'] 	= false;
		}
		
		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';

		} else {
			$this->response->setOutput(json_encode($json));
		}
	} //seo
	public function shippingmethod() {
		$this->checkPlugin();
		$this->load->model('extension/extension');
		$results = $this->model_extension_extension->getExtensions('shipping');		
		/*foreach ($shipp_results as $result) {
				if ($this->config->get($result['code'] . '_status')) {
					$this->load->model('shipping/' . $result['code']);

					$quote = $this->{'model_shipping_' . $result['code']}->getQuote($this->session->data['shipping_address']);

					if ($quote) {
						$results[$result['code']] = array(
							'title'      => $quote['title'],
							'quote'      => $quote['quote'],
							'sort_order' => $quote['sort_order'],
							'error'      => $quote['error']
						);
					}
				}
			}*/
		if(count($results)){
			$json['success'] 	= true;
			$json['ShippingMethod'] 	= $results;
		}else {
			$json['success'] 	= false;
		}
		
		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';

		} else {
			$this->response->setOutput(json_encode($json));
		}
	} //seo
	public function paymentmethod() {
		$this->checkPlugin();
		$this->load->model('extension/extension');
		$results = $this->model_extension_extension->getExtensions('payment');
		/*$recurring = $this->cart->hasRecurringProducts();
		foreach ($results as $result) {
				if ($this->config->get($result['code'] . '_status')) {
					$this->load->model('payment/' . $result['code']);

					$method = $this->{'model_payment_' . $result['code']}->getMethod($this->session->data['payment_address'], $total);

					if ($method) {
						if ($recurring) {
							if (method_exists($this->{'model_payment_' . $result['code']}, 'recurringPayments') && $this->{'model_payment_' . $result['code']}->recurringPayments()) {
								$method_data[$result['code']] = $method;
							}
						} else {
							$method_data[$result['code']] = $method;
						}
					}
				}
			}*/
		if(count($results)){
			$json['success'] 	= true;
			$json['PaymentMethod'] 	= $results;
		}else {
			$json['success'] 	= false;
		}
		
		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';

		} else {
			$this->response->setOutput(json_encode($json));
		}
	} //seo
	public function cartproduct() {
		$this->checkPlugin();
		$this->load->model('account/customer');
		$customer_id = 0;
		if(isset($this->request->post['customer_id'])){
			$customer_id = $this->request->post['customer_id'];
		}
		$res_data = array();
		$cartresults = array();
		if(isset($customer_id) && $customer_id != ''){
		$cartresults = $this->model_account_customer->getCustomer($customer_id);
		}
		
		if(!empty($cartresults)){
		$cart_array = unserialize($cartresults['cart']);
		$cartdata = array();
		foreach ($cart_array as $key => $value) {
					if (!array_key_exists($key, $cartdata)) {
						$cartdata[$key] = $value;
					} else {
						$cartdata[$key] += $value;
					}
				}
		foreach ($cartdata as $key => $quantity) {
				$product = unserialize(base64_decode($key));

				$product_id = $product['product_id'];

				$stock = true;

				// Options
				if (!empty($product['option'])) {
					$options = $product['option'];
				} else {
					$options = array();
				}

				// Profile
				if (!empty($product['recurring_id'])) {
					$recurring_id = $product['recurring_id'];
				} else {
					$recurring_id = 0;
				}

				$product_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) WHERE p.product_id = '" . (int)$product_id . "' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.date_available <= NOW() AND p.status = '1'");

				if ($product_query->num_rows) {
					$option_price = 0;
					$option_points = 0;
					$option_weight = 0;

					$option_data = array();

					foreach ($options as $product_option_id => $value) {
						$option_query = $this->db->query("SELECT po.product_option_id, po.option_id, od.name, o.type FROM " . DB_PREFIX . "product_option po LEFT JOIN `" . DB_PREFIX . "option` o ON (po.option_id = o.option_id) LEFT JOIN " . DB_PREFIX . "option_description od ON (o.option_id = od.option_id) WHERE po.product_option_id = '" . (int)$product_option_id . "' AND po.product_id = '" . (int)$product_id . "' AND od.language_id = '" . (int)$this->config->get('config_language_id') . "'");

						if ($option_query->num_rows) {
							if ($option_query->row['type'] == 'select' || $option_query->row['type'] == 'radio' || $option_query->row['type'] == 'image') {
								$option_value_query = $this->db->query("SELECT pov.option_value_id, ovd.name, pov.quantity, pov.subtract, pov.price, pov.price_prefix, pov.points, pov.points_prefix, pov.weight, pov.weight_prefix FROM " . DB_PREFIX . "product_option_value pov LEFT JOIN " . DB_PREFIX . "option_value ov ON (pov.option_value_id = ov.option_value_id) LEFT JOIN " . DB_PREFIX . "option_value_description ovd ON (ov.option_value_id = ovd.option_value_id) WHERE pov.product_option_value_id = '" . (int)$value . "' AND pov.product_option_id = '" . (int)$product_option_id . "' AND ovd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

								if ($option_value_query->num_rows) {
									if ($option_value_query->row['price_prefix'] == '+') {
										$option_price += $option_value_query->row['price'];
									} elseif ($option_value_query->row['price_prefix'] == '-') {
										$option_price -= $option_value_query->row['price'];
									}

									if ($option_value_query->row['points_prefix'] == '+') {
										$option_points += $option_value_query->row['points'];
									} elseif ($option_value_query->row['points_prefix'] == '-') {
										$option_points -= $option_value_query->row['points'];
									}

									if ($option_value_query->row['weight_prefix'] == '+') {
										$option_weight += $option_value_query->row['weight'];
									} elseif ($option_value_query->row['weight_prefix'] == '-') {
										$option_weight -= $option_value_query->row['weight'];
									}

									if ($option_value_query->row['subtract'] && (!$option_value_query->row['quantity'] || ($option_value_query->row['quantity'] < $quantity))) {
										$stock = false;
									}

									$option_data[] = array(
										'product_option_id'       => $product_option_id,
										'product_option_value_id' => $value,
										'option_id'               => $option_query->row['option_id'],
										'option_value_id'         => $option_value_query->row['option_value_id'],
										'name'                    => $option_query->row['name'],
										'value'                   => $option_value_query->row['name'],
										'type'                    => $option_query->row['type'],
										'quantity'                => $option_value_query->row['quantity'],
										'subtract'                => $option_value_query->row['subtract'],
										'price'                   => $option_value_query->row['price'],
										'price_prefix'            => $option_value_query->row['price_prefix'],
										'points'                  => $option_value_query->row['points'],
										'points_prefix'           => $option_value_query->row['points_prefix'],
										'weight'                  => $option_value_query->row['weight'],
										'weight_prefix'           => $option_value_query->row['weight_prefix']
									);
								}
							} elseif ($option_query->row['type'] == 'checkbox' && is_array($value)) {
								foreach ($value as $product_option_value_id) {
									$option_value_query = $this->db->query("SELECT pov.option_value_id, ovd.name, pov.quantity, pov.subtract, pov.price, pov.price_prefix, pov.points, pov.points_prefix, pov.weight, pov.weight_prefix FROM " . DB_PREFIX . "product_option_value pov LEFT JOIN " . DB_PREFIX . "option_value ov ON (pov.option_value_id = ov.option_value_id) LEFT JOIN " . DB_PREFIX . "option_value_description ovd ON (ov.option_value_id = ovd.option_value_id) WHERE pov.product_option_value_id = '" . (int)$product_option_value_id . "' AND pov.product_option_id = '" . (int)$product_option_id . "' AND ovd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

									if ($option_value_query->num_rows) {
										if ($option_value_query->row['price_prefix'] == '+') {
											$option_price += $option_value_query->row['price'];
										} elseif ($option_value_query->row['price_prefix'] == '-') {
											$option_price -= $option_value_query->row['price'];
										}

										if ($option_value_query->row['points_prefix'] == '+') {
											$option_points += $option_value_query->row['points'];
										} elseif ($option_value_query->row['points_prefix'] == '-') {
											$option_points -= $option_value_query->row['points'];
										}

										if ($option_value_query->row['weight_prefix'] == '+') {
											$option_weight += $option_value_query->row['weight'];
										} elseif ($option_value_query->row['weight_prefix'] == '-') {
											$option_weight -= $option_value_query->row['weight'];
										}

										if ($option_value_query->row['subtract'] && (!$option_value_query->row['quantity'] || ($option_value_query->row['quantity'] < $quantity))) {
											$stock = false;
										}

										$option_data[] = array(
											'product_option_id'       => $product_option_id,
											'product_option_value_id' => $product_option_value_id,
											'option_id'               => $option_query->row['option_id'],
											'option_value_id'         => $option_value_query->row['option_value_id'],
											'name'                    => $option_query->row['name'],
											'value'                   => $option_value_query->row['name'],
											'type'                    => $option_query->row['type'],
											'quantity'                => $option_value_query->row['quantity'],
											'subtract'                => $option_value_query->row['subtract'],
											'price'                   => $option_value_query->row['price'],
											'price_prefix'            => $option_value_query->row['price_prefix'],
											'points'                  => $option_value_query->row['points'],
											'points_prefix'           => $option_value_query->row['points_prefix'],
											'weight'                  => $option_value_query->row['weight'],
											'weight_prefix'           => $option_value_query->row['weight_prefix']
										);
									}
								}
							} elseif ($option_query->row['type'] == 'text' || $option_query->row['type'] == 'textarea' || $option_query->row['type'] == 'file' || $option_query->row['type'] == 'date' || $option_query->row['type'] == 'datetime' || $option_query->row['type'] == 'time') {
								$option_data[] = array(
									'product_option_id'       => $product_option_id,
									'product_option_value_id' => '',
									'option_id'               => $option_query->row['option_id'],
									'option_value_id'         => '',
									'name'                    => $option_query->row['name'],
									'value'                   => $value,
									'type'                    => $option_query->row['type'],
									'quantity'                => '',
									'subtract'                => '',
									'price'                   => '',
									'price_prefix'            => '',
									'points'                  => '',
									'points_prefix'           => '',
									'weight'                  => '',
									'weight_prefix'           => ''
								);
							}
						}
					}

					$price = $product_query->row['price'];

					// Product Discounts
					$discount_quantity = 0;

					foreach ($this->session->data['cart'] as $key_2 => $quantity_2) {
						$product_2 = (array)unserialize(base64_decode($key_2));

						if ($product_2['product_id'] == $product_id) {
							$discount_quantity += $quantity_2;
						}
					}

					$product_discount_query = $this->db->query("SELECT price FROM " . DB_PREFIX . "product_discount WHERE product_id = '" . (int)$product_id . "' AND customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND quantity <= '" . (int)$discount_quantity . "' AND ((date_start = '0000-00-00' OR date_start < NOW()) AND (date_end = '0000-00-00' OR date_end > NOW())) ORDER BY quantity DESC, priority ASC, price ASC LIMIT 1");

					if ($product_discount_query->num_rows) {
						$price = $product_discount_query->row['price'];
					}

					// Product Specials
					$product_special_query = $this->db->query("SELECT price FROM " . DB_PREFIX . "product_special WHERE product_id = '" . (int)$product_id . "' AND customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND ((date_start = '0000-00-00' OR date_start < NOW()) AND (date_end = '0000-00-00' OR date_end > NOW())) ORDER BY priority ASC, price ASC LIMIT 1");

					if ($product_special_query->num_rows) {
						$price = $product_special_query->row['price'];
					}

					// Reward Points
					$product_reward_query = $this->db->query("SELECT points FROM " . DB_PREFIX . "product_reward WHERE product_id = '" . (int)$product_id . "' AND customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "'");

					if ($product_reward_query->num_rows) {
						$reward = $product_reward_query->row['points'];
					} else {
						$reward = 0;
					}

					// Downloads
					$download_data = array();

					$download_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_download p2d LEFT JOIN " . DB_PREFIX . "download d ON (p2d.download_id = d.download_id) LEFT JOIN " . DB_PREFIX . "download_description dd ON (d.download_id = dd.download_id) WHERE p2d.product_id = '" . (int)$product_id . "' AND dd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

					foreach ($download_query->rows as $download) {
						$download_data[] = array(
							'download_id' => $download['download_id'],
							'name'        => $download['name'],
							'filename'    => $download['filename'],
							'mask'        => $download['mask']
						);
					}

					// Stock
					if (!$product_query->row['quantity'] || ($product_query->row['quantity'] < $quantity)) {
						$stock = false;
					}

					$recurring_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "recurring` `p` JOIN `" . DB_PREFIX . "product_recurring` `pp` ON `pp`.`recurring_id` = `p`.`recurring_id` AND `pp`.`product_id` = " . (int)$product_query->row['product_id'] . " JOIN `" . DB_PREFIX . "recurring_description` `pd` ON `pd`.`recurring_id` = `p`.`recurring_id` AND `pd`.`language_id` = " . (int)$this->config->get('config_language_id') . " WHERE `pp`.`recurring_id` = " . (int)$recurring_id . " AND `status` = 1 AND `pp`.`customer_group_id` = " . (int)$this->config->get('config_customer_group_id'));

					if ($recurring_query->num_rows) {
						$recurring = array(
							'recurring_id'    => $recurring_id,
							'name'            => $recurring_query->row['name'],
							'frequency'       => $recurring_query->row['frequency'],
							'price'           => $recurring_query->row['price'],
							'cycle'           => $recurring_query->row['cycle'],
							'duration'        => $recurring_query->row['duration'],
							'trial'           => $recurring_query->row['trial_status'],
							'trial_frequency' => $recurring_query->row['trial_frequency'],
							'trial_price'     => $recurring_query->row['trial_price'],
							'trial_cycle'     => $recurring_query->row['trial_cycle'],
							'trial_duration'  => $recurring_query->row['trial_duration']
						);
					} else {
						$recurring = false;
					}

					$res_data[$key] = array(
						'key'             => $key,
						'product_id'      => $product_query->row['product_id'],
						'name'            => $product_query->row['name'],
						'model'           => $product_query->row['model'],
						'shipping'        => $product_query->row['shipping'],
						'image'           => $product_query->row['image'],
						'option'          => $option_data,
						'download'        => $download_data,
						'quantity'        => $quantity,
						'minimum'         => $product_query->row['minimum'],
						'subtract'        => $product_query->row['subtract'],
						'stock'           => $stock,
						'price'           => ($price + $option_price),
						'total'           => ($price + $option_price) * $quantity,
						'reward'          => $reward * $quantity,
						'points'          => ($product_query->row['points'] ? ($product_query->row['points'] + $option_points) * $quantity : 0),
						'tax_class_id'    => $product_query->row['tax_class_id'],
						'weight'          => ($product_query->row['weight'] + $option_weight) * $quantity,
						'weight_class_id' => $product_query->row['weight_class_id'],
						'length'          => $product_query->row['length'],
						'width'           => $product_query->row['width'],
						'height'          => $product_query->row['height'],
						'length_class_id' => $product_query->row['length_class_id'],
						'recurring'       => $recurring
					);
				} else {
					$this->remove($key);
				}
			}
		}
		
		if(count($res_data)){
			$json['success'] 	= true;
			$json['cartproduct'] 	= $res_data;
		}else {
			$json['success'] 	= false;
		}
		
		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';

		} else {
			$this->response->setOutput(json_encode($json));
		}
	} //seo
	public function getSpecial() {
		$this->checkPlugin();
		$this->load->model('catalog/product');
		$limit = $this->request->get['limit'];
		
		$filter_data = array(
			'sort'  => 'pd.name',
			'order' => 'ASC',
			'start' => 0,
			'limit' => $limit
		);
		$results1 = $this->model_catalog_product->getProductSpecials($filter_data);
		foreach($results1 as $result){
			
				if ($result['image']) {
					$image = $result['image'];
				} else {
					$image = 'placeholder.png';
				}

				$price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')));

				if ((float)$result['special']) {
					$special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')));
				} else {
					$special = false;
				}

				if ($this->config->get('config_tax')) {
					$tax = $this->currency->format((float)$result['special'] ? $result['special'] : $result['price']);
				} else {
					$tax = false;
				}

				if ($this->config->get('config_review_status')) {
					$rating = $result['rating'];
				} else {
					$rating = false;
				}

				$results[] = array(
					'product_id'  => $result['product_id'],
					'thumb'       => $image,
					'name'        => $result['name'],
					'description' => utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get('config_product_description_length')) . '..',
					'price'       => $price,
					'special'     => $special,
					'tax'         => $tax,
					'stock_status'=> $result['stock_status'],
					'manufacturer'=> $result['manufacturer'],
					'rating'      => $rating,
					'quantity'     => $result['quantity'],
					'reviews'     => $result['reviews'],
					'href'        => $this->url->link('product/product', 'product_id=' . $result['product_id'])
				);
			
			//$results[] = $result;
		}
		
		if(count($results)){
			$json['success'] 	= true;
			$json['Special'] 	= $results;
		}else {
			$json['success'] 	= false;
		}
		
		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';

		} else {
			$this->response->setOutput(json_encode($json));
		}
	}//seo
	public function getLatest() {
		$this->checkPlugin();
		$this->load->model('catalog/product');
		$limit = $this->request->get['limit'];
		$filter_data = array(
			'sort'  => 'p.date_added',
			'order' => 'DESC',
			'start' => 0,
			'limit' => $limit
		);
		$results1 = $this->model_catalog_product->getProducts($filter_data);
		foreach($results1 as $result){
			
				if ($result['image']) {
					$image = $result['image'];
				} else {
					$image = 'placeholder.png';
				}

				$price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')));

				if ((float)$result['special']) {
					$special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')));
				} else {
					$special = false;
				}

				if ($this->config->get('config_tax')) {
					$tax = $this->currency->format((float)$result['special'] ? $result['special'] : $result['price']);
				} else {
					$tax = false;
				}

				if ($this->config->get('config_review_status')) {
					$rating = $result['rating'];
				} else {
					$rating = false;
				}

				$results[] = array(
					'product_id'  => $result['product_id'],
					'thumb'       => $image,
					'name'        => $result['name'],
					'description' => utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get('config_product_description_length')) . '..',
					'price'       => $price,
					'special'     => $special,
					'tax'         => $tax,
					'stock_status'=> $result['stock_status'],
					'manufacturer'=> $result['manufacturer'],
					'rating'      => $rating,
					'quantity'     => $result['quantity'],
					'reviews'     => $result['reviews'],
					'href'        => $this->url->link('product/product', 'product_id=' . $result['product_id']),
				);
			
			//$results[] = $result;
		}
		if(count($results)){
			$json['success'] 	= true;
			$json['Latest'] 	= $results;
		}else {
			$json['success'] 	= false;
		}
		
		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';

		} else {
			$this->response->setOutput(json_encode($json));
		}
	}//seo
	public function getFeature() {
		$this->checkPlugin();
		$this->load->model('catalog/product');
		$this->load->model('extension/module');
		$results =array();
		$limit = $this->request->get['limit'];
		$mod_name = $this->request->get['mod_name'];
		$setting = $this->getModuleapi($mod_name,'featured');
		//print_r($results['product']);
		if (!empty($setting['product'])) {
			$products = array_slice($setting['product'], 0, (int)$limit);
			foreach ($products as $product_id) {
				
				$product_info = $this->model_catalog_product->getProduct($product_id);

				if ($product_info) {
					if ($product_info['image']) {
						$image = $product_info['image'];
					} else {
						$image = 'placeholder.png';
					}

					$price = $this->currency->format($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax')));

					if ((float)$product_info['special']) {
						$special = $this->currency->format($this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $this->config->get('config_tax')));
					} else {
						$special = false;
					}

					if ($this->config->get('config_tax')) {
						$tax = $this->currency->format((float)$product_info['special'] ? $product_info['special'] : $product_info['price']);
					} else {
						$tax = false;
					}

					if ($this->config->get('config_review_status')) {
						$rating = $product_info['rating'];
					} else {
						$rating = false;
					}

					$results[] = array(
						'product_id'  => $product_info['product_id'],
						'thumb'       => $image,
						'name'        => $product_info['name'],
						'description' => utf8_substr(strip_tags(html_entity_decode($product_info['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get('config_product_description_length')) . '..',
						'price'       => $price,
						'special'     => $special,
						'tax'         => $tax,
						'stock_status'=> $product_info['stock_status'],
						'manufacturer'=> $product_info['manufacturer'],
						'rating'      => $rating,
						'quantity'     => $product_info['quantity'],
					    'reviews'     => $product_info['reviews'],
						'href'        => $this->url->link('product/product', 'product_id=' . $product_info['product_id'])
					);
				}
			
				//$results[] = $this->model_catalog_product->getProduct($product_id);
			}
		}
		if(count($results)){
			$json['success'] 	= true;
			$json['Feature'] 	= $results;
		}else {
			$json['success'] 	= false;
		}
		
		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';

		} else {
			$this->response->setOutput(json_encode($json));
		}
	}	//seo
	public function getBestseller() {
		$this->checkPlugin();
		$this->load->model('catalog/product');
		$limit = $this->request->get['limit'];
		$results1 = $this->model_catalog_product->getBestSellerProducts($limit);
		foreach($results1 as $result){
			
				if ($result['image']) {
					$image = $result['image'];
				} else {
					$image = 'placeholder.png';
				}

				$price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')));

				if ((float)$result['special']) {
					$special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')));
				} else {
					$special = false;
				}

				if ($this->config->get('config_tax')) {
					$tax = $this->currency->format((float)$result['special'] ? $result['special'] : $result['price']);
				} else {
					$tax = false;
				}

				if ($this->config->get('config_review_status')) {
					$rating = $result['rating'];
				} else {
					$rating = false;
				}

				$results[] = array(
					'product_id'  => $result['product_id'],
					'thumb'       => $image,
					'name'        => $result['name'],
					'description' => utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get('config_product_description_length')) . '..',
					'price'       => $price,
					'special'     => $special,
					
					'tax'         => $tax,
					'stock_status'=> $result['stock_status'],
					'manufacturer'=> $result['manufacturer'],
					'rating'      => $rating,
					'quantity'     => $result['quantity'],
					'reviews'     => $result['reviews'],
					'href'        => $this->url->link('product/product', 'product_id=' . $result['product_id']),
				);
			
			//$results[] = $result;
		}
		if(count($results)){
			$json['success'] 	= true;
			$json['Bestseller'] 	= $results;
		}else {
			$json['success'] 	= false;
		}
		
		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';

		} else {
			$this->response->setOutput(json_encode($json));
		}
	}//seo
	public function productreturn() {
		$this->checkPlugin();
		if (isset($this->request->post['return_data']) && $this->request->post['return_data'] != '') {
			$this->load->language('account/return');
			$this->load->model('account/return');
			$re_data = (array) json_decode(htmlspecialchars_decode($this->request->post['return_data']));
			
			$return_id = $this->model_account_return->addReturn($re_data);
		}		
		if(count($return_id)){
			$json['success'] 	= true;
			$json['return_id'] 	= $return_id;
		}else {
			$json['success'] 	= false;
		}
		
		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';

		} else {
			$this->response->setOutput(json_encode($json));
		}
	}//seo
	public function taxclassapi() {
		$this->checkPlugin();
		//$this->load->model('catalog/product');
		$tax_class_id = $this->request->get['tax_class_id'];
		$tax_class = $this->getTaxClassapi($tax_class_id);
		$tax_rates1 = $this->getTaxRulesapi($tax_class_id);
		$tax_rates = array();
		foreach($tax_rates1 as $tax_rate){
			//$tax_rates[$tax_class_id] = $tax_rate;
			$tax_rates['value'][] = $this->getTaxRateapi($tax_rate['tax_rate_id']);
		}
		//echo "<pre>"; print_r($tax_rates);
		$results = array('tax_class'=>$tax_class,'tax_rate'=>$tax_rates);
		
		if(count($results)){
			$json['success'] 	= true;
			$json['taxvalue'] 	= $results;
		}else {
			$json['success'] 	= false;
		}
		
		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';

		} else {
			$this->response->setOutput(json_encode($json));
		}
	}//seo
	public function searchproduct() {
		$this->checkPlugin();
		$this->load->model('catalog/product');
		$search = '';
		if(isset($this->request->get['seakeyword']) && $this->request->get['seakeyword'] != ''){
			$search = $this->request->get['seakeyword'];
		}
		//echo $search;die();
		$filter_data = array(
				'filter_name'         => $search,
				'filter_tag'          => '',
				'filter_description'  => '',
				'filter_category_id'  => '',
				'filter_sub_category' => '',
				'sort'                => '',
				'order'               => '',
				'start'               => '',
				'limit'               => ''
			);
		$seakeyword = '';
		$results = $this->model_catalog_product->getProducts($filter_data);
		if(count($results)){
			$json['success'] 	= true;
			$json['searchresult'] 	= $results;
		}else {
			$json['success'] 	= false;
		}
		
		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';

		} else {
			$this->response->setOutput(json_encode($json));
		}
	}//seo
	
	
	
	
	
		
	public function orders() {

		$this->checkPlugin();
	
		$orderData['orders'] = array();

		$this->load->model('account/order');

		/*check offset parameter*/
		if (isset($this->request->get['offset']) && $this->request->get['offset'] != "" && ctype_digit($this->request->get['offset'])) {
			$offset = $this->request->get['offset'];
		} else {
			$offset 	= 0;
		}

		/*check limit parameter*/
		if (isset($this->request->get['limit']) && $this->request->get['limit'] != "" && ctype_digit($this->request->get['limit'])) {
			$limit = $this->request->get['limit'];
		} else {
			$limit 	= 10000;
		}
		
		/*get all orders of user*/
		$results = $this->model_account_order->getAllOrders($offset, $limit);
		
		$orders = array();

		if(count($results)){
			foreach ($results as $result) {

				$product_total = $this->model_account_order->getTotalOrderProductsByOrderId($result['order_id']);
				$voucher_total = $this->model_account_order->getTotalOrderVouchersByOrderId($result['order_id']);

				$orders[] = array(
						'order_id'		=> $result['order_id'],
						'name'			=> $result['firstname'] . ' ' . $result['lastname'],
						'status'		=> $result['status'],
						'date_added'	=> $result['date_added'],
						'products'		=> ($product_total + $voucher_total),
						'total'			=> $result['total'],
						'currency_code'	=> $result['currency_code'],
						'currency_value'=> $result['currency_value'],
				);
			}

			$json['success'] 	= true;
			$json['orders'] 	= $orders;
		}else {
			$json['success'] 	= false;
		}
		
		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';

		} else {
			$this->response->setOutput(json_encode($json));
		}
	}
	
	
	
	private function checkPlugin() {

		$json = array("success"=>false);

		/*check rest api is enabled*/
		if (!$this->config->get('rest_api_status')) {
			$json["error"] = 'API is disabled. Enable it!';
		}
		
		/*validate api security key*/
		if ($this->config->get('rest_api_key') && (!isset($this->request->get['key']) || $this->request->get['key'] != $this->config->get('rest_api_key'))) {
			$json["error"] = 'Invalid secret key';
		}
		
		if(isset($json["error"])){
			$this->response->addHeader('Content-Type: application/json');
			echo(json_encode($json));
			exit;
		}else {
			$this->response->setOutput(json_encode($json));			
		}	
	}	
	private function getOrdersapi($start = 0, $limit = 20,$user_id) {
		if ($start < 0) {
			$start = 0;
		}

		if ($limit < 1) {
			$limit = 1;
		}

		$query = $this->db->query("SELECT o.order_id, o.firstname, o.lastname, os.name as status, o.date_added, o.total, o.currency_code, o.currency_value FROM `" . DB_PREFIX . "order` o LEFT JOIN " . DB_PREFIX . "order_status os ON (o.order_status_id = os.order_status_id) WHERE o.customer_id = '" . (int)$user_id . "' AND o.order_status_id > '0' AND o.store_id = '" . (int)$this->config->get('config_store_id') . "' AND os.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY o.order_id DESC LIMIT " . (int)$start . "," . (int)$limit);

		return $query->rows;
	}
	private function editCustomers($data) {

		$this->db->query("UPDATE " . DB_PREFIX . "customer SET firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', fax = '" . $this->db->escape($data['fax']) . "', custom_field = '" . $this->db->escape(isset($data['custom_field']) ? serialize($data['custom_field']) : '') . "' WHERE customer_id = '" . (int)$data['customer_id'] . "'");

	}
	private function getAddressesapi($cust_id) {
		$address_data = array();
		//echo $this->customer->getId()
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "address WHERE customer_id = '" . (int)$cust_id . "'");

		foreach ($query->rows as $result) {
			$country_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE country_id = '" . (int)$result['country_id'] . "'");

			if ($country_query->num_rows) {
				$country = $country_query->row['name'];
				$iso_code_2 = $country_query->row['iso_code_2'];
				$iso_code_3 = $country_query->row['iso_code_3'];
				$address_format = $country_query->row['address_format'];
			} else {
				$country = '';
				$iso_code_2 = '';
				$iso_code_3 = '';
				$address_format = '';
			}

			$zone_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE zone_id = '" . (int)$result['zone_id'] . "'");

			if ($zone_query->num_rows) {
				$zone = $zone_query->row['name'];
				$zone_code = $zone_query->row['code'];
			} else {
				$zone = '';
				$zone_code = '';
			}

			//$address_data[$result['address_id']] = array(
				$address_data[] = array(			
				'address_id'     => $result['address_id'],
				'firstname'      => $result['firstname'],
				'lastname'       => $result['lastname'],
				'company'        => $result['company'],
				'address_1'      => $result['address_1'],
				'address_2'      => $result['address_2'],
				'postcode'       => $result['postcode'],
				'city'           => $result['city'],
				'zone_id'        => $result['zone_id'],
				'zone'           => $zone,
				'zone_code'      => $zone_code,
				'country_id'     => $result['country_id'],
				'country'        => $country,
				'iso_code_2'     => $iso_code_2,
				'iso_code_3'     => $iso_code_3,
				'address_format' => $address_format,
				'custom_field'   => unserialize($result['custom_field'])

			);
		}

		return $address_data;
	}
	private function addAddressapi($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "address SET customer_id = '" . (int)$data['customer_id'] . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', company = '" . $this->db->escape($data['company']) . "', address_1 = '" . $this->db->escape($data['address_1']) . "', address_2 = '" . $this->db->escape($data['address_2']) . "', postcode = '" . $this->db->escape($data['postcode']) . "', city = '" . $this->db->escape($data['city']) . "', zone_id = '" . (int)$data['zone_id'] . "', country_id = '" . (int)$data['country_id'] . "', custom_field = '" . $this->db->escape(isset($data['custom_field']) ? serialize($data['custom_field']) : '') . "'");

		$address_id = $this->db->getLastId();

		if (($data['set_default'] == 1)) {
			$this->db->query("UPDATE " . DB_PREFIX . "customer SET address_id = '" . (int)$address_id . "' WHERE customer_id = '" . (int)$data['customer_id'] . "'");
		}
		return $address_id;
	}
	private function getAddressapi($address_id,$cust_id) {
		$address_query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "address WHERE address_id = '" . (int)$address_id . "' AND customer_id = '" . (int)$cust_id . "'");

		if ($address_query->num_rows) {
			$country_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE country_id = '" . (int)$address_query->row['country_id'] . "'");

			if ($country_query->num_rows) {
				$country = $country_query->row['name'];
				$iso_code_2 = $country_query->row['iso_code_2'];
				$iso_code_3 = $country_query->row['iso_code_3'];
				$address_format = $country_query->row['address_format'];
			} else {
				$country = '';
				$iso_code_2 = '';
				$iso_code_3 = '';
				$address_format = '';
			}

			$zone_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE zone_id = '" . (int)$address_query->row['zone_id'] . "'");

			if ($zone_query->num_rows) {
				$zone = $zone_query->row['name'];
				$zone_code = $zone_query->row['code'];
			} else {
				$zone = '';
				$zone_code = '';
			}

			$address_data = array(
				'address_id'     => $address_query->row['address_id'],
				'firstname'      => $address_query->row['firstname'],
				'lastname'       => $address_query->row['lastname'],
				'company'        => $address_query->row['company'],
				'address_1'      => $address_query->row['address_1'],
				'address_2'      => $address_query->row['address_2'],
				'postcode'       => $address_query->row['postcode'],
				'city'           => $address_query->row['city'],
				'zone_id'        => $address_query->row['zone_id'],
				'zone'           => $zone,
				'zone_code'      => $zone_code,
				'country_id'     => $address_query->row['country_id'],
				'country'        => $country,
				'iso_code_2'     => $iso_code_2,
				'iso_code_3'     => $iso_code_3,
				'address_format' => $address_format,
				'custom_field'   => unserialize($address_query->row['custom_field'])
			);

			return $address_data;
		} else {
			return false;
		}
	}
	private function editAddressapi($data) {

		$this->db->query("UPDATE " . DB_PREFIX . "address SET firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', company = '" . $this->db->escape($data['company']) . "', address_1 = '" . $this->db->escape($data['address_1']) . "', address_2 = '" . $this->db->escape($data['address_2']) . "', postcode = '" . $this->db->escape($data['postcode']) . "', city = '" . $this->db->escape($data['city']) . "', zone_id = '" . (int)$data['zone_id'] . "', country_id = '" . (int)$data['country_id'] . "', custom_field = '" . $this->db->escape(isset($data['custom_field']) ? serialize($data['custom_field']) : '') . "' WHERE address_id  = '" . (int)$data['address_id'] . "' AND customer_id = '" . (int)$data['customer_id'] . "'");

		if (($data['set_default'] == 1)) {
			$this->db->query("UPDATE " . DB_PREFIX . "customer SET address_id = '" . (int)$data['address_id'] . "' WHERE customer_id = '" . (int)$data['customer_id'] . "'");
		}
		return true;

	}
	private function deleteAddressapi($address_id,$cust_id) {
		$this->event->trigger('pre.customer.delete.address', $address_id);

		$this->db->query("DELETE FROM " . DB_PREFIX . "address WHERE address_id = '" . (int)$address_id . "' AND customer_id = '" . (int)$cust_id . "'");
		return true;
	}
	private function getSettingsapi($store_id = 0) {
		$data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "setting WHERE store_id = '" . (int)$store_id . "'");

		foreach ($query->rows as $result) {
			if (!$result['serialized']) {
				$data[$result['key']] = $result['value'];
			} else {
				$data[$result['key']] = unserialize($result['value']);
			}
		}

		return $data;
	}
	public function getModuleapi($mod_name,$code) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "module WHERE name = '" . $mod_name . "' and code = '" . $code . "' ");
		
		if ($query->row) {
			return unserialize($query->row['setting']);
		} else {
			return array();	
		}
	}
	public function getLanguageapi($code) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "language WHERE code = '" . $code . "'");

		return $query->row;
	}
	
	private function getBannerapi($bannername) {
		
		$query1 = $this->db->query("SELECT * FROM " . DB_PREFIX . "banner WHERE name = '" . $bannername . "' ");
		$ids = 0;
		if(!empty($query1->rows)){
		$ids = $query1->rows[0]['banner_id'];
		}
		
		if($ids){
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "banner_image bi LEFT JOIN " . DB_PREFIX . "banner_image_description bid ON (bi.banner_image_id  = bid.banner_image_id) WHERE bi.banner_id = '" . $ids . "' AND bid.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY bi.sort_order ASC");
		return $query->rows;
		}
	}
	public function forgotten() {
		$this->checkPlugin();
		$this->load->language('mail/forgotten');
		$this->load->model('account/customer');
		$results = array();
		$fl = 1;
		if (!isset($this->request->post['email'])) {
			$results = $this->language->get('error_email');
			$fl = 0;
		} elseif (!$this->model_account_customer->getTotalCustomersByEmail($this->request->post['email'])) {
			$fl = 0;
			$results = 'This Email Not Register';
		}
		
		if ($fl == 1) {			

			$password = substr(sha1(uniqid(mt_rand(), true)), 0, 10);

			$this->model_account_customer->editPassword($this->request->post['email'], $password);

			$subject = sprintf($this->language->get('text_subject'), $this->config->get('config_name'));

			$message  = sprintf($this->language->get('text_greeting'), $this->config->get('config_name')) . "\n\n";
			$message .= $this->language->get('text_password') . "\n\n";
			$message .= $password;

			$mail = new Mail($this->config->get('config_mail'));
			$mail->setTo($this->request->post['email']);
			$mail->setFrom($this->config->get('config_email'));
			$mail->setSender($this->config->get('config_name'));
			$mail->setSubject($subject);
			$mail->setText(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));
			$mail->send();

			$results = 'Success forgot password check Email Address';

			// Add to activity log
			$customer_info = $this->model_account_customer->getCustomerByEmail($this->request->post['email']);

			if ($customer_info) {
				$this->load->model('account/activity');

				$activity_data = array(
					'customer_id' => $customer_info['customer_id'],
					'name'        => $customer_info['firstname'] . ' ' . $customer_info['lastname']
				);

				$this->model_account_activity->addActivity('forgotten', $activity_data);
			}
		}
		if(count($results)){
			$json['success'] 	= true;
			$json['forgetpsw'] 	= $results;
		}else {
			$json['success'] 	= false;
		}
		
		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';

		} else {
			$this->response->setOutput(json_encode($json));
		}
	}
	public function wishlist() {
		$this->checkPlugin();
		$this->load->model('account/customer');
		$results = array();
		$fl = 1;
		if (isset($this->request->post['customer_id']) && $this->request->post['customer_id'] != '') {
			$customer_info = $this->model_account_customer->getCustomer($this->request->post['customer_id']);
			if(!empty($customer_info)){
				if ($customer_info['wishlist'] && is_string($customer_info['wishlist'])) {
					
					$wishlist = unserialize($customer_info['wishlist']);
					if(!empty($wishlist)){
						$this->load->model('catalog/product');
						foreach($wishlist as $wish_id){
							$results[] = $this->model_catalog_product->getProduct($wish_id);
						}
					}
					
				}else{
						$results = 'Wishlist Empty';
					}
			}else{
			$results = 'Customer Not Available';
			
		}
		if(count($results)){
			$json['success'] 	= true;
			$json['wishlist'] 	= $results;
		}else {
			$json['success'] 	= false;
		}
		
		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';

		} else {
			$this->response->setOutput(json_encode($json));
		}
	}
	}

	public function addcart() {
		$this->load->language('checkout/cart');
		if (isset($this->request->post['customer_id'])) {
			$customer_id = (int)$this->request->post['customer_id'];
		} else {
			$customer_id = 0;
		}
		
		if (isset($this->request->post['product_id'])) {
			$product_id = (int)$this->request->post['product_id'];
		} else {
			$product_id = 0;
		}
		$this->load->model('catalog/product');

		$product_info = $this->model_catalog_product->getProduct($product_id);
		if ($product_info) {
			if (isset($this->request->post['quantity'])) {
				$quantity = (int)$this->request->post['quantity'];
			} else {
				$quantity = 1;
			}

			if (isset($this->request->post['option']) && $this->request->post['option'] != '') {
				$option = array_filter($this->request->post['option']);
			} else {
				$option = array();
			}
			
			$results = $this->cartnewadd($this->request->post['product_id'], $this->request->post['quantity'], $option, 0,$customer_id);
				
	}else{$results = 'Product Not Exist';}
	
		if(count($results)){
			$json['success'] 	= true;
			$json['cartproduct'] 	= $results;
		}else {
			$json['success'] 	= false;
		}
		
		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';

		} else {
			$this->response->setOutput(json_encode($json));
		}
	}
	public function removecart() {
		$this->load->language('checkout/cart');
		if (isset($this->request->post['customer_id'])) {
			$customer_id = (int)$this->request->post['customer_id'];
		} else {
			$customer_id = 0;
		}
		
		if (isset($this->request->post['key'])) {
			$key = $this->request->post['key'];
		} else {
			$key = 0;
		}
		$results = 'Cart Empty';
		$this->load->model('account/customer');
		$customer_info = $this->model_account_customer->getCustomer($customer_id);
		$cart = unserialize($customer_info['cart']);
		
		unset($cart[$key]);
		if($this->db->query("UPDATE " . DB_PREFIX . "customer SET cart = '" . serialize($cart) . "' WHERE customer_id = '" . (int)$customer_id . "'")){
						if (!empty($cart) && $cart != '') {
							$results = $this->apigetProducts($cart);
						}
					}
		
	
		if(count($results)){
			$json['success'] 	= true;
			$json['cartproduct'] 	= $results;
		}else {
			$json['success'] 	= false;
		}
		
		if ($this->debugIt) {
			echo '<pre>';
			print_r($json);
			echo '</pre>';

		} else {
			$this->response->setOutput(json_encode($json));
		}
	}
	private function apigetProducts($cartdata) {
		$cartprod = array();
		if (!$cartprod) {
			foreach ($cartdata as $key => $quantity) {
				$product = unserialize(base64_decode($key));

				$product_id = $product['product_id'];

				$stock = true;

				// Options
				if (!empty($product['option'])) {
					$options = $product['option'];
				} else {
					$options = array();
				}

				// Profile
				if (!empty($product['recurring_id'])) {
					$recurring_id = $product['recurring_id'];
				} else {
					$recurring_id = 0;
				}

				$product_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) WHERE p.product_id = '" . (int)$product_id . "' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.date_available <= NOW() AND p.status = '1'");

				if ($product_query->num_rows) {
					$option_price = 0;
					$option_points = 0;
					$option_weight = 0;

					$option_data = array();

					foreach ($options as $product_option_id => $value) {
						$option_query = $this->db->query("SELECT po.product_option_id, po.option_id, od.name, o.type FROM " . DB_PREFIX . "product_option po LEFT JOIN `" . DB_PREFIX . "option` o ON (po.option_id = o.option_id) LEFT JOIN " . DB_PREFIX . "option_description od ON (o.option_id = od.option_id) WHERE po.product_option_id = '" . (int)$product_option_id . "' AND po.product_id = '" . (int)$product_id . "' AND od.language_id = '" . (int)$this->config->get('config_language_id') . "'");

						if ($option_query->num_rows) {
							if ($option_query->row['type'] == 'select' || $option_query->row['type'] == 'radio' || $option_query->row['type'] == 'image') {
								$option_value_query = $this->db->query("SELECT pov.option_value_id, ovd.name, pov.quantity, pov.subtract, pov.price, pov.price_prefix, pov.points, pov.points_prefix, pov.weight, pov.weight_prefix FROM " . DB_PREFIX . "product_option_value pov LEFT JOIN " . DB_PREFIX . "option_value ov ON (pov.option_value_id = ov.option_value_id) LEFT JOIN " . DB_PREFIX . "option_value_description ovd ON (ov.option_value_id = ovd.option_value_id) WHERE pov.product_option_value_id = '" . (int)$value . "' AND pov.product_option_id = '" . (int)$product_option_id . "' AND ovd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

								if ($option_value_query->num_rows) {
									if ($option_value_query->row['price_prefix'] == '+') {
										$option_price += $option_value_query->row['price'];
									} elseif ($option_value_query->row['price_prefix'] == '-') {
										$option_price -= $option_value_query->row['price'];
									}

									if ($option_value_query->row['points_prefix'] == '+') {
										$option_points += $option_value_query->row['points'];
									} elseif ($option_value_query->row['points_prefix'] == '-') {
										$option_points -= $option_value_query->row['points'];
									}

									if ($option_value_query->row['weight_prefix'] == '+') {
										$option_weight += $option_value_query->row['weight'];
									} elseif ($option_value_query->row['weight_prefix'] == '-') {
										$option_weight -= $option_value_query->row['weight'];
									}

									if ($option_value_query->row['subtract'] && (!$option_value_query->row['quantity'] || ($option_value_query->row['quantity'] < $quantity))) {
										$stock = false;
									}

									$option_data[] = array(
										'product_option_id'       => $product_option_id,
										'product_option_value_id' => $value,
										'option_id'               => $option_query->row['option_id'],
										'option_value_id'         => $option_value_query->row['option_value_id'],
										'name'                    => $option_query->row['name'],
										'value'                   => $option_value_query->row['name'],
										'type'                    => $option_query->row['type'],
										'quantity'                => $option_value_query->row['quantity'],
										'subtract'                => $option_value_query->row['subtract'],
										'price'                   => $option_value_query->row['price'],
										'price_prefix'            => $option_value_query->row['price_prefix'],
										'points'                  => $option_value_query->row['points'],
										'points_prefix'           => $option_value_query->row['points_prefix'],
										'weight'                  => $option_value_query->row['weight'],
										'weight_prefix'           => $option_value_query->row['weight_prefix']
									);
								}
							} elseif ($option_query->row['type'] == 'checkbox' && is_array($value)) {
								foreach ($value as $product_option_value_id) {
									$option_value_query = $this->db->query("SELECT pov.option_value_id, ovd.name, pov.quantity, pov.subtract, pov.price, pov.price_prefix, pov.points, pov.points_prefix, pov.weight, pov.weight_prefix FROM " . DB_PREFIX . "product_option_value pov LEFT JOIN " . DB_PREFIX . "option_value ov ON (pov.option_value_id = ov.option_value_id) LEFT JOIN " . DB_PREFIX . "option_value_description ovd ON (ov.option_value_id = ovd.option_value_id) WHERE pov.product_option_value_id = '" . (int)$product_option_value_id . "' AND pov.product_option_id = '" . (int)$product_option_id . "' AND ovd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

									if ($option_value_query->num_rows) {
										if ($option_value_query->row['price_prefix'] == '+') {
											$option_price += $option_value_query->row['price'];
										} elseif ($option_value_query->row['price_prefix'] == '-') {
											$option_price -= $option_value_query->row['price'];
										}

										if ($option_value_query->row['points_prefix'] == '+') {
											$option_points += $option_value_query->row['points'];
										} elseif ($option_value_query->row['points_prefix'] == '-') {
											$option_points -= $option_value_query->row['points'];
										}

										if ($option_value_query->row['weight_prefix'] == '+') {
											$option_weight += $option_value_query->row['weight'];
										} elseif ($option_value_query->row['weight_prefix'] == '-') {
											$option_weight -= $option_value_query->row['weight'];
										}

										if ($option_value_query->row['subtract'] && (!$option_value_query->row['quantity'] || ($option_value_query->row['quantity'] < $quantity))) {
											$stock = false;
										}

										$option_data[] = array(
											'product_option_id'       => $product_option_id,
											'product_option_value_id' => $product_option_value_id,
											'option_id'               => $option_query->row['option_id'],
											'option_value_id'         => $option_value_query->row['option_value_id'],
											'name'                    => $option_query->row['name'],
											'value'                   => $option_value_query->row['name'],
											'type'                    => $option_query->row['type'],
											'quantity'                => $option_value_query->row['quantity'],
											'subtract'                => $option_value_query->row['subtract'],
											'price'                   => $option_value_query->row['price'],
											'price_prefix'            => $option_value_query->row['price_prefix'],
											'points'                  => $option_value_query->row['points'],
											'points_prefix'           => $option_value_query->row['points_prefix'],
											'weight'                  => $option_value_query->row['weight'],
											'weight_prefix'           => $option_value_query->row['weight_prefix']
										);
									}
								}
							} elseif ($option_query->row['type'] == 'text' || $option_query->row['type'] == 'textarea' || $option_query->row['type'] == 'file' || $option_query->row['type'] == 'date' || $option_query->row['type'] == 'datetime' || $option_query->row['type'] == 'time') {
								$option_data[] = array(
									'product_option_id'       => $product_option_id,
									'product_option_value_id' => '',
									'option_id'               => $option_query->row['option_id'],
									'option_value_id'         => '',
									'name'                    => $option_query->row['name'],
									'value'                   => $value,
									'type'                    => $option_query->row['type'],
									'quantity'                => '',
									'subtract'                => '',
									'price'                   => '',
									'price_prefix'            => '',
									'points'                  => '',
									'points_prefix'           => '',
									'weight'                  => '',
									'weight_prefix'           => ''
								);
							}
						}
					}

					$price = $product_query->row['price'];

					// Product Discounts
					$discount_quantity = 0;

					foreach ($this->session->data['cart'] as $key_2 => $quantity_2) {
						$product_2 = (array)unserialize(base64_decode($key_2));

						if ($product_2['product_id'] == $product_id) {
							$discount_quantity += $quantity_2;
						}
					}

					$product_discount_query = $this->db->query("SELECT price FROM " . DB_PREFIX . "product_discount WHERE product_id = '" . (int)$product_id . "' AND customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND quantity <= '" . (int)$discount_quantity . "' AND ((date_start = '0000-00-00' OR date_start < NOW()) AND (date_end = '0000-00-00' OR date_end > NOW())) ORDER BY quantity DESC, priority ASC, price ASC LIMIT 1");

					if ($product_discount_query->num_rows) {
						$price = $product_discount_query->row['price'];
					}

					// Product Specials
					$product_special_query = $this->db->query("SELECT price FROM " . DB_PREFIX . "product_special WHERE product_id = '" . (int)$product_id . "' AND customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND ((date_start = '0000-00-00' OR date_start < NOW()) AND (date_end = '0000-00-00' OR date_end > NOW())) ORDER BY priority ASC, price ASC LIMIT 1");

					if ($product_special_query->num_rows) {
						$price = $product_special_query->row['price'];
					}

					// Reward Points
					$product_reward_query = $this->db->query("SELECT points FROM " . DB_PREFIX . "product_reward WHERE product_id = '" . (int)$product_id . "' AND customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "'");

					if ($product_reward_query->num_rows) {
						$reward = $product_reward_query->row['points'];
					} else {
						$reward = 0;
					}

					// Downloads
					$download_data = array();

					$download_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_download p2d LEFT JOIN " . DB_PREFIX . "download d ON (p2d.download_id = d.download_id) LEFT JOIN " . DB_PREFIX . "download_description dd ON (d.download_id = dd.download_id) WHERE p2d.product_id = '" . (int)$product_id . "' AND dd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

					foreach ($download_query->rows as $download) {
						$download_data[] = array(
							'download_id' => $download['download_id'],
							'name'        => $download['name'],
							'filename'    => $download['filename'],
							'mask'        => $download['mask']
						);
					}

					// Stock
					if (!$product_query->row['quantity'] || ($product_query->row['quantity'] < $quantity)) {
						$stock = false;
					}

					$recurring_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "recurring` `p` JOIN `" . DB_PREFIX . "product_recurring` `pp` ON `pp`.`recurring_id` = `p`.`recurring_id` AND `pp`.`product_id` = " . (int)$product_query->row['product_id'] . " JOIN `" . DB_PREFIX . "recurring_description` `pd` ON `pd`.`recurring_id` = `p`.`recurring_id` AND `pd`.`language_id` = " . (int)$this->config->get('config_language_id') . " WHERE `pp`.`recurring_id` = " . (int)$recurring_id . " AND `status` = 1 AND `pp`.`customer_group_id` = " . (int)$this->config->get('config_customer_group_id'));

					if ($recurring_query->num_rows) {
						$recurring = array(
							'recurring_id'      => $recurring_id,
							'name'            => $recurring_query->row['name'],
							'frequency'       => $recurring_query->row['frequency'],
							'price'           => $recurring_query->row['price'],
							'cycle'           => $recurring_query->row['cycle'],
							'duration'        => $recurring_query->row['duration'],
							'trial'           => $recurring_query->row['trial_status'],
							'trial_frequency' => $recurring_query->row['trial_frequency'],
							'trial_price'     => $recurring_query->row['trial_price'],
							'trial_cycle'     => $recurring_query->row['trial_cycle'],
							'trial_duration'  => $recurring_query->row['trial_duration']
						);
					} else {
						$recurring = false;
					}

					$cartprod[$key] = array(
						'key'             => $key,
						'product_id'      => $product_query->row['product_id'],
						'name'            => $product_query->row['name'],
						'model'           => $product_query->row['model'],
						'shipping'        => $product_query->row['shipping'],
						'image'           => $product_query->row['image'],
						'option'          => $option_data,
						'download'        => $download_data,
						'quantity'        => $quantity,
						'minimum'         => $product_query->row['minimum'],
						'subtract'        => $product_query->row['subtract'],
						'stock'           => $stock,
						'price'           => ($price + $option_price),
						'total'           => ($price + $option_price) * $quantity,
						'reward'          => $reward * $quantity,
						'points'          => ($product_query->row['points'] ? ($product_query->row['points'] + $option_points) * $quantity : 0),
						'tax_class_id'    => $product_query->row['tax_class_id'],
						'weight'          => ($product_query->row['weight'] + $option_weight) * $quantity,
						'weight_class_id' => $product_query->row['weight_class_id'],
						'length'          => $product_query->row['length'],
						'width'           => $product_query->row['width'],
						'height'          => $product_query->row['height'],
						'length_class_id' => $product_query->row['length_class_id'],
						'recurring'       => $recurring
					);
				} else {
					$this->remove($key);
				}
			}
		}

		return $cartprod;
	}
	private function cartnewadd($product_id, $qty = 1, $option = array(), $recurring_id = 0,$customer_id) {
		$this->data = array();

		$product['product_id'] = (int)$product_id;

		if ($option) {
			$product['option'] = $option;
		}

		if ($recurring_id) {
			$product['recurring_id'] = (int)$recurring_id;
		}
		$key = base64_encode(serialize($product));
		$this->load->model('account/customer');
		$customer_info = $this->model_account_customer->getCustomer($customer_id);
		$cart = unserialize($customer_info['cart']);
		if ((int)$qty && ((int)$qty > 0)) {
			if (!isset($cart[$key])) {
				$cart[$key] = (int)$qty;
			} else {
				$cart[$key] += (int)$qty;
			}
			if($this->db->query("UPDATE " . DB_PREFIX . "customer SET cart = '" . serialize($cart) . "' WHERE customer_id = '" . (int)$customer_id . "'")){
						if (!empty($cart) && $cart != '') {
							return $this->apigetProducts($cart);
						}
					}
		}
		
	}
	private function getTaxClassapi($tax_class_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "tax_class WHERE tax_class_id = '" . (int)$tax_class_id . "'");

		return $query->row;
	}
	private function getTaxRulesapi($tax_class_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "tax_rule WHERE tax_class_id = '" . (int)$tax_class_id . "'");

		return $query->rows;
	}
	private function getTaxRateapi($tax_rate_id) {
		$query = $this->db->query("SELECT tr.tax_rate_id, tr.name AS name, tr.rate, tr.type, tr.geo_zone_id, gz.name AS geo_zone, tr.date_added, tr.date_modified FROM " . DB_PREFIX . "tax_rate tr LEFT JOIN " . DB_PREFIX . "geo_zone gz ON (tr.geo_zone_id = gz.geo_zone_id) WHERE tr.tax_rate_id = '" . (int)$tax_rate_id . "'");

		return $query->row;
	}
	private function getAddressidapi($address_id) {
		$address_query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "address WHERE address_id = '" . (int)$address_id."'" );

		if ($address_query->num_rows) {
			$country_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE country_id = '" . (int)$address_query->row['country_id'] . "'");

			if ($country_query->num_rows) {
				$country = $country_query->row['name'];
				$iso_code_2 = $country_query->row['iso_code_2'];
				$iso_code_3 = $country_query->row['iso_code_3'];
				$address_format = $country_query->row['address_format'];
			} else {
				$country = '';
				$iso_code_2 = '';
				$iso_code_3 = '';
				$address_format = '';
			}

			$zone_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE zone_id = '" . (int)$address_query->row['zone_id'] . "'");

			if ($zone_query->num_rows) {
				$zone = $zone_query->row['name'];
				$zone_code = $zone_query->row['code'];
			} else {
				$zone = '';
				$zone_code = '';
			}

			$address_data = array(
				'address_id'     => $address_query->row['address_id'],
				'firstname'      => $address_query->row['firstname'],
				'lastname'       => $address_query->row['lastname'],
				'company'        => $address_query->row['company'],
				'address_1'      => $address_query->row['address_1'],
				'address_2'      => $address_query->row['address_2'],
				'postcode'       => $address_query->row['postcode'],
				'city'           => $address_query->row['city'],
				'zone_id'        => $address_query->row['zone_id'],
				'zone'           => $zone,
				'zone_code'      => $zone_code,
				'country_id'     => $address_query->row['country_id'],
				'country'        => $country,
				'iso_code_2'     => $iso_code_2,
				'iso_code_3'     => $iso_code_3,
				'address_format' => $address_format,
				'custom_field'   => unserialize($address_query->row['custom_field'])
			);

			return $address_data;
		} else {
			return false;
		}
	}
	
	public function getOrderidapi($order_id) {
		$order_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order` WHERE order_id = '" . (int)$order_id . "' AND order_status_id > '0'");

		if ($order_query->num_rows) {
			$country_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE country_id = '" . (int)$order_query->row['payment_country_id'] . "'");

			if ($country_query->num_rows) {
				$payment_iso_code_2 = $country_query->row['iso_code_2'];
				$payment_iso_code_3 = $country_query->row['iso_code_3'];
			} else {
				$payment_iso_code_2 = '';
				$payment_iso_code_3 = '';
			}

			$zone_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE zone_id = '" . (int)$order_query->row['payment_zone_id'] . "'");

			if ($zone_query->num_rows) {
				$payment_zone_code = $zone_query->row['code'];
			} else {
				$payment_zone_code = '';
			}

			$country_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE country_id = '" . (int)$order_query->row['shipping_country_id'] . "'");

			if ($country_query->num_rows) {
				$shipping_iso_code_2 = $country_query->row['iso_code_2'];
				$shipping_iso_code_3 = $country_query->row['iso_code_3'];
			} else {
				$shipping_iso_code_2 = '';
				$shipping_iso_code_3 = '';
			}

			$zone_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE zone_id = '" . (int)$order_query->row['shipping_zone_id'] . "'");

			if ($zone_query->num_rows) {
				$shipping_zone_code = $zone_query->row['code'];
			} else {
				$shipping_zone_code = '';
			}

			return array(
				'order_id'                => $order_query->row['order_id'],
				'invoice_no'              => $order_query->row['invoice_no'],
				'invoice_prefix'          => $order_query->row['invoice_prefix'],
				'store_id'                => $order_query->row['store_id'],
				'store_name'              => $order_query->row['store_name'],
				'store_url'               => $order_query->row['store_url'],
				'customer_id'             => $order_query->row['customer_id'],
				'firstname'               => $order_query->row['firstname'],
				'lastname'                => $order_query->row['lastname'],
				'telephone'               => $order_query->row['telephone'],
				'fax'                     => $order_query->row['fax'],
				'email'                   => $order_query->row['email'],
				'payment_firstname'       => $order_query->row['payment_firstname'],
				'payment_lastname'        => $order_query->row['payment_lastname'],
				'payment_company'         => $order_query->row['payment_company'],
				'payment_address_1'       => $order_query->row['payment_address_1'],
				'payment_address_2'       => $order_query->row['payment_address_2'],
				'payment_postcode'        => $order_query->row['payment_postcode'],
				'payment_city'            => $order_query->row['payment_city'],
				'payment_zone_id'         => $order_query->row['payment_zone_id'],
				'payment_zone'            => $order_query->row['payment_zone'],
				'payment_zone_code'       => $payment_zone_code,
				'payment_country_id'      => $order_query->row['payment_country_id'],
				'payment_country'         => $order_query->row['payment_country'],
				'payment_iso_code_2'      => $payment_iso_code_2,
				'payment_iso_code_3'      => $payment_iso_code_3,
				'payment_address_format'  => $order_query->row['payment_address_format'],
				'payment_method'          => $order_query->row['payment_method'],
				'shipping_firstname'      => $order_query->row['shipping_firstname'],
				'shipping_lastname'       => $order_query->row['shipping_lastname'],
				'shipping_company'        => $order_query->row['shipping_company'],
				'shipping_address_1'      => $order_query->row['shipping_address_1'],
				'shipping_address_2'      => $order_query->row['shipping_address_2'],
				'shipping_postcode'       => $order_query->row['shipping_postcode'],
				'shipping_city'           => $order_query->row['shipping_city'],
				'shipping_zone_id'        => $order_query->row['shipping_zone_id'],
				'shipping_zone'           => $order_query->row['shipping_zone'],
				'shipping_zone_code'      => $shipping_zone_code,
				'shipping_country_id'     => $order_query->row['shipping_country_id'],
				'shipping_country'        => $order_query->row['shipping_country'],
				'shipping_iso_code_2'     => $shipping_iso_code_2,
				'shipping_iso_code_3'     => $shipping_iso_code_3,
				'shipping_address_format' => $order_query->row['shipping_address_format'],
				'shipping_method'         => $order_query->row['shipping_method'],
				'comment'                 => $order_query->row['comment'],
				'total'                   => $order_query->row['total'],
				'order_status_id'         => $order_query->row['order_status_id'],
				'language_id'             => $order_query->row['language_id'],
				'currency_id'             => $order_query->row['currency_id'],
				'currency_code'           => $order_query->row['currency_code'],
				'currency_value'          => $order_query->row['currency_value'],
				'date_modified'           => $order_query->row['date_modified'],
				'date_added'              => $order_query->row['date_added'],
				'ip'                      => $order_query->row['ip']
			);
		} else {
			return false;
		}
	}
	/*public	function convertobjtoarray($obj) {
			if(!is_array($obj) && !is_object($obj)) return $obj;
		if(is_object($obj)) $obj = get_object_vars($obj);
        return array_map(__FUNCTION__, $obj);
		}*/
	private function convertobjtoarray($obj, &$arr){

    if(!is_object($obj) && !is_array($obj)){
        $arr = $obj;
        return $arr;
    }

    foreach ($obj as $key => $value)
    {
        if (!empty($value))
        {
            $arr[$key] = array();
            $this->convertobjtoarray($value, $arr[$key]);
        }
        else
        {
            $arr[$key] = $value;
        }
    }
    return $arr;
}
	public function getReturnsapi($customer_id) {
		/*if ($start < 0) {
			$start = 0;
		}

		if ($limit < 1) {
			$limit = 20;
		}*/

		$query = $this->db->query("SELECT r.return_id, r.order_id, r.firstname, r.lastname, rs.name as status, r.date_added FROM `" . DB_PREFIX . "return` r LEFT JOIN " . DB_PREFIX . "return_status rs ON (r.return_status_id = rs.return_status_id) WHERE r.customer_id = '" . $customer_id . "' AND rs.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY r.return_id DESC");

		return $query->rows;
	}
	public function getReturnapi($return_id,$customer_id) {
		$query = $this->db->query("SELECT r.return_id, r.order_id, r.firstname, r.lastname, r.email, r.telephone, r.product, r.model, r.quantity, r.opened, (SELECT rr.name FROM " . DB_PREFIX . "return_reason rr WHERE rr.return_reason_id = r.return_reason_id AND rr.language_id = '" . (int)$this->config->get('config_language_id') . "') AS reason, (SELECT ra.name FROM " . DB_PREFIX . "return_action ra WHERE ra.return_action_id = r.return_action_id AND ra.language_id = '" . (int)$this->config->get('config_language_id') . "') AS action, (SELECT rs.name FROM " . DB_PREFIX . "return_status rs WHERE rs.return_status_id = r.return_status_id AND rs.language_id = '" . (int)$this->config->get('config_language_id') . "') AS status, r.comment, r.date_ordered, r.date_added, r.date_modified FROM `" . DB_PREFIX . "return` r WHERE return_id = '" . (int)$return_id . "' AND customer_id = '" . $customer_id . "'");

		return $query->row;
	}


}
