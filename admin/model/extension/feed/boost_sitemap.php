<?php
//==============================================
// XML Sitemap OC 2.3.x_v2.x
// Author 	: OpenCartBoost
// Email 	: support@opencartboost.com
// Website 	: http://www.opencartboost.com
//==============================================
class ModelExtensionFeedBoostSitemap extends Model {
	/**
	 * Install
	 * 
	 * @access public
	 * @return void
	 */
	public function install() {
		$sqls[] = "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "boost_sitemap_custom_link` (
			`boost_sitemap_custom_link_id` int(11) NOT NULL AUTO_INCREMENT, 
			`url` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '', 
			`frequency` varchar(32) NOT NULL DEFAULT 'weekly', 
			`priority` char(3) NOT NULL DEFAULT '0.5', 
			`date_added` datetime NOT NULL, 
			`store_id` int(11) NOT NULL DEFAULT '0', 
			PRIMARY KEY (`boost_sitemap_custom_link_id`), 
			KEY `store_id` (`store_id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8";
		
		$sqls[] = "COMMIT";
		
		foreach ($sqls as $sql) {
			$this->db->query($sql);	
		}
		
		$this->load->model('setting/setting');
		
		$data = [
			'boost_sitemap_item_limit' => '1000',
			'boost_sitemap_item' => array('product', 'category', 'manufacturer', 'information', 'blog')
		];
		
		$this->model_setting_setting->editSetting('boost_sitemap', $data);
		
		//delete old setting data
		$this->db->query("DELETE FROM `" . DB_PREFIX . "setting` WHERE `code` = 'xmlsitemap'");  //oldversion
		$this->db->query("DELETE FROM `" . DB_PREFIX . "setting` WHERE `code` = 'module_xmlsitemap'"); //oldversion
		
		$this->vqmod_script_dir = substr_replace(DIR_SYSTEM, '/vqmod/xml/', -8);
		$vqmod_name = 'boost_sitemap';
		
		if (is_file($this->vqmod_script_dir . $vqmod_name . '.xml_')) {
			rename($this->vqmod_script_dir . $vqmod_name . '.xml_', $this->vqmod_script_dir . $vqmod_name . '.xml');
		}
	}
	
	/**
	 * Uninstall
	 * 
	 * @access public
	 * @return void
	 */
	public function uninstall() {
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "boost_sitemap_custom_link`");
		
		//delete old setting data
		$this->db->query("DELETE FROM `" . DB_PREFIX . "setting` WHERE `code` = 'boost_sitemap'");
		
		$this->vqmod_script_dir = substr_replace(DIR_SYSTEM, '/vqmod/xml/', -8);
		$vqmod_name = 'boost_sitemap';
		
