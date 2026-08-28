<?php
class ModelExtensionModuleHbSeoSnippets extends Model {
	private function cleanText($value) {
		$value = (string)$value;

		for ($i = 0; $i < 2; $i++) {
			$value = html_entity_decode($value, ENT_QUOTES | ENT_HTML5, 'UTF-8');
		}

		$value = strip_tags($value);
		$value = str_replace(array("\xc2\xa0", '&nbsp;'), ' ', $value);

		return preg_replace('/\s+/u', ' ', trim($value));
	}

	private function jsonLdScript(array $data, $comment = '') {
		$json = json_encode(
			$data,
			JSON_UNESCAPED_SLASHES |
			JSON_UNESCAPED_UNICODE |
			JSON_HEX_TAG |
			JSON_HEX_AMP |
			JSON_HEX_APOS |
			JSON_HEX_QUOT
		);

		if ($json === false) {
			return '';
		}

		return ($comment ? '<!--' . $comment . '-->' : '') . '<script type="application/ld+json">' . $json . '</script>';
	}

	private function removeEmptyValues(array $data) {
		foreach ($data as $key => $value) {
			if (is_array($value)) {
				$value = $this->removeEmptyValues($value);
				$data[$key] = $value;
			}

			if ($value === '' || $value === null || $value === array()) {
				unset($data[$key]);
			}
		}

		return $data;
	}

	private function getStoreUrl() {
		$store_url = $this->config->get('config_ssl');

		if (!$store_url) {
			$store_url = $this->config->get('config_url');
		}

		if (!$store_url && defined('HTTPS_SERVER')) {
			$store_url = HTTPS_SERVER;
		}

		return rtrim((string)$store_url, '/') . '/';
	}

	private function getProductCanonicalUrl($product_id) {
		if ($this->config->get('hb_canonical_status')) {
			$this->load->model('extension/module/hb_canonical');

			return html_entity_decode(
				$this->model_extension_module_hb_canonical->getProductCanonicalUrl((int)$product_id),
				ENT_QUOTES | ENT_HTML5,
				'UTF-8'
			);
		}

		return html_entity_decode(
			$this->url->link('product/product', 'product_id=' . (int)$product_id),
			ENT_QUOTES | ENT_HTML5,
			'UTF-8'
		);
	}

	private function getCategoryCanonicalUrl($category_id, $page = 1) {
		if ($this->config->get('hb_canonical_status')) {
			$this->load->model('extension/module/hb_canonical');

			return html_entity_decode(
				$this->model_extension_module_hb_canonical->getCategoryCanonicalUrl((int)$category_id, (int)$page),
				ENT_QUOTES | ENT_HTML5,
				'UTF-8'
			);
		}

		$args = 'path=' . (int)$category_id;
		if ((int)$page > 1) {
			$args .= '&page=' . (int)$page;
		}

		return html_entity_decode(
			$this->url->link('product/category', $args),
			ENT_QUOTES | ENT_HTML5,
			'UTF-8'
		);
	}

	private function escapeMeta($value) {
		return htmlspecialchars($this->cleanText($value), ENT_QUOTES, 'UTF-8');
	}

	private function getProductAvailability(array $product_info) {
		$this->load->model('extension/module/product_availability');

		return $this->model_extension_module_product_availability->getSchemaAvailability($product_info);
	}

	private function getProductMpn(array $product_info) {
		$mpn = isset($product_info['mpn']) ? $this->cleanText($product_info['mpn']) : '';
		if ($mpn !== '') {
			return $mpn;
		}

		$model = isset($product_info['model']) ? $this->cleanText($product_info['model']) : '';
		$name = isset($product_info['name']) ? $this->cleanText($product_info['name']) : '';
		$manufacturer = isset($product_info['manufacturer']) ? $this->cleanText($product_info['manufacturer']) : '';
		$model_token = utf8_strtolower(preg_replace('/[^\p{L}\p{N}]+/u', '', $model));
		$name_token = utf8_strtolower(preg_replace('/[^\p{L}\p{N}]+/u', '', $name));

		// In this catalog the verified manufacturer model is embedded in the product name.
		return $manufacturer !== '' && strlen($model_token) >= 3 && strpos($name_token, $model_token) !== false ? $model : '';
	}

	private function isValidGtin($value) {
		$value = preg_replace('/\D+/', '', (string)$value);
		$length = strlen($value);

		if (!in_array($length, array(8, 12, 13, 14), true)) {
			return false;
		}

		$sum = 0;
		$weight = 3;
		for ($index = $length - 2; $index >= 0; $index--) {
			$sum += (int)$value[$index] * $weight;
			$weight = $weight === 3 ? 1 : 3;
		}

		return ((10 - ($sum % 10)) % 10) === (int)$value[$length - 1];
	}

