#!/usr/bin/env php
<?php
/**
 * Normalize whitespace in active product SEO aliases and preserve old URLs.
 *
 * Dry run:
 *   php tools/seo_alias_cleanup.php
 *
 * Apply:
 *   php tools/seo_alias_cleanup.php --apply
 *
 * Run this once on each environment before regenerating sitemaps and feeds.
 * It is idempotent: only active product aliases that still contain whitespace
 * are changed. Explicit 301 records are created for flat, category and brand
 * variants of the previous URL when HuntBee's redirect table is available.
 */

if (PHP_SAPI !== 'cli') {
	http_response_code(404);
	exit(1);
}

$project_root = dirname(__DIR__);
$config_file = $project_root . '/config.php';

if (!is_file($config_file)) {
	fwrite(STDERR, "Missing config.php\n");
	exit(1);
}

require $config_file;

$apply = in_array('--apply', $argv, true);
$store_id = 0;
$store_url = rtrim(defined('HTTPS_SERVER') ? HTTPS_SERVER : HTTP_SERVER, '/') . '/';
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$db = new mysqli(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE, DB_PORT);

if ($db->connect_errno) {
	fwrite(STDERR, "Database connection failed: " . $db->connect_error . "\n");
	exit(1);
}

$db->set_charset('utf8mb4');

function tableExists($db, $table) {
	$result = $db->query("SHOW TABLES LIKE '" . $db->real_escape_string($table) . "'");

	return $result && $result->num_rows > 0;
}

function settingValue($db, $key, $default) {
	$sql = "SELECT `value` FROM `" . DB_PREFIX . "setting` WHERE store_id = 0 AND `key` = '" . $db->real_escape_string($key) . "' ORDER BY setting_id DESC LIMIT 1";
	$result = $db->query($sql);

	return $result && $result->num_rows ? $result->fetch_assoc()['value'] : $default;
}

function normalizeKeyword($keyword) {
	$keyword = html_entity_decode((string)$keyword, ENT_QUOTES | ENT_HTML5, 'UTF-8');
	$keyword = preg_replace('/\s+/u', '-', trim($keyword));
	$keyword = preg_replace('/-+/u', '-', $keyword);

	return trim($keyword, '-');
}

function uniqueKeyword($db, $keyword, $query, $language_id, $alias_id, $product_id) {
	$base = $keyword;
	$attempt = 0;

	do {
		$candidate = $attempt === 0 ? $base : $base . '-' . $product_id . ($attempt > 1 ? '-' . $attempt : '');
		$sql = "SELECT `query` FROM `" . DB_PREFIX . "url_alias` WHERE keyword = '" . $db->real_escape_string($candidate) . "' AND language_id = " . (int)$language_id . " AND url_alias_id <> " . (int)$alias_id;
		$result = $db->query($sql);
		$collision = false;

		if ($result) {
			while ($row = $result->fetch_assoc()) {
				if ($row['query'] !== $query) {
					$collision = true;
					break;
				}
			}
		}

		$attempt++;
	} while ($collision);

	return $candidate;
}

function aliasKeyword($db, $query, $language_id) {
	$sql = "SELECT keyword FROM `" . DB_PREFIX . "url_alias` WHERE `query` = '" . $db->real_escape_string($query) . "' AND language_id = " . (int)$language_id . " ORDER BY url_alias_id ASC LIMIT 1";
	$result = $db->query($sql);

	return $result && $result->num_rows ? $result->fetch_assoc()['keyword'] : '';
}

function categoryPaths($db, $product_id) {
	$paths = array();
	$sql = "SELECT p2c.category_id, GROUP_CONCAT(cp.path_id ORDER BY cp.level SEPARATOR '_') AS path FROM `" . DB_PREFIX . "product_to_category` p2c LEFT JOIN `" . DB_PREFIX . "category_path` cp ON cp.category_id = p2c.category_id WHERE p2c.product_id = " . (int)$product_id . " GROUP BY p2c.category_id ORDER BY p2c.category_id ASC";
	$result = $db->query($sql);

	if ($result) {
		while ($row = $result->fetch_assoc()) {
			if (!empty($row['path'])) {
				$paths[] = $row['path'];
			}
		}
	}

	return $paths;
}

function pathKeywords($db, $path, $language_id) {
	if ($path === false || $path === null || $path === '') {
		return array();
	}

	$segments = array();

	foreach (explode('_', $path) as $category_id) {
		$keyword = aliasKeyword($db, 'category_id=' . (int)$category_id, $language_id);

		if ($keyword === '') {
			return array();
		}

		$segments[] = $keyword;
	}

	return $segments;
}

