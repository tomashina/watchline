<?php
class ModelExtensionModuleHbCanonical extends Model {
	private function selectProductCanonicalPath(array $candidates) {
		$type = (int)$this->config->get('hb_canonical_type');
		$counts = array_column($candidates, 'path_array_count');
		$paths = array_map('strval', array_column($candidates, 'path'));

		if ($type === 0) {
			array_multisort($counts, SORT_ASC, SORT_NUMERIC, $paths, SORT_ASC, SORT_STRING, $candidates);
		} else {
			array_multisort($counts, SORT_DESC, SORT_NUMERIC, $paths, SORT_ASC, SORT_STRING, $candidates);
		}

		if ($type === 2) {
			$level = (int)$this->config->get('hb_canonical_level');

			foreach ($candidates as $candidate) {
				if ((int)$candidate['path_array_count'] === $level) {
					return $candidate['path'];
				}
			}
		}

		return $candidates[0]['path'];
	}

	public function getCategoryCanonical($category_id) {
		$query = $this->db->query("SELECT DISTINCT *, (SELECT GROUP_CONCAT(c1.category_id ORDER BY level SEPARATOR '_') FROM " . DB_PREFIX . "category_path cp LEFT JOIN " . DB_PREFIX . "category c1 ON (cp.path_id = c1.category_id AND cp.category_id != cp.path_id) WHERE cp.category_id = c.category_id GROUP BY cp.category_id) AS path FROM " . DB_PREFIX . "category c WHERE c.category_id = '" . (int)$category_id . "'");

		return $query->row;
	}

	public function getProductCategories($product_id) {
		$product_category_data = array();
		$query = $this->db->query("SELECT category_id FROM " . DB_PREFIX . "product_to_category WHERE product_id = '" . (int)$product_id . "' ORDER BY category_id ASC");

		foreach ($query->rows as $result) {
			$product_category_data[] = (int)$result['category_id'];
		}

		return $product_category_data;
	}

	public function getManualPath($type, $id) {
		$path = false;

		if ($type === 'product') {
			$query = $this->db->query("SELECT path FROM " . DB_PREFIX . "product_canonical WHERE product_id = '" . (int)$id . "' LIMIT 1");
		} elseif ($type === 'category') {
			$query = $this->db->query("SELECT path FROM " . DB_PREFIX . "category_canonical WHERE category_id = '" . (int)$id . "' LIMIT 1");
		} else {
			return false;
		}

		if ($query->row) {
			$path = $query->row['path'];
		}

		return $path;
	}

	/**
	 * Resolve the canonical product URL without mutating the Document object.
	 * Schema, Open Graph, feeds and sitemaps can therefore use the same URL as
	 * the canonical link rendered by the product page.
	 */
	public function getProductCanonicalPath($product_id) {
		$product_id = (int)$product_id;
		$paths = $this->getProductCanonicalPaths(array($product_id));

		return isset($paths[$product_id]) ? $paths[$product_id] : false;
	}