	private function decodeJsonLdSnippet($snippet) {
		$snippet = html_entity_decode((string)$snippet, ENT_QUOTES | ENT_HTML5, 'UTF-8');

		if (preg_match('/<script[^>]*application\/ld\+json[^>]*>(.*?)<\/script>/is', $snippet, $matches)) {
			$snippet = $matches[1];
		}

		$data = json_decode(trim($snippet), true);

		return is_array($data) ? $data : array();
	}

	public function get_stock_status_id($product_id) {
		$query = $this->db->query("SELECT stock_status_id FROM ".DB_PREFIX."product WHERE product_id = '".(int)$product_id."'");
		if ($query->row) {
			return $query->row['stock_status_id'];
		}else {
			return '0';
		}
	}

	public function product_sd($product_info, $data) {
		$ldjson = '';

		if ($this->config->get('hb_snippets_prod_enable') || $this->config->get('hb_snippets_og_enable') || $this->config->get('hb_snippets_tc_enable')) {

			if (isset($this->session->data['currency'])) {
				$currencycode 			= $this->session->data['currency'];
			}else{
				$currencycode 			= $this->config->get('config_currency');
			}

				if ($this->config->get('hb_snippets_description') == 'description') {
					$description = $this->cleanText($data['description']);
				} else {
					$description = $this->cleanText($product_info['meta_description']);
				}

				if ($description === '') {
					$description = $this->cleanText($data['description']);
				}

			$product_id 	= $product_info['product_id'];
			$name  			= $product_info['name'];
			//$brand 			= $product_info['manufacturer'];
			$model 			= $product_info['model'];
			$url			= $this->getProductCanonicalUrl($product_id);
			$brand_name	= !empty($product_info['manufacturer']) ? $this->cleanText($product_info['manufacturer']) : $this->cleanText($this->config->get('hb_snippets_brand'));
			$availability	= $this->getProductAvailability($product_info);
			$review_count 	= $product_info['reviews'];

			if ((float)$product_info['special']) {
				$price = (float)$product_info['special'];
			}else{
				$price = (float)$product_info['price'];
			}

			$actual_price = (float)$product_info['price'];

			$formatted_price =  $this->currency->format($price, $currencycode);

			$currency_value = $this->currency->getValue($currencycode);
			$price 			= $price * $currency_value;
			$actual_price 	= $actual_price * $currency_value;

			if ($this->config->get('hb_snippets_incl_tax')) {
				$price 			= $this->tax->calculate($price, $product_info['tax_class_id'], $this->config->get('config_tax'));
				$actual_price 	= $this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax'));
			}

			$price = number_format($price, 2, '.', '');
			$actual_price = number_format($actual_price, 2, '.', '');

			if ($this->config->get('hb_snippets_prod_enable')) {
				$sku = !empty($product_info['sku']) ? $this->cleanText($product_info['sku']) : $this->cleanText($product_info['model']);
				$mpn = $this->getProductMpn($product_info);

				$product_images = array();
				if ($product_info['image']) {
					if (!empty($data['popup'])) {
						$product_images[] = html_entity_decode($data['popup'], ENT_QUOTES | ENT_HTML5, 'UTF-8');
					} else {
						$image_parts = array_map('rawurlencode', explode('/', str_replace('\\', '/', ltrim($product_info['image'], '/'))));
						$product_images[] = $this->getStoreUrl() . 'image/' . implode('/', $image_parts);
					}
				}

					if (!empty($data['images'])) {
						foreach ($data['images'] as $image) {
							if (!empty($image['popup'])) {
									$product_images[] = html_entity_decode($image['popup'], ENT_QUOTES | ENT_HTML5, 'UTF-8');
							}
						}
					}

					$product_images = array_values(array_unique(array_filter($product_images)));

				$price_date = '';

				if ($this->config->get('hb_snippets_pricevalid') && (float)$product_info['special']) {
					$pricedate_query = $this->db->query("SELECT date_end FROM `" . DB_PREFIX . "product_special` WHERE product_id = '" . (int)$product_id . "' AND customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND (date_start = '0000-00-00' OR date_start < NOW()) AND date_end != '0000-00-00' AND date_end > NOW() ORDER BY priority ASC, price ASC LIMIT 1");

					if ($pricedate_query->row) {
						$price_date = date('Y-m-d', strtotime($pricedate_query->row['date_end']));
					}
				}

				$review_data = array();
				$review_query = $this->db->query("SELECT * FROM `".DB_PREFIX."review` WHERE product_id = '".(int)$product_id."' AND status = 1");
				if ($review_query->rows) {
					$reviews = $review_query->rows;

					foreach ($reviews as $rev) {
						$reviewRating =  array(
							'@type'			=> 'Rating',
							'ratingValue'	=> $rev['rating'],
							'bestRating'	=> '5',
							'worstRating'	=>	'1'
						);

						$author = array(
							'@type'			=> 'Person',
							'name'			=> $rev['author'],
						);

						$review_data[] = array(
							'@type'			=> 	'Review',
							'reviewRating'	=> 	$reviewRating,
							'author'		=> 	$author,
								'reviewBody'	=> 	$this->cleanText($rev['text']),
							'datePublished'	=>	date('Y-m-d', strtotime($rev['date_added']))
						);
					}
				}

				$aggregateRating = array();

				if ($review_count > 0) {
					$aggregateRating = array(
						'@type'			=> 	'AggregateRating',
						'ratingValue'	=>	$data['rating'],
						'reviewCount'	=>	$review_count,
						'bestRating'	=> 	'5',
					);
				}

					$offers = array(
						'@type' 			=> 'Offer',
						'@id'				=> $url . '#offer',
						'url'				=> $url,
						'availability' 		=> $availability,
						'price'				=> $price,
						'priceCurrency'		=> $currencycode,
						'itemCondition'		=> 'https://schema.org/NewCondition',
						'seller'			=> array('@id' => $this->getStoreUrl() . '#organization'),
					);

					if ($this->config->get('hb_snippets_pricevalid') && $price_date && strtotime($price_date) > time()) {
						$offers['priceValidUntil'] = date('Y-m-d', strtotime($price_date));
					}

					$product_snippet = array(
						'@type'				=> 	'Product',
						'@id'				=>	$url . '#product',
						'url'				=>	$url,
						'sku'				=> 	$sku,
						'image'				=> 	$product_images,
							'name'				=> 	$this->cleanText($data['heading_title']),
						'description'		=> 	$description,
						'offers'			=> 	$offers,
					);

						if ($this->cleanText($brand_name) !== '') {
						$product_snippet['brand'] = array(
							'@type' => 'Brand',
							'name' => $this->cleanText($brand_name)
						);
					}

					if ($mpn !== '') {
						$product_snippet['mpn'] = $mpn;
					}

					foreach (array('upc', 'ean', 'jan', 'isbn') as $identifier_field) {
						if (empty($product_info[$identifier_field])) {
							continue;
						}

						$identifier = preg_replace('/\D+/', '', $product_info[$identifier_field]);
						$gtin_key = 'gtin' . strlen($identifier);

						if ($this->isValidGtin($identifier)) {
							$product_snippet[$gtin_key] = $identifier;
							break;
						}
					}

					if ($review_data) {
						$product_snippet['review'] = $review_data;
					}

					if ($aggregateRating) {
						$product_snippet['aggregateRating'] = $aggregateRating;
					}

					$product_snippet = $this->removeEmptyValues($product_snippet);

					$webpage_snippet = array(
						'@type' => 'WebPage',
						'@id' => $url . '#webpage',
						'url' => $url,
							'name' => $this->cleanText($data['heading_title']),
						'description' => $description,
						'inLanguage' => $this->config->get('config_language'),
						'isPartOf' => array('@id' => $this->getStoreUrl() . '#website'),
						'mainEntity' => array('@id' => $url . '#product'),
						'breadcrumb' => array('@id' => $url . '#breadcrumb')
					);

					$ldjson .= $this->jsonLdScript(array(
						'@context' => 'https://schema.org',
						'@graph' => array($this->removeEmptyValues($webpage_snippet), $product_snippet)
					), 'watchline product structured data');
				}

			//OPEN GRAPH
			if ($this->config->get('hb_snippets_og_enable')){
				$hb_snippets_ogp = $this->config->get('hb_snippets_ogp');
				if (strlen($hb_snippets_ogp) > 4){
					$hb_snippets_ogp = str_replace('{name}',$name,$hb_snippets_ogp);
					$hb_snippets_ogp = str_replace('{model}',$model,$hb_snippets_ogp);
					$hb_snippets_ogp = str_replace('{brand}',$brand_name,$hb_snippets_ogp);
					$hb_snippets_ogp = str_replace('{price}',$formatted_price,$hb_snippets_ogp);
				}else{
					$hb_snippets_ogp = $name;
				}

				if (strlen($this->config->get('hb_snippets_og_id')) > 5 ){
					$this->document->setOpengraph('fb:app_id', $this->config->get('hb_snippets_og_id'));
				}
					$this->document->setOpengraph('og:title', $this->escapeMeta($hb_snippets_ogp));
					$this->document->setOpengraph('og:type', 'product');
					$this->document->setOpengraph('og:site_name', $this->escapeMeta($this->config->get('config_name')));

				$this->load->model('tool/image');
				if ($product_info['image']) {
					$snippet_thumb = $this->model_tool_image->resize($product_info['image'], $this->config->get('hb_snippets_og_piw'), $this->config->get('hb_snippets_og_pih'));
						$this->document->setOpengraph('og:image', htmlspecialchars($snippet_thumb, ENT_QUOTES, 'UTF-8'));
					$this->document->setOpengraph('og:image:width', $this->config->get('hb_snippets_og_piw'));
					$this->document->setOpengraph('og:image:height', $this->config->get('hb_snippets_og_pih'));
				}

					$this->document->setOpengraph('og:url', htmlspecialchars($url, ENT_QUOTES, 'UTF-8'));
					$this->document->setOpengraph('og:description', $this->escapeMeta($description));

				/*if (!empty($data['images'])) {
					foreach ($data['images'] as $additional_image){
						$this->document->setOpengraph('og:image', $additional_image['popup']);
						$this->document->setOpengraph('og:image:width', $this->config->get('hb_snippets_og_piw'));
						$this->document->setOpengraph('og:image:height', $this->config->get('hb_snippets_og_pih'));
					}
				}*/

				if ((float)$product_info['special']) {
					$this->document->setOpengraph('product:sale_price:amount', $price);
					$this->document->setOpengraph('product:sale_price:currency', $currencycode);
					$this->document->setOpengraph('product:original_price:amount', $actual_price);
					$this->document->setOpengraph('product:original_price:currency', $currencycode);
				} else {
					$this->document->setOpengraph('product:original_price:amount', $price);
					$this->document->setOpengraph('product:original_price:currency', $currencycode);
				}

				$og_availability = array(
					'https://schema.org/InStock' => 'instock',
					'https://schema.org/LimitedAvailability' => 'instock',
					'https://schema.org/PreOrder' => 'preorder',
					'https://schema.org/BackOrder' => 'backorder'
				);
				$this->document->setOpengraph('og:availability', isset($og_availability[$availability]) ? $og_availability[$availability] : 'oos');

				if (!empty($data['products'])) {
					foreach ($data['products'] as $product){
							$this->document->setOpengraph('og:see_also', htmlspecialchars($product['href'], ENT_QUOTES, 'UTF-8'));
					}
				}
			}
			//TWITTER CARDS
			if ($this->config->get('hb_snippets_tc_enable')){
				$hb_snippets_tcp = $this->config->get('hb_snippets_tcp');
				if (strlen($hb_snippets_tcp) > 4){
					$hb_snippets_tcp = str_replace('{name}',$name,$hb_snippets_tcp);
					$hb_snippets_tcp = str_replace('{model}',$model,$hb_snippets_tcp);
					$hb_snippets_tcp = str_replace('{brand}',$brand_name,$hb_snippets_tcp);
					$hb_snippets_tcp = str_replace('{price}',$formatted_price,$hb_snippets_tcp);
				}else{
					$hb_snippets_tcp = $name;
				}

					$this->document->setTwittercard('twitter:card', 'summary_large_image');
					if ($this->config->get('hb_snippets_tc_username')) {
						$this->document->setTwittercard('twitter:site', $this->escapeMeta($this->config->get('hb_snippets_tc_username')));
					}
					$this->document->setTwittercard('twitter:title', $this->escapeMeta($hb_snippets_tcp));
					$this->document->setTwittercard('twitter:description', $this->escapeMeta($description));
					if ($product_info['image']) {
						$this->document->setTwittercard('twitter:image', htmlspecialchars($data['popup'], ENT_QUOTES, 'UTF-8'));
					}
			}
		}

		$this->document->setStructureddata($ldjson);
	}

