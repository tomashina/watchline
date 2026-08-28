<?php
class ControllerExtensionFeedGoogleBase extends Controller {
	private function cdata($value) {
		$value = html_entity_decode((string)$value, ENT_QUOTES | ENT_HTML5, 'UTF-8');
		$value = preg_replace('/\s+/u', ' ', trim($value));

		return '<![CDATA[' . str_replace(']]>', ']]]]><![CDATA[>', $value) . ']]>';
	}

	private function xmlValue($value) {
		return htmlspecialchars(html_entity_decode((string)$value, ENT_QUOTES | ENT_HTML5, 'UTF-8'), ENT_XML1 | ENT_QUOTES, 'UTF-8');
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

	private function getProductMpn(array $product) {
		$mpn = !empty($product['mpn']) ? trim(html_entity_decode($product['mpn'], ENT_QUOTES | ENT_HTML5, 'UTF-8')) : '';
		if ($mpn !== '') {
			return $mpn;
		}

		$model = !empty($product['model']) ? trim(html_entity_decode($product['model'], ENT_QUOTES | ENT_HTML5, 'UTF-8')) : '';
		$name = !empty($product['name']) ? trim(html_entity_decode($product['name'], ENT_QUOTES | ENT_HTML5, 'UTF-8')) : '';
		$model_token = utf8_strtolower(preg_replace('/[^\p{L}\p{N}]+/u', '', $model));
		$name_token = utf8_strtolower(preg_replace('/[^\p{L}\p{N}]+/u', '', $name));

		return !empty($product['manufacturer']) && strlen($model_token) >= 3 && strpos($name_token, $model_token) !== false ? $model : '';
	}

	public function index() {
		if (!$this->config->get('google_base_status')) {
			return;
		}

		$this->load->model('extension/feed/google_base');
		$this->load->model('catalog/category');
		$this->load->model('catalog/product');
		$this->load->model('extension/module/product_availability');
		$this->load->model('tool/image');

		$mapped_products = array();
		$google_base_categories = $this->model_extension_feed_google_base->getCategories();

		foreach ($google_base_categories as $google_base_category) {
			$products = $this->model_catalog_product->getProducts(array(
				'filter_category_id' => $google_base_category['category_id'],
				'filter_filter' => false
			));

			foreach ($products as $product) {
				$product_id = (int)$product['product_id'];
				if (!isset($mapped_products[$product_id])) {
					$mapped_products[$product_id] = array(
						'product' => $product,
						'google_product_category' => $google_base_category['google_base_category_id']
					);
				}
			}
		}

		$product_urls = array();
		if ($this->config->get('hb_canonical_status') && $mapped_products) {
			$this->load->model('extension/module/hb_canonical');
			$product_urls = $this->model_extension_module_hb_canonical->getProductCanonicalUrls(array_keys($mapped_products));
		}

		$store_url = rtrim($this->config->get('config_ssl') ?: $this->config->get('config_url'), '/') . '/';
		$currency_code = $this->config->get('config_currency');
		if (!in_array($currency_code, array('USD', 'EUR', 'GBP'), true)) {
			$currency_code = 'EUR';
		}
		$currency_value = $this->currency->getValue($currency_code);

		$output = '<?xml version="1.0" encoding="UTF-8"?>';
		$output .= '<rss version="2.0" xmlns:g="http://base.google.com/ns/1.0"><channel>';
		$output .= '<title>' . $this->cdata($this->config->get('config_name')) . '</title>';
		$output .= '<description>' . $this->cdata($this->config->get('config_meta_description')) . '</description>';
		$output .= '<link>' . $this->xmlValue($store_url) . '</link>';

		foreach ($mapped_products as $product_id => $mapped_product) {
			$product = $mapped_product['product'];
			$product_url = isset($product_urls[$product_id])
				? html_entity_decode($product_urls[$product_id], ENT_QUOTES | ENT_HTML5, 'UTF-8')
				: html_entity_decode($this->url->link('product/product', 'product_id=' . $product_id), ENT_QUOTES | ENT_HTML5, 'UTF-8');
			$description = trim(strip_tags(html_entity_decode($product['description'], ENT_QUOTES | ENT_HTML5, 'UTF-8')));
			if ($description === '') {
				$description = trim(html_entity_decode($product['meta_description'], ENT_QUOTES | ENT_HTML5, 'UTF-8'));
			}
			if ($description === '') {
				$description = $product['name'];
			}

			$regular_price = $this->currency->format(
				$this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')),
				$currency_code,
				$currency_value,
				false
			);
			$gtin = '';
			$mpn = $this->getProductMpn($product);
			foreach (array('ean', 'upc', 'jan', 'isbn') as $field) {
				if (!empty($product[$field]) && $this->isValidGtin($product[$field])) {
					$gtin = preg_replace('/\D+/', '', $product[$field]);
					break;
				}
			}

			$output .= '<item>';
			$output .= '<g:id>' . $product_id . '</g:id>';
			$output .= '<title>' . $this->cdata($product['name']) . '</title>';
			$output .= '<link>' . $this->xmlValue($product_url) . '</link>';
			$output .= '<description>' . $this->cdata($description) . '</description>';
			if (!empty($product['manufacturer'])) {
				$output .= '<g:brand>' . $this->cdata($product['manufacturer']) . '</g:brand>';
			}
			$output .= '<g:condition>new</g:condition>';
			$output .= '<g:availability>' . $this->model_extension_module_product_availability->getFeedAvailability($product) . '</g:availability>';
			$output .= '<g:price>' . $this->xmlValue($regular_price . ' ' . $currency_code) . '</g:price>';

			if ((float)$product['special']) {
				$sale_price = $this->currency->format(
					$this->tax->calculate($product['special'], $product['tax_class_id'], $this->config->get('config_tax')),
					$currency_code,
					$currency_value,
					false
				);
				$output .= '<g:sale_price>' . $this->xmlValue($sale_price . ' ' . $currency_code) . '</g:sale_price>';
			}

			if ($product['image']) {
				$output .= '<g:image_link>' . $this->xmlValue($this->model_tool_image->resize($product['image'], 500, 500)) . '</g:image_link>';
			}
			if ($gtin !== '') {
				$output .= '<g:gtin>' . $gtin . '</g:gtin>';
			}
			if ($mpn !== '') {
				$output .= '<g:mpn>' . $this->cdata($mpn) . '</g:mpn>';
			}
			if ($gtin === '' && $mpn === '') {
				$output .= '<g:identifier_exists>false</g:identifier_exists>';
			}

			$output .= '<g:google_product_category>' . (int)$mapped_product['google_product_category'] . '</g:google_product_category>';

			$categories = $this->model_catalog_product->getCategories($product_id);
			foreach ($categories as $category) {
				$path = $this->getPath($category['category_id']);
				if (!$path) {
					continue;
				}

				$category_names = array();
				foreach (explode('_', $path) as $path_id) {
					$category_info = $this->model_catalog_category->getCategory($path_id);
					if ($category_info) {
						$category_names[] = $category_info['name'];
					}
				}

				if ($category_names) {
					$output .= '<g:product_type>' . $this->cdata(implode(' > ', $category_names)) . '</g:product_type>';
				}
			}

			$output .= '</item>';
		}

		$output .= '</channel></rss>';
		$this->response->addHeader('Content-Type: application/rss+xml; charset=UTF-8');
		$this->response->setOutput($output);
	}

	protected function getPath($parent_id, $current_path = '') {
		$category_info = $this->model_catalog_category->getCategory($parent_id);

		if ($category_info) {
			$new_path = $current_path
				? $category_info['category_id'] . '_' . $current_path
				: $category_info['category_id'];
			$path = $this->getPath($category_info['parent_id'], $new_path);

			return $path ?: $new_path;
		}
	}
}
