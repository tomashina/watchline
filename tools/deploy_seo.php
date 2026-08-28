#!/usr/bin/env php
<?php
/**
 * Watchline SEO deployment finalizer.
 *
 * Run after pulling the reviewed code and after taking a full database/files
 * backup:
 *   php tools/deploy_seo.php
 *
 * It applies the idempotent alias migration, synchronizes the two modified
 * OCMOD XML records, refreshes OCMOD, clears VQMod caches, regenerates the
 * configured canonical sitemaps and executes HTTP/XML/JSON-LD smoke tests.
 */

if (PHP_SAPI !== 'cli') {
	http_response_code(404);
	exit(1);
}

error_reporting(E_ALL & ~E_DEPRECATED & ~E_NOTICE);
ini_set('display_errors', '1');

$project_root = dirname(__DIR__);
$admin_config = $project_root . '/admin/config.php';

if (!is_file($admin_config)) {
	fwrite(STDERR, "Missing admin/config.php\n");
	exit(1);
}

require $admin_config;

if (!defined('VERSION')) {
	define('VERSION', '2.3.0.2');
}

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$mysqli = new mysqli(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE, DB_PORT);
$mysqli->set_charset('utf8mb4');

function failDeploy($message) {
	fwrite(STDERR, "ERROR: " . $message . "\n");
	exit(1);
}

function deployStep($message) {
	echo "\n== " . $message . " ==\n";
}

function validXmlDocument($xml, $label) {
	$previous = libxml_use_internal_errors(true);
	$document = new DOMDocument();
	$valid = $document->loadXML($xml);
	libxml_clear_errors();
	libxml_use_internal_errors($previous);

	if (!$valid || $document->documentElement->nodeName !== 'modification') {
		failDeploy($label . ' is not a valid OCMOD XML document.');
	}
}

function recursiveFileCount($directory) {
	if (!is_dir($directory)) {
		return 0;
	}

	$count = 0;
	$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory, FilesystemIterator::SKIP_DOTS));

	foreach ($iterator as $file) {
		if ($file->isFile() && $file->getFilename() !== 'index.html') {
			$count++;
		}
	}

	return $count;
}

function clearVqmodCache($project_root) {
	$cache_directory = $project_root . '/vqmod/vqcache';
	$files = is_dir($cache_directory) ? glob($cache_directory . '/vq2-*') : array();

	foreach ($files ?: array() as $file) {
		if (is_file($file)) {
			unlink($file);
		}
	}

	foreach (array($project_root . '/vqmod/mods.cache', $project_root . '/vqmod/checked.cache') as $file) {
		if (is_file($file)) {
			unlink($file);
		}
	}
}

function httpRequest($url) {
	$handle = curl_init($url);
	curl_setopt_array($handle, array(
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_FOLLOWLOCATION => false,
		CURLOPT_CONNECTTIMEOUT => 10,
		CURLOPT_TIMEOUT => 30,
		CURLOPT_ENCODING => '',
		CURLOPT_USERAGENT => 'Watchline-SEO-Deploy/1.0'
	));
	$body = curl_exec($handle);

	if ($body === false) {
		$error = curl_error($handle);
		curl_close($handle);
		failDeploy('HTTP request failed for ' . $url . ': ' . $error);
	}

	$result = array(
		'status' => (int)curl_getinfo($handle, CURLINFO_RESPONSE_CODE),
		'content_type' => (string)curl_getinfo($handle, CURLINFO_CONTENT_TYPE),
		'redirect_url' => (string)curl_getinfo($handle, CURLINFO_REDIRECT_URL),
		'body' => $body
	);
	curl_close($handle);

	return $result;
}

function assertStatus($url, $expected) {
	$response = httpRequest($url);

	if ($response['status'] !== $expected) {
		failDeploy($url . ' returned ' . $response['status'] . '; expected ' . $expected . '.');
	}

	return $response;
}

function countStructuredType($value, $type) {
	if (!is_array($value)) {
		return 0;
	}

	$count = 0;
	$node_type = isset($value['@type']) ? $value['@type'] : null;

	if ($node_type === $type || (is_array($node_type) && in_array($type, $node_type, true))) {
		$count++;
	}

	foreach ($value as $child) {
		if (is_array($child)) {
			$count += countStructuredType($child, $type);
		}
	}

	return $count;
}