	public function category_social($category_info){
		$this->load->model('tool/image');
		$page = !empty($this->request->get['page']) ? max(1, (int)$this->request->get['page']) : 1;
		if ($this->config->get('hb_snippets_og_enable')){
			$hb_snippets_ogc = $this->config->get('hb_snippets_ogc');
			if (strlen($hb_snippets_ogc) > 4){
				$ogc_name = $category_info['name'];
				$hb_snippets_ogc = str_replace('{name}',$ogc_name,$hb_snippets_ogc);
			}else{
				$hb_snippets_ogc = $category_info['name'];
			}

			if (strlen($this->config->get('hb_snippets_og_id')) > 5 ){
			    $this->document->setOpengraph('fb:app_id', $this->config->get('hb_snippets_og_id'));
			}
				$this->document->setOpengraph('og:title', $this->escapeMeta($hb_snippets_ogc));
	            $this->document->setOpengraph('og:type', 'product.group');
				$this->document->setOpengraph('og:site_name', $this->escapeMeta($this->config->get('config_name')));
				$this->document->setOpengraph('og:url', htmlspecialchars($this->getCategoryCanonicalUrl($category_info['category_id'], $page), ENT_QUOTES, 'UTF-8'));
			if ($category_info['image']) {
				$image = $this->model_tool_image->resize($category_info['image'], $this->config->get('hb_snippets_og_ciw'), $this->config->get('hb_snippets_og_cih'));
					$this->document->setOpengraph('og:image', htmlspecialchars($image, ENT_QUOTES, 'UTF-8'));
				$this->document->setOpengraph('og:image:width', $this->config->get('hb_snippets_og_ciw'));
				$this->document->setOpengraph('og:image:height', $this->config->get('hb_snippets_og_cih'));
			}
				$this->document->setOpengraph('og:description', $this->escapeMeta($category_info['meta_description']));
		}

		//TWITTER CARDS
		if ($this->config->get('hb_snippets_tc_enable')){
			$hb_snippets_tcc = $this->config->get('hb_snippets_tcc');
			if (strlen($hb_snippets_tcc) > 4){
				$tcc_name = $category_info['name'];
				$hb_snippets_tcc = str_replace('{name}',$tcc_name,$hb_snippets_tcc);
			}else{
				$hb_snippets_tcc = $category_info['name'];
			}

				$this->document->setTwittercard('twitter:card', 'summary_large_image');
				if ($this->config->get('hb_snippets_tc_username')) {
					$this->document->setTwittercard('twitter:site', $this->escapeMeta($this->config->get('hb_snippets_tc_username')));
				}
				$this->document->setTwittercard('twitter:title', $this->escapeMeta($hb_snippets_tcc));
				$this->document->setTwittercard('twitter:description', $this->escapeMeta($category_info['meta_description']));
			if ($category_info['image']) {
				$image = $this->model_tool_image->resize($category_info['image'], $this->config->get('hb_snippets_og_ciw'), $this->config->get('hb_snippets_og_cih'));
				    $this->document->setTwittercard('twitter:image', htmlspecialchars($image, ENT_QUOTES, 'UTF-8'));
			}
		}
	}

