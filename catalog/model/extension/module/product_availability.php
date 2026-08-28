<?php
class ModelExtensionModuleProductAvailability extends Model {
	public function getSchemaAvailability(array $product) {
		$quantity = isset($product['quantity']) ? (int)$product['quantity'] : 0;
		$subtract = !isset($product['subtract']) || (bool)$product['subtract'];

		if ($quantity > 0 || !$subtract) {
			return 'https://schema.org/InStock';
		}

		// OpenCart refuses checkout for tracked products with no stock when this is disabled.
		// Structured data and merchant feeds must not advertise such products as orderable.
		if (!$this->config->get('config_stock_checkout')) {
			return 'https://schema.org/OutOfStock';
		}

		$status_id = isset($product['stock_status_id']) ? (int)$product['stock_status_id'] : 0;
		$status = isset($product['stock_status'])
			? utf8_strtolower(trim(html_entity_decode($product['stock_status'], ENT_QUOTES | ENT_HTML5, 'UTF-8')))
			: '';

		if ($status_id === 5 || preg_match('/out\s*of\s*stock|rasprodan|nedostup/u', $status)) {
			return 'https://schema.org/OutOfStock';
		}

		if ($status_id === 8 || preg_match('/pre[- ]?order|prednar/u', $status)) {
			return 'https://schema.org/PreOrder';
		}

		$stock_map = $this->config->get('hb_snippets_stock');
		$allowed = array('BackOrder', 'Discontinued', 'InStock', 'LimitedAvailability', 'OutOfStock', 'PreOrder', 'SoldOut');

		if (is_array($stock_map) && !empty($stock_map[$status_id]) && in_array($stock_map[$status_id], $allowed, true)) {
			return 'https://schema.org/' . $stock_map[$status_id];
		}

		if (in_array($status_id, array(6, 9, 10, 11), true) || preg_match('/radn(?:a|ih)\s+dana|\d+\s*-\s*\d+\s*dana|na\s+upit/u', $status)) {
			return 'https://schema.org/BackOrder';
		}

		if ($status_id === 7 || preg_match('/in\s*stock|na\s+zalih/u', $status)) {
			return 'https://schema.org/InStock';
		}

		return 'https://schema.org/OutOfStock';
	}

	public function getFeedAvailability(array $product) {
		$availability = $this->getSchemaAvailability($product);

		switch ($availability) {
			case 'https://schema.org/InStock':
			case 'https://schema.org/LimitedAvailability':
				return 'in_stock';
			case 'https://schema.org/PreOrder':
				return 'preorder';
			case 'https://schema.org/BackOrder':
				return 'backorder';
			default:
				return 'out_of_stock';
		}
	}
}