function jsonLdTypeCount($html, $type) {
	preg_match_all('/<script\b[^>]*type=["\']application\/ld\+json["\'][^>]*>(.*?)<\/script>/is', $html, $matches);

	if (!$matches[1]) {
		failDeploy('Page contains no JSON-LD while checking ' . $type . '.');
	}

	$count = 0;

	foreach ($matches[1] as $json_ld) {
		$data = json_decode($json_ld, true);

		if (json_last_error() !== JSON_ERROR_NONE) {
			failDeploy('Page contains invalid JSON-LD: ' . json_last_error_msg());
		}

		$count += countStructuredType($data, $type);
	}

	return $count;
}

deployStep('Targeted database backup and SEO alias migration');
$migration_command = escapeshellarg(PHP_BINARY) . ' ' . escapeshellarg($project_root . '/tools/seo_alias_cleanup.php') . ' --apply';
passthru($migration_command, $migration_exit);

if ($migration_exit !== 0) {
	failDeploy('SEO alias migration failed.');
}

deployStep('Synchronizing reviewed OCMOD XML');
$ocmod_sources = array(
	'huntbee_seo_canonical_ocmod' => $project_root . '/admin/view/template/extension/hbseo/ocmod/ocmod_canonical_23xx.txt',
	'basel_theme' => $project_root . '/admin/view/javascript/basel/main_ocmod/basel_theme_23.ocmod.xml'
);
$ocmod_backup = array('created_at' => date('c'), 'database' => DB_DATABASE, 'modifications' => array());
$prepared_xml = array();

foreach ($ocmod_sources as $code => $file) {
	if (!is_file($file)) {
		failDeploy('Missing OCMOD source: ' . $file);
	}

	$xml = file_get_contents($file);

	if ($xml === false) {
		failDeploy('Could not read OCMOD source: ' . $file);
	}

	validXmlDocument($xml, $file);
	$result = $mysqli->query("SELECT * FROM `" . DB_PREFIX . "modification` WHERE code = '" . $mysqli->real_escape_string($code) . "' LIMIT 1");

	if (!$result->num_rows) {
		failDeploy('Required installed OCMOD row is missing: ' . $code);
	}

	$ocmod_backup['modifications'][] = $result->fetch_assoc();
	$prepared_xml[$code] = $xml;
}

$ocmod_backup_file = rtrim(sys_get_temp_dir(), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . 'watchline-ocmod-backup-' . date('Ymd-His') . '.json';

if (file_put_contents($ocmod_backup_file, json_encode($ocmod_backup, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES), LOCK_EX) === false) {
	failDeploy('Could not write the OCMOD backup.');
}

try {
	foreach ($prepared_xml as $code => $xml) {
		$mysqli->query("UPDATE `" . DB_PREFIX . "modification` SET xml = '" . $mysqli->real_escape_string($xml) . "' WHERE code = '" . $mysqli->real_escape_string($code) . "'");
		$check = $mysqli->query("SELECT xml FROM `" . DB_PREFIX . "modification` WHERE code = '" . $mysqli->real_escape_string($code) . "' LIMIT 1");

		if (!$check->num_rows || !hash_equals($xml, $check->fetch_assoc()['xml'])) {
			throw new RuntimeException('Could not verify OCMOD row ' . $code);
		}
	}
} catch (Throwable $error) {
	failDeploy('OCMOD synchronization stopped before refresh: ' . $error->getMessage() . '. Re-run the same deploy command; the previous XML backup is ' . $ocmod_backup_file . '.');
}

echo "OCMOD backup: " . $ocmod_backup_file . "\n";

deployStep('Refreshing OCMOD');
$_SERVER['DOCUMENT_ROOT'] = $project_root;
$_SERVER['HTTP_HOST'] = parse_url(HTTPS_CATALOG, PHP_URL_HOST);
$_SERVER['REQUEST_URI'] = '/admin/index.php?route=extension/modification/refresh';
$_SERVER['SERVER_PORT'] = parse_url(HTTPS_CATALOG, PHP_URL_SCHEME) === 'https' ? 443 : 80;
$_SERVER['HTTPS'] = $_SERVER['SERVER_PORT'] === 443 ? 'on' : '';
$_SERVER['REQUEST_METHOD'] = 'POST';

require_once DIR_SYSTEM . 'startup.php';

$registry = new Registry();
$config = new Config();
$config->load('default');
$config->load('admin');
$registry->set('config', $config);
$event = new Event($registry);
$registry->set('event', $event);
$loader = new Loader($registry);
$registry->set('load', $loader);
$request = new Request();
$request->server['REQUEST_METHOD'] = 'POST';
$registry->set('request', $request);
$response = new Response();
$registry->set('response', $response);
$database = new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE, DB_PORT);
$registry->set('db', $database);
$session = new stdClass();
$session->data = array();
$registry->set('session', $session);
$registry->set('cache', new Cache($config->get('cache_type'), $config->get('cache_expire')));
$registry->set('url', new Url(HTTP_SERVER, HTTPS_SERVER));
$registry->set('document', new Document());