	public function information_social($information_info){
		if ($this->config->get('hb_snippets_og_enable')){
			if (strlen($this->config->get('hb_snippets_og_id')) > 5 ){
				$this->document->setOpengraph('fb:app_id', $this->config->get('hb_snippets_og_id'));
			}
				$this->document->setOpengraph('og:title', $this->escapeMeta($information_info['title']));
				$this->document->setOpengraph('og:type', 'website');
				$this->document->setOpengraph('og:site_name', $this->escapeMeta($this->config->get('config_name')));
				if ($this->config->get('hb_snippets_og_img')) {
					$this->document->setOpengraph('og:image', htmlspecialchars($this->getStoreUrl() . 'image/' . $this->config->get('hb_snippets_og_img'), ENT_QUOTES, 'UTF-8'));
				$this->document->setOpengraph('og:image:width', $this->config->get('hb_snippets_og_diw'));
				$this->document->setOpengraph('og:image:height', $this->config->get('hb_snippets_og_dih'));
			}
				$this->document->setOpengraph('og:url', htmlspecialchars($this->url->link('information/information', 'information_id=' .  $information_info['information_id']), ENT_QUOTES, 'UTF-8'));
				$this->document->setOpengraph('og:description', $this->escapeMeta($information_info['meta_description']));
		}

		//TWITTER CARDS
		if ($this->config->get('hb_snippets_tc_enable')){
				$this->document->setTwittercard('twitter:card', 'summary_large_image');
				if ($this->config->get('hb_snippets_tc_username')) {
					$this->document->setTwittercard('twitter:site', $this->escapeMeta($this->config->get('hb_snippets_tc_username')));
				}
				$this->document->setTwittercard('twitter:title', $this->escapeMeta($information_info['title']));
				$this->document->setTwittercard('twitter:description', $this->escapeMeta($information_info['meta_description']));
				if ($this->config->get('hb_snippets_og_img')) {
					$this->document->setTwittercard('twitter:image', htmlspecialchars($this->getStoreUrl() . 'image/' . $this->config->get('hb_snippets_og_img'), ENT_QUOTES, 'UTF-8'));
			}

		}
	}