function canonicalPath($db, $product_id, $paths) {
	if (!(int)settingValue($db, 'hb_canonical_status', 0)) {
		return false;
	}

	if (tableExists($db, DB_PREFIX . 'product_canonical')) {
		$result = $db->query("SELECT path FROM `" . DB_PREFIX . "product_canonical` WHERE product_id = " . (int)$product_id . " LIMIT 1");

		if ($result && $result->num_rows) {
			$path = $result->fetch_assoc()['path'];

			return $path === 'E' ? false : $path;
		}
	}

	$candidates = array(array('count' => 0, 'path' => false));

	foreach ($paths as $path) {
		$candidates[] = array('count' => count(explode('_', $path)), 'path' => $path);
	}

	$type = (int)settingValue($db, 'hb_canonical_type', 1);
	$level = (int)settingValue($db, 'hb_canonical_level', 1);

	usort($candidates, function ($left, $right) use ($type) {
		if ($left['count'] === $right['count']) {
			return strcmp((string)$left['path'], (string)$right['path']);
		}

		return $type === 0 ? $left['count'] - $right['count'] : $right['count'] - $left['count'];
	});

	if ($type === 2) {
		foreach ($candidates as $candidate) {
			if ((int)$candidate['count'] === $level) {
				return $candidate['path'];
			}
		}
	}

	return $candidates[0]['path'];
}

function absoluteUrl($base, $segments, $encode) {
	if ($encode) {
		$segments = array_map('rawurlencode', $segments);
	}

	return rtrim($base, '/') . '/' . implode('/', $segments);
}

function upsertRedirect($db, $error, $redirect, $store_id) {
	$sql = "SELECT id FROM `" . DB_PREFIX . "error` WHERE error = '" . $db->real_escape_string($error) . "' AND store_id = " . (int)$store_id . " LIMIT 1";
	$result = $db->query($sql);

	if ($result && $result->num_rows) {
		$id = (int)$result->fetch_assoc()['id'];
		$db->query("UPDATE `" . DB_PREFIX . "error` SET redirect = '" . $db->real_escape_string($redirect) . "', type = 301, author = 2, date_modified = NOW() WHERE id = " . $id);
	} else {
		$db->query("INSERT INTO `" . DB_PREFIX . "error` (error, redirect, type, author, hits, redirect_hits, store_id, date_modified) VALUES ('" . $db->real_escape_string($error) . "', '" . $db->real_escape_string($redirect) . "', 301, 2, 1, 0, " . (int)$store_id . ", NOW())");
	}
}

$sql = "SELECT ua.url_alias_id, ua.query, ua.keyword, ua.language_id, p.product_id, p.manufacturer_id FROM `" . DB_PREFIX . "url_alias` ua INNER JOIN `" . DB_PREFIX . "product` p ON ua.query = CONCAT('product_id=', p.product_id) INNER JOIN `" . DB_PREFIX . "product_to_store` p2s ON p2s.product_id = p.product_id AND p2s.store_id = " . (int)$store_id . " WHERE p.status = 1 AND ua.keyword REGEXP '[[:space:]]' ORDER BY ua.url_alias_id ASC";
$result = $db->query($sql);

if (!$result) {
	fwrite(STDERR, "Alias query failed: " . $db->error . "\n");
	exit(1);
}

$changes = array();
$has_redirect_table = tableExists($db, DB_PREFIX . 'error');

if (!$has_redirect_table) {
	fwrite(STDERR, "HuntBee redirect table is missing. No aliases were changed because their old URLs could not be preserved.\n");
	exit(1);
}

while ($row = $result->fetch_assoc()) {
	$alias_id = (int)$row['url_alias_id'];
	$product_id = (int)$row['product_id'];
	$language_id = (int)$row['language_id'];
	$old_keyword = $row['keyword'];
	$new_keyword = uniqueKeyword($db, normalizeKeyword($old_keyword), $row['query'], $language_id, $alias_id, $product_id);
	$paths = categoryPaths($db, $product_id);
	$canonical_path = canonicalPath($db, $product_id, $paths);
	$canonical_segments = pathKeywords($db, $canonical_path, $language_id);
	$canonical_segments[] = $new_keyword;
	$new_url = absoluteUrl($store_url, $canonical_segments, true);
	$old_variants = array(array($old_keyword));

	foreach ($paths as $path) {
		$segments = pathKeywords($db, $path, $language_id);

		if ($segments) {
			$old_variants[] = array_merge($segments, array($old_keyword));
			$leaf = end($segments);
			$old_variants[] = array($leaf, $old_keyword);
		}
	}

	$manufacturer_keyword = $row['manufacturer_id'] ? aliasKeyword($db, 'manufacturer_id=' . (int)$row['manufacturer_id'], $language_id) : '';

	if ($manufacturer_keyword !== '') {
		$old_variants[] = array($manufacturer_keyword, $old_keyword);
	}

	$redirect_keys = array();

	foreach ($old_variants as $segments) {
		$redirect_keys[urlencode(absoluteUrl($store_url, $segments, false))] = true;
		$redirect_keys[urlencode(absoluteUrl($store_url, $segments, true))] = true;
	}

	$changes[] = array(
		'url_alias_id' => $alias_id,
		'product_id' => $product_id,
		'old_keyword' => $old_keyword,
		'new_keyword' => $new_keyword,
		'canonical_url' => $new_url,
		'redirects' => array_keys($redirect_keys)
	);
}