$settings = $database->query("SELECT * FROM `" . DB_PREFIX . "setting` WHERE store_id = 0 ORDER BY setting_id ASC");

foreach ($settings->rows as $setting) {
	$value = $setting['serialized'] ? json_decode($setting['value'], true) : $setting['value'];
	$config->set($setting['key'], $value);
}

$language_code = $config->get('config_language') ?: $config->get('language_default');
$language = new Language($language_code);
$language->load($language_code);
$registry->set('language', $language);

class WatchlineDeployUser {
	public function hasPermission($action, $route) {
		return true;
	}
}

$registry->set('user', new WatchlineDeployUser());

require_once $project_root . '/admin/controller/extension/modification.php';

class WatchlineCliModificationController extends ControllerExtensionModification {
	protected function getList() {
		// The web response is intentionally skipped during CLI deployment.
	}
}

$modification_controller = new WatchlineCliModificationController($registry);
$modification_controller->refresh(array('cli' => true));
$generated_modifications = recursiveFileCount(DIR_MODIFICATION);

if ($generated_modifications < 1) {
	failDeploy('OCMOD refresh produced no generated modification files.');
}

echo "Generated OCMOD files: " . $generated_modifications . "\n";

deployStep('Clearing VQMod cache');
clearVqmodCache($project_root);
echo "VQMod cache cleared.\n";

deployStep('Regenerating canonical sitemaps');
$items = $config->get('boost_sitemap_item');
$configured_limit = (int)$config->get('boost_sitemap_item_limit');

if (!is_array($items) || !$items) {
	$items = array('product', 'category', 'manufacturer', 'information', 'blog');
}

$request->post = array(
	'boost_sitemap_status' => 1,
	'boost_sitemap_item_limit' => $configured_limit > 0 ? $configured_limit : 1000,
	'boost_sitemap_item' => $items
);
$request->server['REQUEST_METHOD'] = 'POST';

require_once $project_root . '/admin/controller/extension/feed/boost_sitemap.php';
$sitemap_controller = new ControllerExtensionFeedBoostSitemap($registry);
$sitemap_controller->generate();

$sitemap_files = glob($project_root . '/sitemaps/*.xml') ?: array();

if (!$sitemap_files) {
	failDeploy('Sitemap generation produced no XML files.');
}

$product_url_count = 0;
$first_product_url = '';

foreach ($sitemap_files as $sitemap_file) {
	if (preg_match('/_(?:category|manufacturer)_product(?:_\d+)?\.xml$/', basename($sitemap_file))) {
		failDeploy('Legacy duplicate-product sitemap is still present: ' . basename($sitemap_file));
	}

	libxml_use_internal_errors(true);
	$xml = simplexml_load_file($sitemap_file);

	if ($xml === false) {
		failDeploy('Invalid XML sitemap: ' . basename($sitemap_file));
	}

	if (preg_match('/^sitemap_\d+_\d+_product(?:_\d+)?\.xml$/', basename($sitemap_file))) {
		foreach ($xml->url as $entry) {
			$location = (string)$entry->loc;

			if (preg_match('/\s/u', $location)) {
				failDeploy('Whitespace remains in product sitemap URL: ' . $location);
			}

			$product_url_count++;

			if ($first_product_url === '') {
				$first_product_url = $location;
			}
		}
	}
}