	public function home_social(){
		$this->load->model('tool/image');
		if ($this->config->get('hb_snippets_og_enable')){
			if (strlen($this->config->get('hb_snippets_og_id')) > 5 ){
			        $this->document->setOpengraph('fb:app_id', $this->config->get('hb_snippets_og_id'));
			    }
					$this->document->setOpengraph('og:title', $this->escapeMeta($this->config->get('config_meta_title')));
					$this->document->setOpengraph('og:type', 'website');
					$this->document->setOpengraph('og:site_name', $this->escapeMeta($this->config->get('config_name')));
					if ($this->config->get('hb_snippets_og_img')) {
						$this->document->setOpengraph('og:image', htmlspecialchars($this->getStoreUrl() . 'image/' . $this->config->get('hb_snippets_og_img'), ENT_QUOTES, 'UTF-8'));
					$this->document->setOpengraph('og:image:width', $this->config->get('hb_snippets_og_diw'));
					$this->document->setOpengraph('og:image:height', $this->config->get('hb_snippets_og_dih'));
				}
					$this->document->setOpengraph('og:url', htmlspecialchars($this->getStoreUrl(), ENT_QUOTES, 'UTF-8'));
					$this->document->setOpengraph('og:description', $this->escapeMeta($this->config->get('config_meta_description')));
		}

		//TWITTER CARDS
		if ($this->config->get('hb_snippets_tc_enable')){
				$this->document->setTwittercard('twitter:card', 'summary_large_image');
				if ($this->config->get('hb_snippets_tc_username')) {
					$this->document->setTwittercard('twitter:site', $this->escapeMeta($this->config->get('hb_snippets_tc_username')));
				}
				$this->document->setTwittercard('twitter:title', $this->escapeMeta($this->config->get('config_meta_title')));
				$this->document->setTwittercard('twitter:description', $this->escapeMeta($this->config->get('config_meta_description')));
				if ($this->config->get('hb_snippets_og_img')) {
					$this->document->setTwittercard('twitter:image', htmlspecialchars($this->getStoreUrl() . 'image/' . $this->config->get('hb_snippets_og_img'), ENT_QUOTES, 'UTF-8'));
			}
		}
	}