		if (is_file($this->vqmod_script_dir . $vqmod_name . '.xml')) {
			rename($this->vqmod_script_dir . $vqmod_name . '.xml', $this->vqmod_script_dir . $vqmod_name . '.xml_');
		}
	}
	
	/**
	 * Get products
	 * 
	 * @access public
	 * @param array $data
	 * @return array
	 */
	public function getProducts($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "product_to_store p2s";
		
		if (!empty($data['filter_category_id'])) {
			$sql .= " LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (p2s.product_id = p2c.product_id) LEFT JOIN " . DB_PREFIX . "product p ON (p2c.product_id = p.product_id)";
		} else {
			$sql .= " LEFT JOIN " . DB_PREFIX . "product p ON (p2s.product_id = p.product_id)";
		}
		
		$sql .= " LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) WHERE p2s.store_id = '" . (int)$data['store_id'] . "' AND pd.language_id = '" . (int)$data['language_id'] . "' AND p.status = '1'";
		
		if (!empty($data['filter_category_id'])) {
			$sql .= " AND p2c.category_id = '" . (int)$data['filter_category_id'] . "'";
		}
		
		if (!empty($data['filter_manufacturer_id'])) {
			$sql .= " AND p.manufacturer_id = '" . (int)$data['filter_manufacturer_id'] . "'";
		}
		
		$sql .= " GROUP BY p.product_id ORDER BY pd.name ASC";
		
		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$query = $this->db->query($sql);

		return $query->rows;
	}
	
	/**
	 * Get total of products
	 * 
	 * @access public
	 * @param array $data
	 * @return int
	 */
	public function getTotalProducts($data = array()) {
		$sql = "SELECT COUNT(DISTINCT p.product_id) AS total FROM " . DB_PREFIX . "product_to_store p2s";
		
		if (!empty($data['filter_category_id'])) {
			$sql .= " LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (p2s.product_id = p2c.product_id) LEFT JOIN " . DB_PREFIX . "product p ON (p2c.product_id = p.product_id)";
		} else {
			$sql .= " LEFT JOIN " . DB_PREFIX . "product p ON (p2s.product_id = p.product_id)";
		}
		
		$sql .= " LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) WHERE p2s.store_id = '" . (int)$data['store_id'] . "' AND pd.language_id = '" . (int)$data['language_id'] . "' AND p.status = '1'";
		
		if (!empty($data['filter_category_id'])) {
			$sql .= " AND p2c.category_id = '" . (int)$data['filter_category_id'] . "'";
		}
		
		if (!empty($data['filter_manufacturer_id'])) {
			$sql .= " AND p.manufacturer_id = '" . (int)$data['filter_manufacturer_id'] . "'";
		}
		
		$query = $this->db->query($sql);
		
		return $query->row['total'];
	}
	
	/**
	 * Get categories
	 * 
	 * @access public
	 * @param array $data
	 * @return array
	 */
	public function getCategories($data = array()) {
		$sql = "SELECT cp.category_id AS category_id, c1.image, c1.date_modified, cd2.name AS name, GROUP_CONCAT(cd.category_id ORDER BY cp.level SEPARATOR '_') AS path, c1.parent_id, c1.sort_order FROM " . DB_PREFIX . "category_to_store c2s LEFT JOIN " . DB_PREFIX . "category_path cp ON(c2s.category_id = cp.category_id) LEFT JOIN " . DB_PREFIX . "category c1 ON (cp.category_id = c1.category_id) LEFT JOIN " . DB_PREFIX . "category c2 ON (cp.path_id = c2.category_id) LEFT JOIN " . DB_PREFIX . "category_description cd ON (cp.path_id = cd.category_id) LEFT JOIN " . DB_PREFIX . "category_description cd2 ON (cp.category_id = cd2.category_id) WHERE c2s.store_id = '" . (int)$data['store_id'] . "' AND cd.language_id = '" . (int)$data['language_id'] . "' AND cd2.language_id = '" . (int)$data['language_id'] . "' AND c1.status = '1'";
		
		if (isset($data['parent_id'])) {
			$sql .= " AND c1.parent_id = '" . (int)$data['parent_id'] . "'";
		}
		
		$sql .= " GROUP BY cp.category_id ORDER BY sort_order ASC";
		
		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$query = $this->db->query($sql);

		return $query->rows;
	}
	
	/**
	 * Get total of categories
	 * 
	 * @access public
	 * @param array $data
	 * @return int
	 */
	public function getTotalCategories($data = array()) {
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "category_to_store c2s LEFT JOIN " . DB_PREFIX . "category c ON(c2s.category_id = c.category_id) LEFT JOIN " . DB_PREFIX . "category_description cd ON (c.category_id = cd.category_id) WHERE c.status = '1' AND c2s.store_id = '" . (int)$data['store_id'] . "' AND cd.language_id = '" . (int)$data['language_id'] . "'";
		
		if (isset($data['parent_id'])) {
			$sql .= " AND c.parent_id = '" . (int)$data['parent_id'] . "'";
		}
		
		$query = $this->db->query($sql);
		
		return $query->row['total'];
	}
	
	/**
	 * Get manufacturers
	 * 
	 * @access public
	 * @param array $data
	 * @return array
	 */
	public function getManufacturers($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "manufacturer_to_store m2s LEFT JOIN " . DB_PREFIX . "manufacturer m ON(m2s.manufacturer_id = m.manufacturer_id) WHERE m2s.store_id = '" . (int)$data['store_id']. "' ORDER BY name ASC";

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$query = $this->db->query($sql);

		return $query->rows;
	}
	
	/**
	 * Get total manufacturers
	 * 
	 * @access public
	 * @param array $data
	 * @return int
	 */
	public function getTotalManufacturers($data = array()) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "manufacturer_to_store m2s LEFT JOIN " . DB_PREFIX . "manufacturer m ON(m2s.manufacturer_id = m.manufacturer_id) WHERE m2s.store_id = '" . (int)$data['store_id']. "'");
		
		return $query->row['total'];
	}
	
	/**
	 * Get informations
	 * 
	 * @access public
	 * @param array $data
	 * @return array
	 */
	public function getInformations($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "information_to_store i2s LEFT JOIN " . DB_PREFIX . "information i ON(i2s.information_id = i.information_id) LEFT JOIN " . DB_PREFIX . "information_description id ON (i.information_id = id.information_id) WHERE i2s.store_id = '" . (int)$data['store_id'] . "' AND id.language_id = '" . (int)$data['language_id'] . "' AND i.status = '1' ORDER BY i.sort_order, LCASE(id.title) ASC";

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$query = $this->db->query($sql);

		return $query->rows;
	}
	
	/**
	 * Get total of informations
	 * 
	 * @access public
	 * @return int
	 */
	public function getTotalInformations($data = array()) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "information_to_store i2s LEFT JOIN " . DB_PREFIX . "information i ON(i2s.information_id = i.information_id) LEFT JOIN " . DB_PREFIX . "information_description id ON (i.information_id = id.information_id) WHERE i2s.store_id = '" . (int)$data['store_id'] . "' AND id.language_id = '" . (int)$data['language_id'] . "' AND i.status = '1'");
		
		return $query->row['total'];
	}
	
	/**
	 * Validate slug
	 * 
	 * @access protected
	 * @param string $keyword
	 * @param int $counter
	 * @return string
	 */
	protected function validateKeyword($keyword = '', $store_id = 0, $counter = null) {
		$keyword = $keyword.($counter ? '-'.$counter : '');
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "seo_url WHERE keyword = '" . $this->db->escape($keyword) . "' AND 'store_id' = '" .(int)$store_id. "'");
		$count = (int)$query->row['total'];
		
		if ($count > 0) {
			if( ! $counter) {
				$counter = 1;
			} else {
				$counter++;
			}
		
			return $this->validateKeyword($keyword, $store_id, $counter);
		} else {
			return $keyword.($counter ? '-'.$counter : '');
		}
	}
	
	/**
	 * Convert to URL title style
	 * 
	 * @access protected
	 * @param string $str
	 * @param string $separator
	 * @param bool $lowercase
	 * @return string
	 */
	protected function urlTitle($str, $separator = '-', $lowercase = false) {
		if ($separator === 'dash') {
			$separator = '-';
		} elseif ($separator === 'underscore') {
			$separator = '_';
		}

		$q_separator = preg_quote($separator, '#');

		$trans = array(
			'&.+?;'	=> '',
			'[^\w\d _-]' => '',
			'\s+' => $separator,
			'('.$q_separator.')+' => $separator
		);

		$str = strip_tags($str);
		
		foreach ($trans as $key => $val) {
			$str = preg_replace('#'.$key.'#iu', $val, $str);
		}

		if ($lowercase === true) {
			$str = strtolower($str);
		}

		return strtolower(trim(trim($str, $separator)));
	}
	
	/**
	 * Generate keyword
	 * 
	 * @access public
	 * @param mixed $query
	 * @param mixed $keyword
	 * @param int $language_id
	 * @param int $store_id
	 * @return void
	 */
	public function generateKeyword($query, $keyword, $language_id = 1, $store_id = 0) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "seo_url WHERE query = '" . $this->db->escape($query) . "' AND language_id = '" . (int)$language_id . "' AND store_id = '" . (int)$store_id . "'");
		
		$keyword = $this->validateKeyword($this->urlTitle($keyword), $store_id);
		
		$this->db->query("INSERT INTO " . DB_PREFIX . "seo_url SET query = '" . $this->db->escape($query) . "', keyword = '" . $this->db->escape($keyword) . "', language_id = '" . (int)$language_id . "', store_id = '" . (int)$store_id . "'");
		
		$this->cache->delete('seo_url');
	}
	
	/**
	 * Add custom link
	 * 
	 * @access public
	 * @param array $data
	 * @return void
	 */
	public function addCustomLink($data = array()) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "boost_sitemap_custom_link SET url = '" . $this->db->escape($data['url']) . "', frequency = '" . $this->db->escape($data['frequency']) . "', priority = '" . $this->db->escape($data['priority']) . "', store_id = '" . (int)$data['store_id'] . "', date_added = '" . date('Y-m-d H:i:s') . "'");
	}
	
	/**
	 * Get custom links
	 * 
	 * @access public
	 * @return array
	 */
	public function getCustomLinks($data = array()) {	
		$sql = "SELECT bscl.*, s.name AS store_name FROM " . DB_PREFIX . "boost_sitemap_custom_link bscl LEFT JOIN " . DB_PREFIX . "store s ON (bscl.store_id = s.store_id)";
		
		if (isset($data['store_id'])) {
			$sql .= " WHERE bscl.store_id = '" . (int)$data['store_id'] . "'";
		}
		
		$sql .= " ORDER BY bscl.store_id ASC";
		
		$query = $this->db->query($sql);
		
		return $query->rows;
	}
	
	/**
	 * Get total of custom links
	 * 
	 * @access public
	 * @return int
	 */
	public function getTotalCustomLinks($data = array()) {
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "boost_sitemap_custom_link";
		
		if (isset($data['store_id'])) {
			$sql .= " WHERE store_id = '" . (int)$data['store_id'] . "'";
		}
		
		$query = $this->db->query($sql);
		
		return $query->row['total'];
	}

	public function getBlogs($data = array()) {
		$sql = "SELECT b.blog_id, b.date_added, b.image, bd.title FROM " . DB_PREFIX . "blog b LEFT JOIN " . DB_PREFIX . "blog_description bd ON (b.blog_id = bd.blog_id) LEFT JOIN " . DB_PREFIX . "blog_to_store b2s ON (b.blog_id = b2s.blog_id) WHERE b.status = '1' AND b.sort_order <> '-1' AND bd.language_id = '" . (int)$data['language_id'] . "' AND b2s.store_id = '" . (int)$data['store_id'] . "' ORDER BY b.blog_id ASC";

		if (isset($data['start']) || isset($data['limit'])) {
			$start = isset($data['start']) ? max(0, (int)$data['start']) : 0;
			$limit = isset($data['limit']) ? max(1, (int)$data['limit']) : 1000;
			$sql .= " LIMIT " . $start . "," . $limit;
		}

		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getTotalBlogs($data = array()) {
		$query = $this->db->query("SELECT COUNT(DISTINCT b.blog_id) AS total FROM " . DB_PREFIX . "blog b LEFT JOIN " . DB_PREFIX . "blog_description bd ON (b.blog_id = bd.blog_id) LEFT JOIN " . DB_PREFIX . "blog_to_store b2s ON (b.blog_id = b2s.blog_id) WHERE b.status = '1' AND b.sort_order <> '-1' AND bd.language_id = '" . (int)$data['language_id'] . "' AND b2s.store_id = '" . (int)$data['store_id'] . "'");

		return (int)$query->row['total'];
	}
	
	/**
	 * Delete custom link
	 * 
	 * @access public
	 * @param int $boost_sitemap_custom_link_id
	 * @return void
	 */
	public function deleteCustomLink($boost_sitemap_custom_link_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "boost_sitemap_custom_link WHERE boost_sitemap_custom_link_id = '" . (int)$boost_sitemap_custom_link_id . "'");
	}
	
	/**
	 * Resize image
	 * 
	 * @access public
	 * @param string $filename
	 * @param int $width
	 * @param int $height
	 * @param string $url
	 * @return void
	 */
	public function resizeImage($filename, $width, $height, $url) {
		if (!is_file(DIR_IMAGE . $filename) || substr(str_replace('\\', '/', realpath(DIR_IMAGE . $filename)), 0, strlen(DIR_IMAGE)) != str_replace('\\', '/', DIR_IMAGE)) {
			return;
		}

		$extension = pathinfo($filename, PATHINFO_EXTENSION);

		$image_old = $filename;
		$image_new = 'cache/' . utf8_substr($filename, 0, utf8_strrpos($filename, '.')) . '-' . $width . 'x' . $height . '.' . $extension;

		if (!is_file(DIR_IMAGE . $image_new) || (filemtime(DIR_IMAGE . $image_old) > filemtime(DIR_IMAGE . $image_new))) {
			list($width_orig, $height_orig, $image_type) = getimagesize(DIR_IMAGE . $image_old);
				 
			if (!in_array($image_type, array(IMAGETYPE_PNG, IMAGETYPE_JPEG, IMAGETYPE_GIF))) { 
				return DIR_IMAGE . $image_old;
			}
 
			$path = '';

			$directories = explode('/', dirname($image_new));

			foreach ($directories as $directory) {
				$path = $path . '/' . $directory;

				if (!is_dir(DIR_IMAGE . $path)) {
					@mkdir(DIR_IMAGE . $path, 0777);
				}
			}

			if ($width_orig != $width || $height_orig != $height) {
				$image = new Image(DIR_IMAGE . $image_old);
				$image->resize($width, $height);
				$image->save(DIR_IMAGE . $image_new);
			} else {
				copy(DIR_IMAGE . $image_old, DIR_IMAGE . $image_new);
			}
		}
		
		return $url . 'image/' . $image_new;
	}
}
