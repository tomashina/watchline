<?php

require_once(DIR_SYSTEM . 'library/trustpilot/config.php');
require_once(DIR_SYSTEM . 'library/trustpilot/trustpilot_http_client.php');

class TrustpilotHelper {
    private $registry, $log, $trustpilot_api = null;
    
    protected static $instance = null;

    public static function getInstance($registry) {
        if ( null == self::$instance ) {
            self::$instance = new self($registry);
        }
        return self::$instance;
    }

    public function __construct($registry) {
        $this->registry = $registry;
        $this->log = new Log('error.log');
    }

    public function isV21() {
        return version_compare(VERSION, '2.2', '<');
    }

    public function isV22() {
        return version_compare(VERSION, '2.3', '<');
    }

    public function isV23() {
        return version_compare(VERSION, '3.0', '<') &&
               version_compare(VERSION, '2.3', '>=');
    }

    public function isV3() {
        return version_compare(VERSION, '3.0', '>=');
    }

    public function versionSafePath($path) {
        if ($this->isV22()) {
            return str_replace('extension/', '', $path);
        } else {
            return $path;
        }
    }

    public function versionSafeViewPath($path) {
        if ($this->isV22()) {
            return str_replace('extension/', '', $path) . '.tpl';
        } else {
            return $path;
        }
    }

    private function getTrustpilotSetting() {
        $this->registry->get('load')->model('setting/setting');
        $model_setting_setting = $this->registry->get('model_setting_setting');
        return $model_setting_setting->getSetting('trustpilot');
    }

    public function getTrustpilotField($key, $json = true) {
        $settings = $this->getTrustpilotSetting();
        if (array_key_exists($key, $settings)) {
            if ($json) {
                return json_decode($settings[$key]);
            }
            return $settings[$key];
        } else {
            return '';
        }
    }

    public function setTrustpilotField($key, $value, $json = true) {
        $this->registry->get('load')->model('setting/setting');
        $model_setting_setting = $this->registry->get('model_setting_setting');

        $settings = $model_setting_setting->getSetting('trustpilot');
        $settings[$key] = $value;
        if (method_exists('ModelSettingSetting', 'editSetting')) {
            $model_setting_setting->editSetting('trustpilot', $settings);
        } else {
            $this->registry->get('load')->model($this->versionSafePath('extension/module/trustpilot/setting'));
            $model_trustpilot_setting = $this->registry->get('model_extension_module_trustpilot_setting');
            $model_trustpilot_setting->editSetting('trustpilot', $settings);
        }
        return $this->getTrustpilotField($key, $json);
    }

    public function getProducts($limit = null) {
        $this->registry->get('load')->model('catalog/product');
        $model_catalog_product = $this->registry->get('model_catalog_product');
        $filter_data = array(
            'filter_status' => '1',
        );
        if ($limit) {
            $filter_data['start'] = 0;
            $filter_data['limit'] = $limit;
        }
        return $model_catalog_product->getProducts($filter_data);
    }

    public function getFirstCategoryId() {
        $product_ids = array_map(function ($a) { return (int)$a['product_id']; },
            $this->getProducts(100));
        $sql = "SELECT DISTINCT cp.category_id  AS category_id
                FROM " . DB_PREFIX . "category_path cp
                LEFT JOIN " . DB_PREFIX . "product_to_category ptc
                ON (cp.category_id = ptc.category_id)
                WHERE  ptc.product_id IN (".implode(',', $product_ids).")";
        $query = $this->registry->get('db')->query($sql);
        $category = $query->rows[0];

        return $category['category_id'];
    }