	public function getProductCategory(int $product_id): array{
		$query = $this->db->query("SELECT c.category_id, c.parent_id FROM " . DB_PREFIX . "product_to_category p2c LEFT JOIN " . DB_PREFIX . "category c ON (p2c.category_id = c.category_id) WHERE product_id = '" . (int)$product_id . "' ORDER BY parent_id DESC LIMIT 1");
		if ($query->row){
			return $query->row;
		}else{
			return [];
		}
	}

	public function getParentCategory(int $category_id): int{
		$query = $this->db->query("SELECT parent_id FROM " . DB_PREFIX . "category WHERE category_id = '" . (int)$category_id . "' LIMIT 1");
		if ($query->row){
			return $query->row['parent_id'];
		}else{
			return '0';
		}
	}

	public function isCategoryActive(int $category_id): bool{
		$query = $this->db->query("SELECT count(*) as total FROM " . DB_PREFIX . "category WHERE category_id = '" . (int)$category_id . "' AND status = 1");
		if ($query->row['total'] > 0){
			return true;
		}else{
			return false;
		}
	 }

	public function breadcrumbs_sd($breadcrumbs, $options = []) {
		if ($this->config->get('hb_snippets_bc_enable')) {
			$ldjson = '';
			$itemlist = [];
			$i = 1;

			if ($this->config->get('hb_snippets_bc_type') == 'smart' && !empty($options)) {
				$type = isset($options['type']) ? $options['type'] : '';
				$id = isset($options['id']) ? (int)$options['id'] : 0;
				$title = isset($options['title']) ? $options['title'] : '';
				$breadcrumbs = array(
					array(
						'text' => $this->language->get('text_home'),
						'href' => $this->getStoreUrl()
					)
				);
				$canonical_path = false;

				$this->load->model('catalog/category');
				$this->load->model('extension/module/hb_canonical');

				if ($type === 'product' && $id > 0) {
					$canonical_path = $this->model_extension_module_hb_canonical->getProductCanonicalPath($id);
				} elseif ($type === 'category' && $id > 0) {
					$canonical_path = $this->model_extension_module_hb_canonical->getCategoryCanonicalPath($id);
				}

				if ($canonical_path) {
					foreach (explode('_', $canonical_path) as $category_id) {
						$category_id = (int)$category_id;
						$category_info = $this->model_catalog_category->getCategory($category_id);

						if ($category_info) {
							$breadcrumbs[] = array(
								'text' => $category_info['name'],
								'href' => $this->getCategoryCanonicalUrl($category_id)
							);
						}
					}
				}

				if ($type === 'product' && $id > 0) {
					$breadcrumbs[] = array(
						'text' => $title,
						'href' => $this->getProductCanonicalUrl($id)
					);
				} elseif ($type === 'category' && $id > 0 && count($breadcrumbs) === 1) {
					$breadcrumbs[] = array(
						'text' => $title,
						'href' => $this->getCategoryCanonicalUrl($id)
					);
				}
			}

					if (!empty($breadcrumbs)) {
						foreach ($breadcrumbs as $breadcrumb) {
							$breadcrumb_name = $this->cleanText($breadcrumb['text']);
							if ($breadcrumb_name === '' && $i === 1) {
								$breadcrumb_name = 'Naslovna';
							}

							if ($breadcrumb_name === '') {
								continue;
							}

							$itemlist[] = array(
								'@type'			=> 	'ListItem',
								'position'		=>  $i,
								'name'			=>  $breadcrumb_name,
							'item'			=>  html_entity_decode($breadcrumb['href'], ENT_QUOTES | ENT_HTML5, 'UTF-8')
						);

					$i++;
				}
			}

				$page_url = !empty($itemlist) ? $itemlist[count($itemlist) - 1]['item'] : $this->getStoreUrl();
				$breadcrumb_snippet = array(
					'@context' 			=> 	'https://schema.org',
					'@type'				=> 	'BreadcrumbList',
					'@id'				=>	$page_url . '#breadcrumb',
					'itemListElement'   =>	$itemlist
				);

				if ($itemlist) {
					$ldjson .= $this->jsonLdScript($breadcrumb_snippet, 'watchline breadcrumb structured data');
				}

		} else {
			$ldjson = '';
		}

		$this->document->setStructureddata($ldjson);
	}