if (!$changes) {
	echo "No active product aliases with whitespace were found.\n";
	exit(0);
}

foreach ($changes as $change) {
	echo sprintf("%d: %s -> %s (%d redirect variants)\n", $change['product_id'], $change['old_keyword'], $change['new_keyword'], count($change['redirects']));
}

if (!$apply) {
	echo "Dry run only. Re-run with --apply to update the database.\n";
	exit(0);
}

$alias_ids = array_map(function ($change) {
	return (int)$change['url_alias_id'];
}, $changes);
$backup = array(
	'created_at' => date('c'),
	'database' => DB_DATABASE,
	'aliases' => array(),
	'redirects' => array()
);
$backup_query = $db->query("SELECT * FROM `" . DB_PREFIX . "url_alias` WHERE url_alias_id IN (" . implode(',', $alias_ids) . ") ORDER BY url_alias_id ASC");

while ($backup_row = $backup_query->fetch_assoc()) {
	$backup['aliases'][] = $backup_row;
}

$all_redirects = array();

foreach ($changes as $change) {
	$all_redirects = array_merge($all_redirects, $change['redirects']);
}

$all_redirects = array_values(array_unique($all_redirects));
$quoted_redirects = array_map(function ($value) use ($db) {
	return "'" . $db->real_escape_string($value) . "'";
}, $all_redirects);
$backup_query = $db->query("SELECT * FROM `" . DB_PREFIX . "error` WHERE store_id = " . (int)$store_id . " AND error IN (" . implode(',', $quoted_redirects) . ") ORDER BY id ASC");

while ($backup_row = $backup_query->fetch_assoc()) {
	$backup['redirects'][] = $backup_row;
}

$backup_file = rtrim(sys_get_temp_dir(), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . 'watchline-seo-alias-backup-' . date('Ymd-His') . '.json';

if (file_put_contents($backup_file, json_encode($backup, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES), LOCK_EX) === false) {
	fwrite(STDERR, "Could not write the pre-migration backup. No database changes were made.\n");
	exit(1);
}

echo "Backup: " . $backup_file . "\n";

try {
	foreach ($changes as $change) {
		$redirect = urlencode($change['canonical_url']);

		// oc_url_alias is MyISAM on this store. Persist and verify every
		// redirect first, then change the alias as the final operation. If the
		// process stops early, the old alias still works and a rerun is safe.
		foreach ($change['redirects'] as $error) {
			upsertRedirect($db, $error, $redirect, $store_id);
			$redirect_check = $db->query("SELECT redirect, type, author FROM `" . DB_PREFIX . "error` WHERE error = '" . $db->real_escape_string($error) . "' AND store_id = " . (int)$store_id . " LIMIT 1")->fetch_assoc();

			if (!$redirect_check || $redirect_check['redirect'] !== $redirect || (int)$redirect_check['type'] !== 301 || (int)$redirect_check['author'] !== 2) {
				throw new RuntimeException('Could not verify redirect for product ' . (int)$change['product_id']);
			}
		}

		$db->query("UPDATE `" . DB_PREFIX . "url_alias` SET keyword = '" . $db->real_escape_string($change['new_keyword']) . "' WHERE url_alias_id = " . (int)$change['url_alias_id']);
		$alias_check = $db->query("SELECT keyword FROM `" . DB_PREFIX . "url_alias` WHERE url_alias_id = " . (int)$change['url_alias_id'] . " LIMIT 1");

		if (!$alias_check->num_rows || $alias_check->fetch_assoc()['keyword'] !== $change['new_keyword']) {
			throw new RuntimeException('Could not verify updated alias for product ' . (int)$change['product_id']);
		}
	}
} catch (Throwable $error) {
	fwrite(STDERR, "Migration stopped safely: " . $error->getMessage() . ". Re-run the same command after correcting the cause.\n");
	exit(1);
}

echo "Updated " . count($changes) . " product aliases and preserved their previous URL variants with explicit 301 redirects.\n";