    public function loadTrustboxes($settings, $request) {
        if (isset($settings->trustboxes)) {
            $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
            $current_url = $protocol . trim($_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"], "/");
            $current_url_trustboxes = $this->getAvailableTrustboxesByPage($settings, $current_url, $request);

            if (isset($request->get['route']) && $request->get['route'] == 'product/product') {
                $current_url_trustboxes = array_merge((array) $this->getAvailableTrustboxesByPage($settings, 'product', $request), (array) $current_url_trustboxes);
            }
            if (isset($request->get['route']) && $request->get['route'] == 'product/category') {
                $current_url_trustboxes = array_merge((array) $this->getAvailableTrustboxesByPage($settings, 'category', $request),(array) $current_url_trustboxes);
                if ($this->repeatData($current_url_trustboxes)) {
                    $settings->categoryProductsData = $this->_helper->loadCategoryProductInfo($request);
                }
            }
            if (!isset($request->get['route']) || $request->get['route'] == 'common/home') {
                $current_url_trustboxes = array_merge((array) $this->getAvailableTrustboxesByPage($settings, 'landing', $request),(array) $current_url_trustboxes);
            }
            
            if (count($current_url_trustboxes) > 0) {
                $settings->trustboxes = $current_url_trustboxes;
                return json_encode($settings);
            }
        }
        return '{"trustboxes":[]}';
    }

    private function repeatData($trustBoxes) {
        foreach ($trustBoxes as $trustbox) {
            if (isset($trustbox->repeat)) {
                return true;
            }
        }
        return false;
    }

    public function loadCategoryProductInfo($request = null) {
        try {
            $this->registry->get('load')->model('catalog/product');
            $model_catalog_product = $this->registry->get('model_catalog_product');
            $config = $this->registry->get('config');

            if (isset($request) && isset($request->get['path'])) {
                $parts = explode('_', (string)$request->get['path']);
                $category_id = (int)array_pop($parts);
            } else {
                $category_id = $this->getFirstCategoryId();
            }

            if (isset($request) && isset($request->get['filter'])) {
                $filter = $request->get['filter'];
            } else {
                $filter = '';
            }

            if (isset($request) && isset($request->get['sort'])) {
                $sort = $request->get['sort'];
            } else {
                $sort = 'p.sort_order';
            }

            if (isset($request) && isset($request->get['order'])) {
                $order = $request->get['order'];
            } else {
                $order = 'ASC';
            }
            
            if (isset($request) && isset($request->get['page'])) {
                $page = (int)$request->get['page'];
            } else {
                $page = 1;
            }

            if (isset($request) && isset($request->get['limit'])) {
                $limit = (int)$request->get['limit'];
            } else {
                if ($this->isV21()) {
                    $limit = $config->get('config_product_limit');
                } else if ($this->isV22()){
                    $limit = $config->get($config->get('config_theme') . '_product_limit');
                } else if ($this->isV3()) {
                    $limit = $config->get('theme_' . $config->get('config_theme') . '_pagination');
                } else {
                    $limit = 15;
                }
            }

            $filter_data = array(
                'filter_category_id'  => $category_id,
                'filter_sub_category' => $config->get('config_product_category') ? true : false,
                'filter_filter'       => $filter,
                'sort'                => $sort,
                'order'               => $order,
                'start'               => ($page - 1) * $limit,
                'limit'               => $limit
            );

            $products = $model_catalog_product->getProducts($filter_data);
            $master_settings = json_decode($config->get('trustpilot_master_settings_field'));
            $productList = array();
            $skuSelector = $master_settings->skuSelector;
            foreach ($products as $product) {
                $sku = $skuSelector != 'none' && $skuSelector != '' && array_key_exists($skuSelector, $product) ? $product[$skuSelector] : '';   
                array_push($productList, array(
                    "sku" => $sku,
                    "id" => $product['product_id'],
                    "productUrl" => $this->registry->get('url')->link('product/product&path=' . $category_id . '&product_id=' . $product['product_id']) ?: '',
                    "name" => $product['name'],
                ));
            }

            return $productList;
        } catch (Exception $e) {
            $message = 'Failed to collect category product data. Error: ' . $e->getMessage();
            $this->log($message);
            return array();
        }
    }

    public function getAvailableTrustboxesByPage($settings, $page, $request) {
        $data = array();
        foreach ($settings->trustboxes as $trustbox) {
            if ((rtrim($trustbox->page, '/') == $page || $this->checkCustomPage($trustbox->page, $page)) && $trustbox->enabled == 'enabled') {
                if (isset($request->get['route']) && $request->get['route'] == 'product/product') {
                    $this->registry->get('load')->model('catalog/product');
                    $product = $this->registry->get('model_catalog_product')->getProduct($request->get['product_id']);
                    $skuSelector = json_decode($this->registry->get('config')->get('trustpilot_master_settings_field'))->skuSelector;
                    $sku = ($skuSelector != 'none' && $skuSelector != '' && isset($product[$skuSelector]))
                        ? $product[$skuSelector] 
                        : '';
                    $trustbox->sku = $sku;
                    $trustbox->name = $product['name'];
                } else {
                    unset($trustbox->sku);
                    unset($trustbox->name);
                }
                array_push($data, $trustbox);
            }
        }
        return $data;
    }

    private function checkCustomPage($tbPage, $page) {
        return (
            $tbPage == strtolower(base64_encode($page . '/')) ||
            $tbPage == strtolower(base64_encode($page)) ||
            $tbPage == strtolower(base64_encode(rtrim($page, '/')))
        );
    }
    
    public function link($route, $base, $args = '') {
        $url = $base . 'index.php?route=' . (string)$route;
        if ($args) {
            if (is_array($args)) {
                $url .= '&amp;' . http_build_query($args, '', '&amp;');
            } else {
                $url .= str_replace('&', '&amp;', '&' . ltrim($args, '&'));
            }
        }
        return $url;
    }

    public function getBaseUrl() {
        if (defined('HTTP_CATALOG')) {
            return HTTP_CATALOG;
        } else {
            return HTTP_SERVER;
        }
    }

    public function getConfigurationScopeTree() {
        $this->registry->get('load')->model('setting/store');
        $this->registry->get('load')->model('localisation/language');
        $configurationScopeTree = array();

        $stores = $this->registry->get('model_setting_store')->getStores();
        array_unshift($stores, array(
            'store_id' => '0',
            'name' => $this->registry->get('config')->get('config_name'),
            'url' => HTTP_CATALOG,
        ));

        $languages = $this->registry->get('model_localisation_language')->getLanguages();
        foreach ($stores as $store) {
            foreach ($languages as $language) {
                if ($language['status'] == 1) {
                    array_push($configurationScopeTree, array(
                        'ids' => array($store['store_id'], $language['language_id']),
                        'names' => array(
                            'store' => $store['name'],
                            'view' => $language['name'],
                        ),
                        'domain' => parse_url($store['url'], PHP_URL_HOST),
                    ));
                }
            }
        }

        return $configurationScopeTree;
    }

    public function log($message, $key = '') {
        try {
            $this->log->write($message);

            $trustpilot_api = new TrustpilotHttpClient(TRUSTPILOT_API_URL, $this->registry, $this->getBaseUrl());
            $data = array(
                'platform' => 'OpenCart-' . VERSION,
                'version'  => TRUSTPILOT_PLUGIN_VERSION,
                'key'      => $key,
                'message'  => $message,
            );
            $trustpilot_api->postLog($data);
        } catch (Exception $e) { /* empty on purpose */ }
    }

    public function offlineLog($message) {
        try {
            $this->log->write($message);
        } catch (Exception $e) { /* empty on purpose */ }
    }
}