	public function local_business() {
		$ldjson = '';

		if ($this->config->get('hb_snippets_local_enable')) {
			$store = $this->decodeJsonLdSnippet($this->config->get('hb_snippets_local_snippet'));

			if ($store) {
				$store_url = $this->getStoreUrl();
				$store['@context'] = 'https://schema.org';
				$store['@id'] = $store_url . '#store-zagreb';
				$store['name'] = 'Watch Line Zagreb';
				$store['url'] = $store_url;
				$store['parentOrganization'] = array('@id' => $store_url . '#organization');

				if (!empty($store['image']) && is_string($store['image'])) {
					$image_path = parse_url(html_entity_decode($store['image'], ENT_QUOTES | ENT_HTML5, 'UTF-8'), PHP_URL_PATH);

					if ($image_path && strpos($image_path, '/image/') !== false) {
						$store['image'] = rtrim($store_url, '/') . '/' . ltrim($image_path, '/');
					} elseif (strpos($store['image'], 'https://') !== 0) {
						unset($store['image']);
					}
				}

				if (!empty($store['address']) && is_array($store['address'])) {
					$address = $store['address'];
					$contained_in = isset($address['addressLocality']) ? $this->cleanText($address['addressLocality']) : '';

					if (!empty($address['streetAddress'])) {
						$address['streetAddress'] = preg_replace('/,\s*Zagreb\s*$/ui', '', $this->cleanText($address['streetAddress']));
					}

					$address['addressLocality'] = 'Zagreb';
					$address['addressRegion'] = 'Grad Zagreb';
					$address['addressCountry'] = 'HR';
					$store['address'] = $address;

					if ($contained_in && stripos($contained_in, 'Zagreb') === false) {
						$store['containedInPlace'] = array(
							'@type' => 'Place',
							'name' => $contained_in
						);
					}
				}

				$store = $this->removeEmptyValues($store);
				$ldjson = $this->jsonLdScript($store, 'watchline physical store structured data');
			}
		}

		$this->document->setStructureddata($ldjson);
	}

	public function knowledge_graph() {
		if ($this->config->get('hb_snippets_kg_enable')) {
			$store_url = $this->getStoreUrl();
			$contactPoint = [];
			$sameAs = [];
			if ($this->config->get('hb_snippets_contact')) {
				$contacts = $this->config->get('hb_snippets_contact');
				foreach ($contacts as $contact) {
					$contactPoint[] = array(
						'@type' 		=> 'ContactPoint',
						'telephone' 	=> $contact['n'],
						'contactType'	=> $contact['t']
					);
				}
			}

			if ($this->config->get('hb_snippets_socials')) {
				$socials = $this->config->get('hb_snippets_socials');
				foreach ($socials as $social) {
					$sameAs[] = $social;
				}
			}

			$home_snippet = array(
				'@context' => 'https://schema.org',
				'@type' => 'OnlineStore',
				'@id' => $store_url . '#organization',
				'name' => 'Watch Line',
				'legalName' => 'WATCH LINE AM d.o.o.',
				'alternateName' => $this->cleanText($this->config->get('config_name')),
				'url' => $store_url,
				'email' => $this->config->get('config_email'),
				'contactPoint' => $contactPoint,
				'sameAs' => $sameAs
			);

			if ($this->config->get('hb_snippets_logo')) {
				$logo = $store_url . 'image/' . ltrim($this->config->get('hb_snippets_logo'), '/');
				$home_snippet['logo'] = array(
					'@type' => 'ImageObject',
					'@id' => $store_url . '#logo',
					'url' => $logo,
					'contentUrl' => $logo
				);
			}

			$ldjson = '';
			$ldjson .= $this->jsonLdScript($this->removeEmptyValues($home_snippet), 'watchline organization structured data');

		} else {
			$ldjson = '';
		}

		$this->document->setStructureddata($ldjson);
	}

