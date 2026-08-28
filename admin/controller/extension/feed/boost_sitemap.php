<?php
//==============================================
// XML Sitemap OC 2.3.x_v2.x
// Author 	: OpenCartBoost
// Email 	: support@opencartboost.com
// Website 	: http://www.opencartboost.com
//==============================================
class ControllerExtensionFeedBoostSitemap extends Controller {
	private $error = array();
	private $languages = array();
	private $stores = array();
	private $files = array();
	private $directory;
	private $canonical_settings = array();

	/**
	 * Constructor
	 *
	 * @access public
	 * @param mixed $registry
	 * @return void
	 */
	public function __construct($registry) {
		$this->registry = $registry;
		$this->directory = str_replace('system', 'sitemaps', DIR_SYSTEM);

		$this->load->language('extension/feed/boost_sitemap');

		$this->load->model('setting/store');
		$this->load->model('setting/setting');

		$stores = $this->model_setting_store->getStores();

		$this->stores[] = [
			'store_id' => 0,
			'name' => 'Default',
			'url' => ($this->config->get('config_secure') ? HTTPS_CATALOG : HTTPS_CATALOG)
		];

		foreach ($stores as $store) {
			$ssl = $this->model_setting_setting->getSettingValue('config_secure', $store['store_id']);

			$this->stores[] = [
				'store_id' => $store['store_id'],
				'name' => $store['name'],
				'url' => ($ssl ? $store['ssl'] : $store['url'])
			];
		}

		$this->load->model('localisation/language');

		$languages = $this->model_localisation_language->getLanguages();

		foreach ($languages as $language) {
			$this->languages[] = $language;
		}
	}

	/**
	 * Install
	 *
	 * @access public
	 * @return void
	 */
	public function install() {
		umask(0);
		mkdir($this->directory, 0777);

		$this->load->model('extension/feed/boost_sitemap');

		$this->model_extension_feed_boost_sitemap->install();
	}

	/**
	 * Uninstall
	 *
	 * @access public
	 * @return void
	 */
	public function uninstall() {
		umask(0);

		$dir = $this->directory;
		$it = new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS);
		$files = new RecursiveIteratorIterator($it, RecursiveIteratorIterator::CHILD_FIRST);

		foreach($files as $file) {
			if ($file->isDir()){
				rmdir($file->getRealPath());
			} else {
				unlink($file->getRealPath());
			}
		}

		rmdir($dir);

		$this->load->model('extension/feed/boost_sitemap');

