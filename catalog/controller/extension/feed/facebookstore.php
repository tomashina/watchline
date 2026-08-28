<?php
class ControllerExtensionFeedFacebookstore extends Controller {
	private function cdata($value) {
		$value = html_entity_decode((string)$value, ENT_QUOTES | ENT_HTML5, 'UTF-8');
		$value = preg_replace('/\s+/u', ' ', trim($value));

		return '<![CDATA[' . str_replace(']]>', ']]]]><![CDATA[>', $value) . ']]>';
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
		$this->load->model('catalog/product');
		$this->load->model('extension/module/product_availability');

		$products = $this->model_catalog_product->getProducts();
		$product_urls = array();

		if ($this->config->get('hb_canonical_status') && $products) {
			$this->load->model('extension/module/hb_canonical');
			$product_urls = $this->model_extension_module_hb_canonical->getProductCanonicalUrls(array_column($products, 'product_id'));
		}

		$store_url = rtrim($this->config->get('config_ssl') ?: $this->config->get('config_url'), '/') . '/';
		$output = '<?xml version="1.0" encoding="UTF-8"?>';
		$output .= '<rss xmlns:g="http://base.google.com/ns/1.0" version="2.0"><channel>';
		$output .= '<title>' . $this->cdata($this->config->get('config_name')) . '</title>';
		$output .= '<link>' . $this->cdata($store_url) . '</link>';
		$output .= '<description>' . $this->cdata($this->config->get('config_meta_description')) . '</description>';

		foreach ($products as $product) {
			if (empty($product['model']) || empty($product['image'])) {
				continue;
			}

			$product_id = (int)$product['product_id'];
			$product_url = isset($product_urls[$product_id])
				? html_entity_decode($product_urls[$product_id], ENT_QUOTES | ENT_HTML5, 'UTF-8')
				: html_entity_decode($this->url->link('product/product', 'product_id=' . $product_id), ENT_QUOTES | ENT_HTML5, 'UTF-8');
			$description = trim(html_entity_decode($product['meta_description'], ENT_QUOTES | ENT_HTML5, 'UTF-8'));

			if ($description === '') {
				$description = trim(strip_tags(html_entity_decode($product['description'], ENT_QUOTES | ENT_HTML5, 'UTF-8')));
			}

			if ($description === '') {
				$description = $product['name'];
			}

			$price = number_format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')), 2, '.', '');
			$availability = $this->model_extension_module_product_availability->getFeedAvailability($product);
			$gtin = '';
			$mpn = $this->getProductMpn($product);

			foreach (array('ean', 'upc', 'jan', 'isbn') as $field) {
				if (!empty($product[$field]) && $this->isValidGtin($product[$field])) {
					$gtin = preg_replace('/\D+/', '', $product[$field]);
					break;
				}
			}

			$output .= '<item>';
			$output .= '<g:id>' . $this->cdata($product['model']) . '</g:id>';
			$output .= '<g:title>' . $this->cdata($product['name']) . '</g:title>';
			$output .= '<g:description>' . $this->cdata($description) . '</g:description>';
			$output .= '<g:link>' . $this->cdata($product_url) . '</g:link>';
			$output .= '<g:image_link>' . $this->cdata($store_url . 'image/' . ltrim($product['image'], '/')) . '</g:image_link>';
			if (!empty($product['manufacturer'])) {
				$output .= '<g:brand>' . $this->cdata($product['manufacturer']) . '</g:brand>';
			}
			$output .= '<g:condition>new</g:condition>';
			$output .= '<g:availability>' . $availability . '</g:availability>';
			$output .= '<g:price>' . $price . ' EUR</g:price>';

			if ((float)$product['special']) {
				$sale_price = number_format($this->tax->calculate($product['special'], $product['tax_class_id'], $this->config->get('config_tax')), 2, '.', '');
				$output .= '<g:sale_price>' . $sale_price . ' EUR</g:sale_price>';
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

			$output .= '</item>';
		}

		$output .= '</channel></rss>';
		$this->response->addHeader('Content-Type: application/rss+xml; charset=UTF-8');
		$this->response->setOutput($output);
	}
}