	public function site_search() {
		if ($this->config->get('hb_snippets_search_enable')) {
			$store_url = $this->getStoreUrl();
			$snippet = array(
				'@context' => 'https://schema.org',
				'@graph' => array(
					array(
						'@type' => 'WebSite',
						'@id' => $store_url . '#website',
						'url' => $store_url,
						'name' => 'Watch Line',
						'alternateName' => $this->cleanText($this->config->get('config_name')),
						'inLanguage' => $this->config->get('config_language'),
						'publisher' => array('@id' => $store_url . '#organization')
					),
					array(
						'@type' => 'WebPage',
						'@id' => $store_url . '#webpage',
						'url' => $store_url,
						'name' => $this->cleanText($this->config->get('config_meta_title')),
						'description' => $this->cleanText($this->config->get('config_meta_description')),
						'isPartOf' => array('@id' => $store_url . '#website'),
						'about' => array('@id' => $store_url . '#organization')
					)
				)
			);

			$ldjson = $this->jsonLdScript($this->removeEmptyValues($snippet), 'watchline website structured data');

		} else {
			$ldjson = '';
		}

		$this->document->setStructureddata($ldjson);
	}

	public function itemlist($products) {
		if ($this->config->get('hb_snippets_list_enable') && $products) {
			$ldjson = '';
			$itemlist = [];
			$canonical_urls = array();
			$page = !empty($this->request->get['page']) ? max(1, (int)$this->request->get['page']) : 1;
			$limit = !empty($this->request->get['limit'])
				? max(1, (int)$this->request->get['limit'])
				: max(1, (int)$this->config->get($this->config->get('config_theme') . '_product_limit'));
			if ($this->config->get('hb_canonical_status')) {
				$product_ids = array();

				foreach ($products as $product) {
					if (!empty($product['product_id'])) {
						$product_ids[] = (int)$product['product_id'];
					}
				}

				if ($product_ids) {
					$this->load->model('extension/module/hb_canonical');
					$canonical_urls = $this->model_extension_module_hb_canonical->getProductCanonicalUrls($product_ids);
				}
			}

			$i = (($page - 1) * $limit) + 1;
			foreach ($products as $product) {
				$product_id = !empty($product['product_id']) ? (int)$product['product_id'] : 0;
				$product_url = $product_id && isset($canonical_urls[$product_id])
					? $canonical_urls[$product_id]
					: (!empty($product['href']) ? $product['href'] : $this->getProductCanonicalUrl($product_id));

				$item = array(
					'@type'			=> 	'ListItem',
					'position'		=>  $i,
					'name'			=>  $this->cleanText($product['name']),
					'url'			=> 	html_entity_decode($product_url, ENT_QUOTES | ENT_HTML5, 'UTF-8')
				);

				if (!empty($product['thumb'])) {
					$item['image'] = html_entity_decode($product['thumb'], ENT_QUOTES | ENT_HTML5, 'UTF-8');
				}

				$itemlist[] = $item;

				$i++;
			}

			$list_url = $this->getStoreUrl();
			if (!empty($this->request->get['path'])) {
				$path_parts = explode('_', $this->request->get['path']);
				$list_url = $this->getCategoryCanonicalUrl((int)end($path_parts), $page);
			} elseif (!empty($this->request->get['manufacturer_id'])) {
				$args = 'manufacturer_id=' . (int)$this->request->get['manufacturer_id'];
				if ($page > 1) {
					$args .= '&page=' . $page;
				}
				$list_url = html_entity_decode($this->url->link('product/manufacturer/info', $args), ENT_QUOTES | ENT_HTML5, 'UTF-8');
			} elseif (isset($this->request->get['route']) && $this->request->get['route'] === 'product/special') {
				$list_url = html_entity_decode($this->url->link('product/special', $page > 1 ? 'page=' . $page : ''), ENT_QUOTES | ENT_HTML5, 'UTF-8');
			}
			$list_id = $list_url . '#itemlist';

			$itemlist_snippet = array(
				'@context' 			=> 	'https://schema.org',
				'@type'				=> 	'ItemList',
				'@id'				=>	$list_id,
				'numberOfItems'		=>	count($itemlist),
				'itemListElement'   =>	$itemlist
			);

			$ldjson .= $this->jsonLdScript($itemlist_snippet, 'watchline category item list structured data');

		} else {
			$ldjson = '';
		}

		$this->document->setStructureddata($ldjson);
	}

}