	/**
	 * Resolve many product paths with two catalog queries instead of issuing
	 * category/path queries for every product in an ItemList or sitemap chunk.
	 */
	public function getProductCanonicalPaths(array $product_ids) {
		$product_ids = array_values(array_unique(array_filter(array_map('intval', $product_ids))));

		if (!$product_ids) {
			return array();
		}

		$id_list = implode(',', $product_ids);
		$paths = array();
		$candidates = array();
		$manual_ids = array();

		foreach ($product_ids as $product_id) {
			$candidates[$product_id] = array(array(
				'path_array_count' => 0,
				'path' => false
			));
		}

		$manual_query = $this->db->query("SELECT product_id, path FROM " . DB_PREFIX . "product_canonical WHERE product_id IN (" . $id_list . ")");

		foreach ($manual_query->rows as $row) {
			$product_id = (int)$row['product_id'];
			$paths[$product_id] = $row['path'] === 'E' ? false : $row['path'];
			$manual_ids[$product_id] = true;
		}

		$category_query = $this->db->query("SELECT p2c.product_id, p2c.category_id, GROUP_CONCAT(cp.path_id ORDER BY cp.level SEPARATOR '_') AS path FROM " . DB_PREFIX . "product_to_category p2c LEFT JOIN " . DB_PREFIX . "category_path cp ON (cp.category_id = p2c.category_id) WHERE p2c.product_id IN (" . $id_list . ") GROUP BY p2c.product_id, p2c.category_id ORDER BY p2c.product_id ASC, p2c.category_id ASC");

		foreach ($category_query->rows as $row) {
			$product_id = (int)$row['product_id'];

			if (isset($manual_ids[$product_id]) || empty($row['path'])) {
				continue;
			}

			$candidates[$product_id][] = array(
				'path_array_count' => count(explode('_', $row['path'])),
				'path' => $row['path']
			);
		}

		foreach ($product_ids as $product_id) {
			if (!isset($manual_ids[$product_id])) {
				$paths[$product_id] = $this->selectProductCanonicalPath($candidates[$product_id]);
			}
		}

		return $paths;
	}

	public function getProductCanonicalUrls(array $product_ids) {
		$urls = array();

		foreach ($this->getProductCanonicalPaths($product_ids) as $product_id => $path) {
			$args = 'product_id=' . (int)$product_id;

			if ($path !== false && $path !== '') {
				$args = 'path=' . $path . '&' . $args;
			}

			$urls[(int)$product_id] = $this->url->link('product/product', $args);
		}

		return $urls;
	}

	public function getProductCanonicalUrl($product_id) {
		$product_id = (int)$product_id;
		$path = $this->getProductCanonicalPath($product_id);
		$args = 'product_id=' . $product_id;

		if ($path !== false) {
			$args = 'path=' . $path . '&' . $args;
		}

		return $this->url->link('product/product', $args);
	}

	public function product_canonical($product_id) {
		$url = $this->getProductCanonicalUrl($product_id);
		$this->document->addLink($url, 'canonical');

		return $url;
	}

	/**
	 * Resolve the configured canonical category hierarchy without adding links.
	 */
	public function getCategoryCanonicalPath($category_id) {
		$category_id = (int)$category_id;
		$manual_path = $this->getManualPath('category', $category_id);

		if ($manual_path) {
			return $manual_path === 'E' ? (string)$category_id : $manual_path;
		}

		$paths = array(
			array('count' => 1, 'path' => (string)$category_id)
		);
		$category = $this->getCategoryCanonical($category_id);

		if ($category) {
			$path = !empty($category['path']) ? $category['path'] . '_' . $category['category_id'] : (string)$category['category_id'];
			$paths[] = array('count' => count(explode('_', $path)), 'path' => $path);
		}

		$counts = array_column($paths, 'count');

		if ((int)$this->config->get('hb_canonical_type_c') === 0) {
			array_multisort($counts, SORT_ASC, SORT_NUMERIC, $paths);
		} else {
			array_multisort($counts, SORT_DESC, SORT_NUMERIC, $paths);
		}

		return $paths[0]['path'];
	}

	public function getCategoryCanonicalUrl($category_id, $page = 1) {
		$path = $this->getCategoryCanonicalPath($category_id);
		$page = max(1, (int)$page);
		$args = 'path=' . $path;

		if ($page > 1) {
			$args .= '&page=' . $page;
		}

		return $this->url->link('product/category', $args);
	}

	public function category_canonical($category_id, $page, $limit, $total) {
		$page = max(1, (int)$page);
		$path = $this->getCategoryCanonicalPath($category_id);

		$this->document->addLink($this->getCategoryCanonicalUrl($category_id, $page), 'canonical');

		if ($limit && ceil($total / $limit) > $page) {
			$this->document->addLink($this->getCategoryCanonicalUrl($category_id, $page + 1), 'next');
		}

		if ($page > 1) {
			$this->document->addLink($this->getCategoryCanonicalUrl($category_id, $page - 1), 'prev');
		}

		return $path;
	}
}