		$this->model_extension_feed_boost_sitemap->uninstall();
	}

	/**
	 * Index
	 *
	 * @access public
	 * @return void
	 */
	public function index() {
		$this->document->setTitle($this->language->get('heading_title_etitle'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('boost_sitemap', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			if (isset($this->request->get['continue'])) {
				$this->response->redirect($this->url->link('extension/feed/boost_sitemap', 'token=' . $this->session->data['token'], true));
			} else {
				$this->response->redirect($this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=feed', true));
			}

		}

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		$text_languages = array(
			'heading_title',
			'text_extension',
			'text_success',
			'text_edit',
			'text_overwrite',
			'text_confirm',
			'text_empty',
			'text_enabled',
			'text_disabled',
			'text_always',
			'text_hourly',
			'text_daily',
			'text_weekly',
			'text_monthly',
			'text_yearly',
			'text_never',
			'column_parameter',
			'column_changefreq',
			'column_priority',
			'entry_product',
			'entry_category',
			'entry_manufacturer',
			'entry_information',
			'entry_status',
			'entry_data_feed',
			'entry_item_keyword',
			'entry_item',
			'entry_item_limit',
			'entry_url',
			'entry_frequency',
			'entry_priority',
			'entry_store',
			'help_item_keyword',
			'help_item',
			'button_save',
			'button_save_continue',
			'button_cancel',
			'button_delete',
			'button_refresh',
			'button_generate_file',
			'button_add_link',
			'column_store',
			'column_sitemap_index',
			'column_ping_to',
			'column_file_path',
			'column_file_size',
			'column_file_created',
			'column_link',
			'column_frequency',
			'column_priority',
			'tab_general',
			'tab_keyword',
			'tab_setting',
			'tab_raw_file',
			'tab_custom_link',
			'error_permission',
			'success_keyword'
		);

		foreach ($text_languages as $text_lang) {
			$data[$text_lang] = $this->language->get($text_lang);
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=feed', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/feed/boost_sitemap', 'token=' . $this->session->data['token'], true)
		);

		$data['action'] = $this->url->link('extension/feed/boost_sitemap', 'token=' . $this->session->data['token'], true);
		$data['cancel'] = $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=feed', true);
		$data['continue'] = $this->url->link('extension/feed/boost_sitemap', 'token=' . $this->session->data['token'] . '&continue=1', true);
		$data['delete'] = $this->url->link('extension/feed/boost_sitemap/delete', 'token=' . $this->session->data['token'], true);

		$data['generate'] = $this->url->link('extension/feed/boost_sitemap/generate', 'token=' . $this->session->data['token'], true);
		$data['custom_link'] = str_replace('&amp;', '&', $this->url->link('extension/feed/boost_sitemap/custom_link', 'token=' . $this->session->data['token'], true));
		$data['delete_custom_link'] = str_replace('&amp;', '&', $this->url->link('extension/feed/boost_sitemap/delete_custom_link', 'token=' . $this->session->data['token'], true));

		$data['data_feed'] = [];

		foreach ($this->stores as $store) {
			$data['data_feed'][] = [
				'store_name' => $store['name'],
				'feed' => $this->link($store['url'], 'extension/feed/boost_sitemap', '')
			];
		}

		$boostsitemap_config = [
			'boost_sitemap_status',
			'boost_sitemap_item_limit'
		];

		foreach ($boostsitemap_config as $conf_sitemap1) {
			if (isset($this->request->post[$conf_sitemap1])) {
				$data[$conf_sitemap1] = $this->request->post[$conf_sitemap1];
			} else {
				$data[$conf_sitemap1] = $this->config->get($conf_sitemap1);
			}
		}

		if (isset($this->request->post['boost_sitemap_item'])) {
			$data['boost_sitemap_item'] = $this->request->post['boost_sitemap_item'];
			} elseif ($this->config->get('boost_sitemap_item')) {
				$data['boost_sitemap_item'] = $this->config->get('boost_sitemap_item');
			} else {
				$data['boost_sitemap_item'] = array('product', 'category', 'manufacturer', 'information', 'blog');
			}

				$data['items'] = [
					'product' => 'Product Sitemaps',
					'category' => 'Category Sitemaps',
					'manufacturer' => 'Manufacturer Sitemaps',
					'information' => 'Information Sitemaps',
					'blog' => 'Blog Sitemaps',
					'custom_link' => 'Custom Link Sitemaps'
		];

		$data['xml_files'] = [];

		foreach ($this->getRecursiveFiles($this->directory) as $file) {
			if (pathinfo($file, PATHINFO_EXTENSION) == 'xml') {
				foreach ($this->stores as $store) {
					$path = basename($file);
					$explode = explode('_', $path);

					if (isset($explode[1])) {
						$store_id = $explode[1];

						if ($store_id == $store['store_id']) {
							$data['xml_files'][] = [
								'url' => $store['url'] . 'sitemaps/' . $path,
								'path' => $path,
								'size' => $this->getFileSize(filesize($file)),
								'datetime' => date($this->language->get('datetime_format'), filemtime($file))
							];
						}
					}
				}
			}
		}

		$data['text_overwrite'] = $this->language->get('text_overwrite');
		$data['stores'] = $this->stores;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/feed/boost_sitemap', $data));
	}

	/**
	 * Validate
	 *
	 * @access protected
	 * @return void
	 */
	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/feed/boost_sitemap')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}

	/**
	 * Get files recursively
	 *
	 * @access protected
	 * @param string $dir
	 * @param array &$results
	 * @return array
	 */
	protected function getRecursiveFiles($dir, &$results = array()) {
		$files = scandir($dir);

		foreach ($files as $key => $value) {
			$path = realpath($dir . DIRECTORY_SEPARATOR . $value);
			if (!is_dir($path)) {
				$results[] = $path;
			} else if ($value != "." && $value != "..") {
				$this->getRecursiveFiles($path, $results);
				$results[] = $path;
			}
		}

		return $results;
	}

	/**
	 * Get file size
	 *
	 * @access protected
	 * @param float $size
	 * @return string
	 */
	protected function getFileSize($size) {
		$suffix = array(
			'B',
			'KB',
			'MB',
			'GB',
			'TB',
			'PB',
			'EB',
			'ZB',
			'YB'
		);

		$i = 0;

		while (($size / 1024) > 1) {
			$size = $size / 1024;
			$i++;
		}

		return number_format($size, 2, '.', ',') . ' ' . $suffix[$i];
	}

	protected function removeLegacyProductSitemaps() {
		$patterns = array(
			$this->directory . 'sitemap_*_category_product*.xml',
			$this->directory . 'sitemap_*_manufacturer_product*.xml'
		);

		foreach ($patterns as $pattern) {
			$files = glob($pattern);
			if (!$files) {
				continue;
			}

			foreach ($files as $file) {
				if (is_file($file)) {
					unlink($file);
				}
			}
		}
	}

	protected function getCanonicalSetting($key, $store_id, $default = null) {
		$cache_key = (int)$store_id . ':' . $key;

		if (!array_key_exists($cache_key, $this->canonical_settings)) {
			$this->canonical_settings[$cache_key] = $this->model_setting_setting->getSettingValue($key, (int)$store_id);
		}

		$value = $this->canonical_settings[$cache_key];

		return $value === null || $value === '' ? $default : $value;
	}

	protected function getProductCanonicalArguments($product_id, $store_id) {
		$arguments = $this->getProductCanonicalArgumentsBatch(array($product_id), $store_id);

		return $arguments[(int)$product_id];
	}

	protected function getProductCanonicalArgumentsBatch(array $product_ids, $store_id) {
		$product_ids = array_values(array_unique(array_filter(array_map('intval', $product_ids))));
		$arguments = array();

		foreach ($product_ids as $product_id) {
			$arguments[$product_id] = 'product_id=' . $product_id;
		}

		if (!$product_ids || !$this->getCanonicalSetting('hb_canonical_status', $store_id, 0)) {
			return $arguments;
		}

		$id_list = implode(',', $product_ids);
		$candidates = array();
		$paths = array();
		$manual_ids = array();
		$type = (int)$this->getCanonicalSetting('hb_canonical_type', $store_id, 1);
		$level = (int)$this->getCanonicalSetting('hb_canonical_level', $store_id, 1);

		foreach ($product_ids as $product_id) {
			$candidates[$product_id] = array(array('count' => 0, 'path' => false));
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

			if (!isset($manual_ids[$product_id]) && !empty($row['path'])) {
				$candidates[$product_id][] = array(
					'count' => count(explode('_', $row['path'])),
					'path' => $row['path']
				);
			}
		}

		foreach ($product_ids as $product_id) {
			if (!isset($manual_ids[$product_id])) {
				$product_candidates = $candidates[$product_id];
				$counts = array_column($product_candidates, 'count');
				$path_values = array_map('strval', array_column($product_candidates, 'path'));
				array_multisort($counts, $type === 0 ? SORT_ASC : SORT_DESC, SORT_NUMERIC, $path_values, SORT_ASC, SORT_STRING, $product_candidates);
				$paths[$product_id] = $product_candidates[0]['path'];

				if ($type === 2) {
					foreach ($product_candidates as $candidate) {
						if ((int)$candidate['count'] === $level) {
							$paths[$product_id] = $candidate['path'];
							break;
						}
					}
				}
			}

			if ($paths[$product_id] !== false && $paths[$product_id] !== '') {
				$arguments[$product_id] = 'path=' . $paths[$product_id] . '&product_id=' . $product_id;
			}
		}

		return $arguments;
	}

	protected function getCategoryCanonicalArguments($category_id, $store_id) {
		$category_id = (int)$category_id;

		if (!$this->getCanonicalSetting('hb_canonical_status', $store_id, 0)) {
			return 'path=' . $category_id;
		}

		$manual = $this->db->query("SELECT path FROM " . DB_PREFIX . "category_canonical WHERE category_id = '" . $category_id . "' LIMIT 1");

		if ($manual->row) {
			$path = $manual->row['path'] === 'E' ? (string)$category_id : $manual->row['path'];
		} else {
			$query = $this->db->query("SELECT GROUP_CONCAT(path_id ORDER BY level SEPARATOR '_') AS path FROM " . DB_PREFIX . "category_path WHERE category_id = '" . $category_id . "'");
			$full_path = $query->row && $query->row['path'] ? $query->row['path'] : (string)$category_id;
			$type = (int)$this->getCanonicalSetting('hb_canonical_type_c', $store_id, 1);
			$path = $type === 0 ? (string)$category_id : $full_path;
		}

		return 'path=' . $path;
	}

	protected function xmlValue($value) {
		$value = html_entity_decode((string)$value, ENT_QUOTES | ENT_HTML5, 'UTF-8');

		return htmlspecialchars($value, ENT_QUOTES | ENT_XML1, 'UTF-8');
	}

	protected function sitemapDate($value) {
		$timestamp = strtotime($value);

		return $timestamp && (int)date('Y', $timestamp) > 1970 ? date('c', $timestamp) : false;
	}

	protected function writeSitemapFile($file_name, $output) {
		$target = $this->directory . basename($file_name);
		$temp = $target . '.tmp-' . uniqid('', true);

		if (file_put_contents($temp, $output, LOCK_EX) === false || !rename($temp, $target)) {
			if (is_file($temp)) {
				unlink($temp);
			}

			throw new Exception('Unable to write sitemap file: ' . basename($file_name));
		}
	}

	protected function removeStaleSitemapFiles($store_id, $language_id, $type, array $generated_files) {
		$prefix = 'sitemap_' . (int)$store_id . '_';

		if ($language_id !== null) {
			$prefix .= (int)$language_id . '_';
		}

		$prefix .= preg_replace('/[^a-z0-9_]/i', '', $type);
		$keep = array_flip(array_map('basename', $generated_files));
		$files = glob($this->directory . $prefix . '*.xml');

		if (!$files) {
			return;
		}

		foreach ($files as $file) {
			$basename = basename($file);

			if (preg_match('/^' . preg_quote($prefix, '/') . '(?:_\\d+)?\\.xml$/', $basename) && !isset($keep[$basename])) {
				unlink($file);
			}
		}
	}

	/**
	 * Delete xml files
	 *
	 * @access public
	 * @return void
	 */
	public function delete() {
		$json = [];

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			if (isset($this->request->post['selected'])) {
				$selected = $this->request->post['selected'];

				foreach ($selected as $path) {
					if (file_exists($this->directory . $path)) {
						unlink($this->directory . $path);
					}
				}
			}
		}

		$this->response->setOutput(json_encode($json));
	}

	/**
	 * Generate xml files
	 *
	 * @access public
	 * @return void
	 */
	public function generate() {
		$json = [];

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->load->model('setting/setting');

			if (isset($this->request->post['selected'])) {
				unset($this->request->post['selected']);
			}

			if (isset($this->request->post['boost_sitemap_item'])) {
				$this->request->post['boost_sitemap_item'] = array_values(array_diff(
					$this->request->post['boost_sitemap_item'],
					array('category_product', 'manufacturer_product')
				));
			}

			$setting_data = array(
				'boost_sitemap_status' => !empty($this->request->post['boost_sitemap_status']) ? 1 : 0,
				'boost_sitemap_item_limit' => max(1, isset($this->request->post['boost_sitemap_item_limit']) ? (int)$this->request->post['boost_sitemap_item_limit'] : 1),
				'boost_sitemap_item' => isset($this->request->post['boost_sitemap_item']) ? $this->request->post['boost_sitemap_item'] : array()
			);
			$this->model_setting_setting->editSetting('boost_sitemap', $setting_data);
			$this->db->query("DELETE FROM " . DB_PREFIX . "setting WHERE code = 'feed_boost_sitemap' AND `key` IN ('boost_sitemap_status', 'boost_sitemap_item_limit', 'boost_sitemap_item')");

			$items = $setting_data['boost_sitemap_item'];
			$limit = $setting_data['boost_sitemap_item_limit'];
			$this->removeLegacyProductSitemaps();

			if (in_array('product', $items)) {
				$this->generateProductSitemap($limit);
			}

			if (in_array('category', $items)) {
				$this->generateCategorySitemap($limit);
			}

			if (in_array('information', $items)) {
				$this->generateInformationSitemap($limit);
			}

			if (in_array('manufacturer', $items)) {
				$this->generateManufacturerSitemap($limit);
			}

			if (in_array('blog', $items)) {
				$this->generateBlogSitemap($limit);
			}

			if (in_array('custom_link', $items)) {
				$this->generateCustomLinkSitemap($limit);
			}
		}

		$this->response->setOutput(json_encode($json));
	}

	/**
	 * Get products of category recursively
	 *
	 * @access public
	 * @param int $parent_id
	 * @param string $current_path
	 * @param int $language_id
	 * @param int $store_id
	 * @return array
	 */
	public function getCategories($parent_id, $current_path = '', $language_id, $store_id) {
		$this->load->model('extension/feed/boost_sitemap');

		$output = array();
		$results = $this->model_extension_feed_boost_sitemap->getCategories(array(
			'store_id' => $store_id,
			'parent_id' => $parent_id,
			'language_id' => $language_id
		));

		foreach ($results as $result) {
			if (!$current_path) {
				$new_path = $result['category_id'];
			} else {
				$new_path = $current_path . '_' . $result['category_id'];
			}

			$products = $this->model_extension_feed_boost_sitemap->getProducts(array(
				'store_id' => $store_id,
				'filter_category_id' => $result['category_id'],
				'language_id' => $language_id
			));

			foreach ($products as $product) {
				$output[] = array(
					'product_id' => $product['product_id'],
					'name' => $product['name'],
					'image' => $product['image'],
					'path' => $new_path
				);
			}

			foreach ($this->getCategories($result['category_id'], $new_path, $language_id, $store_id) as $child) {
				$output[] = $child;
			}
		}

		return $output;
	}

	/**
	 * Generate category to product sitemap
	 *
	 * @access protected
	 * @param int $limit
	 * @return void
	 */
	protected function generateCategoryToProductSitemap($limit = null) {
		$this->load->model('extension/feed/boost_sitemap');

		if (version_compare(VERSION, '2.2.0.0', '<')) {
			$image_width = $this->config->get('config_image_popup_width');
			$image_height = $this->config->get('config_image_popup_height');
		} else {
			$image_width = $this->config->get($this->config->get('config_theme') . '_image_popup_width');
			$image_height = $this->config->get($this->config->get('config_theme') . '_image_popup_height');
		}

		foreach ($this->stores as $store) {
			foreach ($this->languages as $language) {
				$categories = $this->getCategories(0, '', $language['language_id'], $store['store_id']);
				$results = array_chunk($categories, $limit);
				$count = 1;

				foreach ($results as $key => $result) {
					$output  = '<?xml version="1.0" encoding="UTF-8"?>';
					$output .= '<urlset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd" xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">';

					foreach ($result as $product) {
						$output .= '<url>';
						$output .= '  <loc>' . $this->link($store['url'], 'product/product', 'path=' . $product['path'] . '&product_id=' . $product['product_id'], $store['store_id'], $language['language_id']) . '</loc>';
						$output .= '  <changefreq>weekly</changefreq>';
						$output .= '  <priority>1.0</priority>';

						if ($product['image']) {
							$output .= '  <image:image>';
							$output .= '  <image:loc>' . $this->model_extension_feed_boost_sitemap->resizeImage($product['image'], $image_width, $image_height, $store['url']) . '</image:loc>';
							$output .= '  <image:caption>' . $product['name'] . '</image:caption>';
							$output .= '  <image:title>' . $product['name'] . '</image:title>';
							$output .= '  </image:image>';
						}

						$output .= '</url>';
					}

					$output .= '</urlset>';

					if (count($categories) <= $limit) {
						$file_name = 'sitemap_' . $store['store_id'] . '_' . $language['language_id'] . '_category_product.xml';
					} else {
						$file_name = 'sitemap_' . $store['store_id'] . '_' . $language['language_id'] . '_category_product_' . $count . '.xml';
					}

					$count++;

					$xml_file = fopen($this->directory . $file_name, 'w') or die('Unable to open file!');

					fwrite($xml_file, $output);
					fclose($xml_file);

					$this->files[] = 'sitemaps/' . $file_name;
				}
			}
		}
	}

	/**
	 * Generate manufacturer to product sitemap
	 *
	 * @access protected
	 * @param int $limit
	 * @return void
	 */
	protected function generateManufacturerToProductSitemap($limit = null) {
		$this->load->model('extension/feed/boost_sitemap');

		if (version_compare(VERSION, '2.2.0.0', '<')) {
			$image_width = $this->config->get('config_image_popup_width');
			$image_height = $this->config->get('config_image_popup_height');
		} else {
			$image_width = $this->config->get($this->config->get('config_theme') . '_image_popup_width');
			$image_height = $this->config->get($this->config->get('config_theme') . '_image_popup_height');
		}

		foreach ($this->stores as $store) {
			$products = [];
			$manufacturers = $this->model_extension_feed_boost_sitemap->getManufacturers(['store_id' => $store['store_id']]);

			foreach ($this->languages as $language) {
				foreach ($manufacturers as $manufacturer) {
					$params = [
						'store_id' => $store['store_id'],
						'language_id' => $language['language_id'],
						'filter_manufacturer_id' => $manufacturer['manufacturer_id']
					];

					foreach ($this->model_extension_feed_boost_sitemap->getProducts($params) as $product) {
						$products[$language['language_id']][] = $product;
					}
				}
			}

			foreach ($this->languages as $language) {
				if (isset($products[$language['language_id']])) {
					$results = array_chunk($products[$language['language_id']], $limit);
					$count = 1;

					foreach ($results as $result) {
						$output  = '<?xml version="1.0" encoding="UTF-8"?>';
						$output .= '<urlset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd" xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">';

						foreach ($result as $product) {
							$output .= '<url>';
							$output .= '  <loc>' . $this->link($store['url'], 'product/product', 'manufacturer_id=' . $product['manufacturer_id'] . '&product_id=' . $product['product_id'], $store['store_id'], $language['language_id']) . '</loc>';
							$output .= '  <changefreq>weekly</changefreq>';
							$output .= '  <priority>1.0</priority>';

							if ($product['image']) {
								$output .= '  <image:image>';
								$output .= '  <image:loc>' . $this->model_extension_feed_boost_sitemap->resizeImage($product['image'], $image_width, $image_height, $store['url']) . '</image:loc>';
								$output .= '  <image:caption>' . $product['name'] . '</image:caption>';
								$output .= '  <image:title>' . $product['name'] . '</image:title>';
								$output .= '  </image:image>';
							}

							$output .= '</url>';
						}

						$output .= '</urlset>';

						if (count($products[$language['language_id']]) <= $limit) {
							$file_name = 'sitemap_' . $store['store_id'] . '_' . $language['language_id'] . '_manufacturer_product.xml';
						} else {
							$file_name = 'sitemap_' . $store['store_id'] . '_' . $language['language_id'] . '_manufacturer_product_' . $count . '.xml';
						}

						$count++;

						$xml_file = fopen($this->directory . $file_name, 'w') or die('Unable to open file!');

						fwrite($xml_file, $output);
						fclose($xml_file);

						$this->files[] = 'sitemaps/' . $file_name;
					}
				}
			}
		}
	}

	/**
	 * Generate information sitemap
	 *
	 * @access protected
	 * @param int $limit
	 * @return void
	 */
	protected function generateInformationSitemap($limit = null) {
		$this->load->model('extension/feed/boost_sitemap');

		foreach ($this->stores as $store) {
			foreach ($this->languages as $language) {
				$generated_files = array();
				$total = $this->model_extension_feed_boost_sitemap->getTotalInformations(array(
					'store_id' => $store['store_id'],
					'language_id' => $language['language_id']
				));

				if ($total && $limit) {
					if ($total > $limit) {
						$total_pages = ceil($total / $limit);
					} else {
						$total_pages = 1;
					}

					for ($i = 1; $i <= $total_pages; $i++) {
						$output  = '<?xml version="1.0" encoding="UTF-8"?>';
						$output .= '<urlset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd" xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

						$params = [
							'store_id' => $store['store_id'],
							'language_id' => $language['language_id'],
							'start' => ($i - 1) * $limit,
							'limit' => $limit
						];

						$informations = $this->model_extension_feed_boost_sitemap->getInformations($params);

						foreach ($informations as $information) {
							$output .= '<url>';
							$output .= '  <loc>' . $this->xmlValue($this->link($store['url'], 'information/information', 'information_id=' . $information['information_id'], $store['store_id'], $language['language_id'])) . '</loc>';
							$output .= '  <changefreq>monthly</changefreq>';
							$output .= '  <priority>0.5</priority>';
							$output .= '</url>';
						}

						$output .= '</urlset>';

						if ($total_pages == 1) {
							$file_name = 'sitemap_' . $store['store_id'] . '_' . $language['language_id'] . '_information.xml';
						} else {
							$file_name = 'sitemap_' . $store['store_id'] . '_' . $language['language_id'] . '_information_' . $i . '.xml';
						}

						$this->writeSitemapFile($file_name, $output);
						$generated_files[] = $file_name;

						$this->files[] = 'sitemaps/' . $file_name;
					}
				}

				if ($limit) {
					$this->removeStaleSitemapFiles($store['store_id'], $language['language_id'], 'information', $generated_files);
				}
			}
		}
	}

	/**
	 * Generate manufacturer sitemap
	 *
	 * @access protected
	 * @param int $limit
	 * @return void
	 */
	protected function generateManufacturerSitemap($limit = null) {
		$this->load->model('extension/feed/boost_sitemap');

		if (version_compare(VERSION, '2.2.0.0', '<')) {
			$image_width = $this->config->get('config_image_popup_width');
			$image_height = $this->config->get('config_image_popup_height');
		} else {
			$image_width = $this->config->get($this->config->get('config_theme') . '_image_popup_width');
			$image_height = $this->config->get($this->config->get('config_theme') . '_image_popup_height');
		}

		foreach ($this->stores as $store) {
			foreach ($this->languages as $language) {
				$generated_files = array();
				$total = $this->model_extension_feed_boost_sitemap->getTotalManufacturers(array('store_id' => $store['store_id']));

				if ($total && $limit) {
					if ($total > $limit) {
						$total_pages = ceil($total / $limit);
					} else {
						$total_pages = 1;
					}

					for ($i = 1; $i <= $total_pages; $i++) {
						$output  = '<?xml version="1.0" encoding="UTF-8"?>';
						$output .= '<urlset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd" xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">';

						$params = [
							'store_id' => $store['store_id'],
							'start' => ($i - 1) * $limit,
							'limit' => $limit
						];

						$manufacturers = $this->model_extension_feed_boost_sitemap->getManufacturers($params);

						foreach ($manufacturers as $manufacturer) {
							$output .= '<url>';
							$output .= '  <loc>' . $this->xmlValue($this->link($store['url'], 'product/manufacturer/info', 'manufacturer_id=' . $manufacturer['manufacturer_id'], $store['store_id'], $language['language_id'])) . '</loc>';
							$output .= '  <changefreq>monthly</changefreq>';
							$output .= '  <priority>0.5</priority>';

							$manufacturer_image = $manufacturer['image'] ? $this->model_extension_feed_boost_sitemap->resizeImage($manufacturer['image'], $image_width, $image_height, $store['url']) : '';
							if ($manufacturer_image) {
								$output .= '  <image:image>';
								$output .= '  <image:loc>' . $this->xmlValue($manufacturer_image) . '</image:loc>';
								$output .= '  <image:caption>' . $this->xmlValue($manufacturer['name']) . '</image:caption>';
								$output .= '  <image:title>' . $this->xmlValue($manufacturer['name']) . '</image:title>';
								$output .= '  </image:image>';
							}

							$output .= '</url>';
						}

						$output .= '</urlset>';

						if ($total_pages == 1) {
							$file_name = 'sitemap_' . $store['store_id'] . '_' . $language['language_id'] . '_manufacturer.xml';
						} else {
							$file_name = 'sitemap_' . $store['store_id'] . '_' . $language['language_id'] . '_manufacturer_' . $i . '.xml';
						}

						$this->writeSitemapFile($file_name, $output);
						$generated_files[] = $file_name;

						$this->files[] = 'sitemaps/' . $file_name;
					}
				}

				if ($limit) {
					$this->removeStaleSitemapFiles($store['store_id'], $language['language_id'], 'manufacturer', $generated_files);
				}
			}
		}
	}

	/**
	 * Generate product sitemap
	 *
	 * @access protected
	 * @param int $limit
	 * @return void
	 */
	protected function generateProductSitemap($limit = null) {
		$this->load->model('extension/feed/boost_sitemap');

		if (version_compare(VERSION, '2.2.0.0', '<')) {
			$image_width = $this->config->get('config_image_popup_width');
			$image_height = $this->config->get('config_image_popup_height');
		} else {
			$image_width = $this->config->get($this->config->get('config_theme') . '_image_popup_width');
			$image_height = $this->config->get($this->config->get('config_theme') . '_image_popup_height');
		}

		foreach ($this->stores as $store) {
			foreach ($this->languages as $language) {
				$generated_files = array();
				$total = $this->model_extension_feed_boost_sitemap->getTotalProducts(array(
					'store_id' => $store['store_id'],
					'language_id' => $language['language_id']
				));

				if ($total && $limit) {
					if ($total > $limit) {
						$total_pages = ceil($total / $limit);
					} else {
						$total_pages = 1;
					}

					for ($i = 1; $i <= $total_pages; $i++) {
						$output  = '<?xml version="1.0" encoding="UTF-8"?>';
						$output .= '<urlset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd" xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">';

						$params = [
							'store_id' => $store['store_id'],
							'language_id' => $language['language_id'],
							'start' => ($i - 1) * $limit,
							'limit' => $limit
						];

							$products = $this->model_extension_feed_boost_sitemap->getProducts($params);
							$product_ids = array_column($products, 'product_id');
							$product_arguments = $this->getProductCanonicalArgumentsBatch($product_ids, $store['store_id']);

							foreach ($products as $product) {
								$output .= '<url>';
								$product_args = $product_arguments[(int)$product['product_id']];
								$product_url = $this->link($store['url'], 'product/product', $product_args, $store['store_id'], $language['language_id']);
								$output .= '  <loc>' . $this->xmlValue($product_url) . '</loc>';
								$output .= '  <changefreq>weekly</changefreq>';
								$lastmod = $this->sitemapDate($product['date_modified']);
								if ($lastmod) {
									$output .= '  <lastmod>' . $lastmod . '</lastmod>';
								}
								$output .= '  <priority>1.0</priority>';

								$product_image = $product['image'] ? $this->model_extension_feed_boost_sitemap->resizeImage($product['image'], $image_width, $image_height, $store['url']) : '';
								if ($product_image) {
									$output .= '  <image:image>';
									$output .= '  <image:loc>' . $this->xmlValue($product_image) . '</image:loc>';
									$output .= '  <image:caption>' . $this->xmlValue($product['name']) . '</image:caption>';
									$output .= '  <image:title>' . $this->xmlValue($product['name']) . '</image:title>';
									$output .= '  </image:image>';
								}

								$output .= '</url>';
							}

						$output .= '</urlset>';

						if ($total_pages == 1) {
							$file_name = 'sitemap_' . $store['store_id'] . '_' . $language['language_id'] . '_product.xml';
						} else {
							$file_name = 'sitemap_' . $store['store_id'] . '_' . $language['language_id'] . '_product_' . $i . '.xml';
						}

						$this->writeSitemapFile($file_name, $output);
						$generated_files[] = $file_name;

						$this->files[] = 'sitemaps/' . $file_name;
					}
				}

				if ($limit) {
					$this->removeStaleSitemapFiles($store['store_id'], $language['language_id'], 'product', $generated_files);
				}
			}
		}
	}

	/**
	 * Generate category sitemap
	 *
	 * @access protected
	 * @param int $limit
	 * @return void
	 */
	protected function generateCategorySitemap($limit = null) {
		$this->load->model('extension/feed/boost_sitemap');

		if (version_compare(VERSION, '2.2.0.0', '<')) {
			$image_width = $this->config->get('config_image_popup_width');
			$image_height = $this->config->get('config_image_popup_height');
		} else {
			$image_width = $this->config->get($this->config->get('config_theme') . '_image_popup_width');
			$image_height = $this->config->get($this->config->get('config_theme') . '_image_popup_height');
		}

		foreach ($this->stores as $store) {
			foreach ($this->languages as $language) {
				$generated_files = array();
				$total = $this->model_extension_feed_boost_sitemap->getTotalCategories(array(
					'store_id' => $store['store_id'],
					'language_id' => $language['language_id']
				));

				if ($total && $limit) {
					if ($total > $limit) {
						$total_pages = ceil($total / $limit);
					} else {
						$total_pages = 1;
					}

					for ($i = 1; $i <= $total_pages; $i++) {
						$output  = '<?xml version="1.0" encoding="UTF-8"?>';
						$output .= '<urlset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd" xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">';

						$params = [
							'store_id' => $store['store_id'],
							'language_id' => $language['language_id'],
							'start' => ($i - 1) * $limit,
							'limit' => $limit
						];

						$categories = $this->model_extension_feed_boost_sitemap->getCategories($params);

							foreach ($categories as $category) {
								$output .= '<url>';
								$category_args = $this->getCategoryCanonicalArguments($category['category_id'], $store['store_id']);
								$category_url = $this->link($store['url'], 'product/category', $category_args, $store['store_id'], $language['language_id']);
								$output .= '  <loc>' . $this->xmlValue($category_url) . '</loc>';
								$output .= '  <changefreq>weekly</changefreq>';
								$lastmod = $this->sitemapDate($category['date_modified']);
								if ($lastmod) {
									$output .= '  <lastmod>' . $lastmod . '</lastmod>';
								}
								$output .= '  <priority>0.5</priority>';

							$category_image = $category['image'] ? $this->model_extension_feed_boost_sitemap->resizeImage($category['image'], $image_width, $image_height, $store['url']) : '';
							if ($category_image) {
								$output .= '  <image:image>';
								$output .= '  <image:loc>' . $this->xmlValue($category_image) . '</image:loc>';
									$output .= '  <image:caption>' . $this->xmlValue($category['name']) . '</image:caption>';
									$output .= '  <image:title>' . $this->xmlValue($category['name']) . '</image:title>';
								$output .= '  </image:image>';
							}

							$output .= '</url>';
						}

						$output .= '</urlset>';

						if ($total_pages == 1) {
							$file_name = 'sitemap_' . $store['store_id'] . '_' . $language['language_id'] . '_category.xml';
						} else {
							$file_name = 'sitemap_' . $store['store_id'] . '_' . $language['language_id'] . '_category_' . $i . '.xml';
						}

						$this->writeSitemapFile($file_name, $output);
						$generated_files[] = $file_name;

						$this->files[] = 'sitemaps/' . $file_name;
					}
				}

				if ($limit) {
					$this->removeStaleSitemapFiles($store['store_id'], $language['language_id'], 'category', $generated_files);
				}
			}
		}
	}

	/**
	 * Generate custom link sitemap
	 *
	 * @access protected
	 * @param int $limit
	 * @return void
	 */
	protected function generateCustomLinkSitemap($limit = null) {
		$this->load->model('extension/feed/boost_sitemap');

		foreach ($this->stores as $store) {
			$generated_files = array();
			//foreach ($this->languages as $language) {
				$total = $this->model_extension_feed_boost_sitemap->getTotalCustomLinks(array(
					'store_id' => $store['store_id']
				));

				if ($total && $limit) {
					if ($total > $limit) {
						$total_pages = ceil($total / $limit);
					} else {
						$total_pages = 1;
					}

					for ($i = 1; $i <= $total_pages; $i++) {
						$output  = '<?xml version="1.0" encoding="UTF-8"?>';
						$output .= '<urlset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd" xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">';

						$params = [
							'store_id' => $store['store_id'],
							'start' => ($i - 1) * $limit,
							'limit' => $limit
						];

						$custom_links = $this->model_extension_feed_boost_sitemap->getCustomLinks($params);

						foreach ($custom_links as $custom_link) {
							$frequency = in_array($custom_link['frequency'], array('always', 'hourly', 'daily', 'weekly', 'monthly', 'yearly', 'never'), true) ? $custom_link['frequency'] : 'monthly';
							$priority = isset($custom_link['priority']) && is_numeric($custom_link['priority']) ? min(1, max(0, (float)$custom_link['priority'])) : 0.5;
							$lastmod = $this->sitemapDate($custom_link['date_added']);
							$output .= '<url>';
							$output .= '  <loc>' . $this->xmlValue($custom_link['url']) . '</loc>';
							$output .= '  <changefreq>' . $frequency . '</changefreq>';
							if ($lastmod) {
								$output .= '  <lastmod>' . $lastmod . '</lastmod>';
							}
							$output .= '  <priority>' . number_format($priority, 1, '.', '') . '</priority>';
							$output .= '</url>';
						}

						$output .= '</urlset>';

						if ($total_pages == 1) {
							//$file_name = 'sitemap_' . $store['store_id'] . '_' . $language['language_id'] . '_custom_link.xml';
							$file_name = 'sitemap_' . $store['store_id'] . '_custom_link.xml';
						} else {
							//$file_name = 'sitemap_' . $store['store_id'] . '_' . $language['language_id'] . '_custom_link_' . $i . '.xml';
							$file_name = 'sitemap_' . $store['store_id'] . '_custom_link_' . $i . '.xml';
						}

						$this->writeSitemapFile($file_name, $output);
						$generated_files[] = $file_name;

						$this->files[] = 'sitemaps/' . $file_name;
					}
				}

				if ($limit) {
					$this->removeStaleSitemapFiles($store['store_id'], null, 'custom_link', $generated_files);
				}
			//}
		}
	}

	/**
	 * Generate the canonical blog home and article URLs. Tag and archive
	 * variants are intentionally excluded because they are noindex pages.
	 */
	protected function generateBlogSitemap($limit = null) {
		$this->load->model('extension/feed/boost_sitemap');

		foreach ($this->stores as $store) {
			foreach ($this->languages as $language) {
				$generated_files = array();
				$total = $this->model_extension_feed_boost_sitemap->getTotalBlogs(array(
					'store_id' => $store['store_id'],
					'language_id' => $language['language_id']
				));

				if ($limit) {
					$total_pages = max(1, (int)ceil($total / $limit));

					for ($i = 1; $i <= $total_pages; $i++) {
						$output = '<?xml version="1.0" encoding="UTF-8"?>';
						$output .= '<urlset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd" xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

						if ($i === 1) {
							$output .= '<url>';
							$output .= '<loc>' . $this->xmlValue($this->link($store['url'], 'extension/blog/home')) . '</loc>';
							$output .= '<changefreq>weekly</changefreq><priority>0.6</priority>';
							$output .= '</url>';
						}

						$blogs = $this->model_extension_feed_boost_sitemap->getBlogs(array(
							'store_id' => $store['store_id'],
							'language_id' => $language['language_id'],
							'start' => ($i - 1) * $limit,
							'limit' => $limit
						));

						foreach ($blogs as $blog) {
							$output .= '<url>';
							$output .= '<loc>' . $this->xmlValue($this->link($store['url'], 'extension/blog/blog', 'blog_id=' . (int)$blog['blog_id'])) . '</loc>';
							$lastmod = $this->sitemapDate($blog['date_added']);
							if ($lastmod) {
								$output .= '<lastmod>' . $lastmod . '</lastmod>';
							}
							$output .= '<changefreq>monthly</changefreq><priority>0.5</priority>';
							$output .= '</url>';
						}

						$output .= '</urlset>';
						$file_name = $total_pages === 1
							? 'sitemap_' . $store['store_id'] . '_' . $language['language_id'] . '_blog.xml'
							: 'sitemap_' . $store['store_id'] . '_' . $language['language_id'] . '_blog_' . $i . '.xml';

						$this->writeSitemapFile($file_name, $output);
						$generated_files[] = $file_name;
						$this->files[] = 'sitemaps/' . $file_name;
					}

					$this->removeStaleSitemapFiles($store['store_id'], $language['language_id'], 'blog', $generated_files);
				}
			}
		}
	}

	/**
	 * Link
	 *
	 * @access protected
	 * @param string $url
	 * @param string $route
	 * @param string $args
	 * @return string

	 protected function link($url, $route, $args = '', $store_id = 0, $language_id = 0)
	 */

	protected function link($url, $route, $args = '') {
		$url = $url . 'index.php?route=' . $route;

		if ($args) {
			if (is_array($args)) {
				$url .= '&amp;' . http_build_query($args);
			} else {
				$url .= str_replace('&', '&amp;', '&' . ltrim($args, '&'));
			}
		}

		if ($this->config->get('config_seo_url')) {
			$url = $this->rewrite($url);
		}

		return $url;
	}


	/**
	 * Rewrite
	 *
	 * @access public
	 * @param mixed $link
	 * @param int $store_id
	 * @param int $language_id
	 * @return void
	 */
	public function rewrite($link) {
		$url_info = parse_url(str_replace('&amp;', '&', $link));

		$url = '';

		$data = array();

		parse_str($url_info['query'], $data);

		foreach ($data as $key => $value) {
			if (isset($data['route'])) {
					if (($data['route'] == 'product/product' && $key == 'product_id') || (($data['route'] == 'product/manufacturer/info' || $data['route'] == 'product/product') && $key == 'manufacturer_id') || ($data['route'] == 'information/information' && $key == 'information_id') || ($data['route'] == 'extension/blog/blog' && $key == 'blog_id')) {
					$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE `query` = '" . $this->db->escape($key . '=' . (int)$value) . "'");

					if ($query->num_rows && $query->row['keyword']) {
						$url .= '/' . $query->row['keyword'];

						unset($data[$key]);
					}
				} elseif ($key == 'path') {
					$categories = explode('_', $value);

					foreach ($categories as $category) {
						$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE `query` = 'category_id=" . (int)$category . "'");

						if ($query->num_rows && $query->row['keyword']) {
							$url .= '/' . $query->row['keyword'];
						} else {
							$url = '';

							break;
						}
					}

					unset($data[$key]);
					} elseif ($key == 'route' && $data['route'] == 'extension/blog/home') {
						$query = $this->db->query("SELECT keyword FROM " . DB_PREFIX . "url_alias WHERE `query` = 'extension/blog/home' LIMIT 1");
						if ($query->row && $query->row['keyword']) {
							$url = '/' . $query->row['keyword'];
							unset($data[$key]);
						}
					} elseif ($data['route'] == 'extension/feed/boost_sitemap') {
					$url = '/sitemap-index.xml';

					unset($data[$key]);
				}
			}
		}

		if ($url) {
			unset($data['route']);

			$query = '';

			if ($data) {
				foreach ($data as $key => $value) {
					$query .= '&' . rawurlencode((string)$key) . '=' . rawurlencode((is_array($value) ? http_build_query($value) : (string)$value));
				}

				if ($query) {
					$query = '?' . str_replace('&', '&amp;', trim($query, '&'));
				}
			}

			return $url_info['scheme'] . '://' . $url_info['host'] . (isset($url_info['port']) ? ':' . $url_info['port'] : '') . str_replace('/index.php', '', $url_info['path']) . $url . $query;
		} else {
			return $link;
		}
	}

	/**
	 * Delete custom link
	 *
	 * @access public
	 * @return void
	 */
	public function delete_custom_link() {
		$json = array();

		$this->load->model('extension/feed/boost_sitemap');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			if (isset($this->request->post['custom_link_ids'])) {
				foreach ($this->request->post['custom_link_ids'] as $custom_link_id) {
					$this->model_extension_feed_boost_sitemap->deleteCustomLink($custom_link_id);
				}
			}
		}

		$this->response->setOutput(json_encode($json));
	}

	/**
	 * Custom link
	 *
	 * @access public
	 * @return void
	 */
	public function custom_link() {
		$this->load->model('extension/feed/boost_sitemap');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			if (isset($this->request->post['custom_link_url']) && isset($this->request->post['custom_link_frequency']) && isset($this->request->post['custom_link_priority']) && isset($this->request->post['custom_link_store_id'])) {
				$data['url'] = $this->request->post['custom_link_url'];
				$data['frequency'] = $this->request->post['custom_link_frequency'];
				$data['priority'] = $this->request->post['custom_link_priority'];
				$data['store_id'] = (int)$this->request->post['custom_link_store_id'];

				$this->model_extension_feed_boost_sitemap->addCustomLink($data);
			}
		} else {
			$custom_links = $this->model_extension_feed_boost_sitemap->getCustomLinks();

			$html = '';

			if ($custom_links) {
				foreach ($custom_links as $custom_link) {
					$html .= '<tr>';
					$html .= '<td><input type="checkbox" name="custom_link_ids[]" value="' . (int)$custom_link['boost_sitemap_custom_link_id'] . '" /></td>';
					$html .= '<td>' . ($custom_link['store_name'] ? $custom_link['store_name'] : 'Default') . '</td>';
					$html .= '<td>' . $custom_link['url'] . '</td>';
					$html .= '<td>' . $custom_link['frequency'] . '</td>';
					$html .= '<td>' . $custom_link['priority'] . '</td>';
					$html .= '</tr>';
				}
			} else {
				$html .= '<tr><td colspan="4" class="text-center">' . $this->language->get('text_empty') . '</td></tr>';
			}

			$this->response->addHeader('Content-Type: text/html; charset=UTF-8');
			$this->response->setOutput($html);
		}
	}
}
