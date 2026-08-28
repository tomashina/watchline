<?php
//==============================================
// XML Sitemap OC 2.3.x_v2.x
// Author 	: OpenCartBoost
// Email 	: support@opencartboost.com
// Website 	: http://www.opencartboost.com
//==============================================
class ControllerExtensionFeedBoostSitemap extends Controller {
	public function index() {
		if ($this->config->get('boost_sitemap_status')) {
			$directory = str_replace('system', 'sitemaps', DIR_SYSTEM);
			$files = glob($directory. '*.xml', GLOB_BRACE);
			
				if (!$files) {
					$files = array();
				}

				sort($files, SORT_NATURAL);
			
			$output  = '<?xml version="1.0" encoding="UTF-8"?>';
			$output .= '<sitemapindex xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/siteindex.xsd" xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
			
				foreach ($files as $file) {
					if (!is_file($file) || preg_match('/_(?:category|manufacturer)_product(?:_|\.)/', basename($file))) {
						continue;
					}

					$time = filemtime($file);
				$file = basename($file);
				$explode = explode('_', $file);
				
				if (isset($explode[1])) {
					$store_id = $explode[1];
					
					if ($store_id == (int)$this->config->get('config_store_id')) {
						$output .= '<sitemap>';
							$store_url = $this->config->get('config_ssl') ?: $this->config->get('config_url');
							$loc = rtrim($store_url, '/') . '/sitemaps/' . rawurlencode($file);
							$output .= '<loc>' . htmlspecialchars($loc, ENT_QUOTES | ENT_XML1, 'UTF-8') . '</loc>';
						$output .= '<lastmod>' . date('c', $time) . '</lastmod>';
						$output .= '</sitemap>';
					}
				}
			}
		
			$output .= '</sitemapindex>';

				$this->response->addHeader('Content-Type: application/xml; charset=UTF-8');
				$this->response->addHeader('Cache-Control: public, max-age=3600');
				$this->response->addHeader('Expires: ' . gmdate('D, d M Y H:i:s', time() + 3600) . ' GMT');
				$this->response->addHeader('Pragma: public');
			$this->response->setOutput($output);
		} else {
			$this->response->addHeader($this->request->server['SERVER_PROTOCOL'] . ' 404 Not Found');
			$this->response->setOutput('404 Not Found');
		}
	}
}