$active_products = $database->query("SELECT COUNT(DISTINCT p.product_id) AS total FROM `" . DB_PREFIX . "product` p INNER JOIN `" . DB_PREFIX . "product_to_store` p2s ON p2s.product_id = p.product_id AND p2s.store_id = 0 WHERE p.status = 1")->row['total'];

if ($product_url_count !== (int)$active_products) {
	failDeploy('Product sitemap contains ' . $product_url_count . ' URLs; expected ' . (int)$active_products . '.');
}

echo "Sitemap files: " . count($sitemap_files) . "; canonical product URLs: " . $product_url_count . "\n";

deployStep('HTTP, Schema and feed smoke tests');
$catalog_url = rtrim(HTTPS_CATALOG, '/') . '/';
$home = assertStatus($catalog_url, 200);
$canonical_count = preg_match_all('/<link\b[^>]*\brel=["\']canonical["\'][^>]*>/i', $home['body'], $canonical_matches);

if ($canonical_count !== 1) {
	failDeploy('Homepage must render exactly one canonical link; found ' . $canonical_count . '.');
}

if (jsonLdTypeCount($home['body'], 'OnlineStore') !== 1) {
	failDeploy('Homepage must contain exactly one OnlineStore node.');
}

$product_page = assertStatus($first_product_url, 200);
$product_canonical_count = preg_match_all('/<link\b[^>]*\brel=["\']canonical["\'][^>]*>/i', $product_page['body'], $product_canonical_matches);

if ($product_canonical_count !== 1 || jsonLdTypeCount($product_page['body'], 'Product') !== 1) {
	failDeploy('Canonical product page must contain one canonical link and one Product node.');
}

assertStatus($first_product_url . '?utm_source=deploy-smoke&gclid=deploy-smoke', 200);

$smoke_product = $database->query("SELECT p.product_id FROM `" . DB_PREFIX . "product` p INNER JOIN `" . DB_PREFIX . "product_to_store` p2s ON p2s.product_id = p.product_id AND p2s.store_id = 0 WHERE p.status = 1 ORDER BY p.product_id ASC LIMIT 1")->row;
$dynamic_product = assertStatus($catalog_url . 'index.php?route=product/product&product_id=' . (int)$smoke_product['product_id'] . '&utm_source=deploy-smoke&gclid=deploy-smoke', 301);

if ($dynamic_product['redirect_url'] === '' || strpos($dynamic_product['redirect_url'], 'utm_source=deploy-smoke') === false || strpos($dynamic_product['redirect_url'], 'gclid=deploy-smoke') === false) {
	failDeploy('Dynamic product URL did not preserve approved tracking parameters in its canonical redirect.');
}

assertStatus($catalog_url . 'rucni-sat-MICHAEL%20KORS-MK3190', 301);
assertStatus($catalog_url . 'rucni-sat-casio-A700WEMG-9AEF%20', 301);

$sitemap_index = assertStatus($catalog_url . 'sitemap-index.xml', 200);

if (stripos($sitemap_index['content_type'], 'xml') === false || simplexml_load_string($sitemap_index['body']) === false) {
	failDeploy('sitemap-index.xml is not valid XML.');
}

$llms = assertStatus($catalog_url . 'llms.txt', 200);

if (stripos($llms['content_type'], 'text/plain') === false || strpos($llms['body'], '# Watch Line') !== 0) {
	failDeploy('llms.txt has the wrong content type or header.');
}

assertStatus($catalog_url . 'seo-deploy-smoke-' . gmdate('YmdHis'), 404);
$feed = assertStatus($catalog_url . 'index.php?route=extension/feed/facebookstore', 200);

if (simplexml_load_string($feed['body']) === false) {
	failDeploy('Facebook merchant feed is not valid XML.');
}

if (preg_match('/<g:link>[^<]*\s[^<]*<\/g:link>/u', $feed['body'])) {
	failDeploy('Whitespace remains in a merchant feed product URL.');
}

echo "PASS: homepage/Product canonical+JSON-LD, legacy 301s, sitemap index, llms.txt, real 404 and merchant feed.\n";
echo "\nSEO deployment completed successfully.\n";
