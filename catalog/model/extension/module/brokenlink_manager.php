<?php
class ModelExtensionModuleBrokenlinkManager extends Model {
	
	public function insertErrorLog($current_url,$referrer_url, $user_agent, $ip) {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "error_logs` (error,referrer,user_agent,ip) VALUES ('".$this->db->escape($current_url)."','".$this->db->escape($referrer_url)."','".$this->db->escape($user_agent)."','".$this->db->escape($ip)."')");
		
			// Unknown URLs must remain real 404 responses. Phonetic/smart
			// redirects can map unrelated products and are intentionally not
			// generated automatically. Explicit admin and keyword mappings are
			// still honoured below.
			$redirect_path = '';
			$explicit_redirect = false;
		
		//CHECK IF USER HAS SELECTED KEYWORD BASED REDIRECT
		$keyword_url = $this->config->get('hb_brokenlinks_keywordurl');
		if ($keyword_url == 1){
			$allkeywordurls = $this->db->query("SELECT * FROM `" . DB_PREFIX . "error_keyword` WHERE `store_id`='".(int)$this->config->get('config_store_id')."'");
			if ($allkeywordurls->num_rows > 0){
				$keywordurls = $allkeywordurls->rows;
				foreach ($keywordurls as $keywordurl) {
					$keyword_term = $keywordurl['keyword'];
					$keyword_url = $keywordurl['redirect_url'];
					if (strpos($current_url,$keyword_term) !== false){
						$redirect_path = $keyword_url;
						$explicit_redirect = true;
					}
				}
			}
		}
		
			if (strlen(trim($redirect_path)) === 0){
			$type = 404;
		}else{
			$type = $this->config->get('hb_brokenlinks_rtype');
		}
		$query = $this->db->query("SELECT id FROM `" . DB_PREFIX . "error` WHERE error = '".$this->db->escape($current_url)."'");
		if ($query->num_rows > 0){
			$id = $query->row['id'];
			$redirect_url = $this->getRedirect($current_url);
			
			if (trim($redirect_url) == ''){
					$author = $explicit_redirect ? 2 : 3;
					$this->db->query("UPDATE `" . DB_PREFIX . "error` SET hits = hits+1, `redirect` = '".$this->db->escape($redirect_path)."', `type` = '".(int)$type."', `author` = '".(int)$author."' WHERE id = '" . (int)$id . "'");
			}else{
				$verify_redirect_url = $this->db->query("SELECT * FROM `" . DB_PREFIX . "error` WHERE error = '".$this->db->escape($redirect_url)."'");
				if ($verify_redirect_url->num_rows > 0){
						$redirect_path = '';
					$this->db->query("UPDATE `" . DB_PREFIX . "error` SET `redirect` = '".$this->db->escape($redirect_path)."' WHERE redirect = '" . $this->db->escape($redirect_url) . "'");
				}
			
				$this->db->query("UPDATE `" . DB_PREFIX . "error` SET hits = hits+1 WHERE id = '" . (int)$id . "'");
			}
		}else{
				$author = $explicit_redirect ? 2 : 3;
				$this->db->query("INSERT INTO `" . DB_PREFIX . "error` (error, redirect, type, author, store_id, date_modified) VALUES ('".$this->db->escape($current_url)."','".$this->db->escape($redirect_path)."','".(int)$type."', '".(int)$author."', '".(int)$this->config->get('config_store_id')."', now())");
		}
	}
	
	public function validateCommonRedirect() {
		if (isset($_SERVER['HTTPS']) && (($_SERVER['HTTPS'] == 'on') || ($_SERVER['HTTPS'] == '1'))) {
			$http_protocol = "https://";
		}else{
			$http_protocol = "http://";
		}
		$current_url= $http_protocol.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
		$current_url = urlencode($current_url);
		
		$query = $this->db->query("SELECT redirect FROM `" . DB_PREFIX . "error` WHERE error = '".$this->db->escape($current_url)."' and author = 1 LIMIT 1");
		if ($query->num_rows > 0){
			$redirect_url = $query->row['redirect'];
			$redirect_url = urldecode($redirect_url);
			$this->updateRedirecthits($current_url);
			$response_code = $this->getResponse($current_url);
			$this->response->redirect($redirect_url, $response_code);
		}
		
		if ($this->config->get('hb_brokenlinks_replacer')) {
			$replacer = $this->db->query("SELECT * FROM `" . DB_PREFIX . "error_replacer`");
			if ($replacer->rows) {
				foreach ($replacer->rows as $row) {
					$match = $row['match'];
					if (strpos($current_url,$match) !== false){
						$replaced_url = str_replace($match,$row['replace'],$current_url);
						$this->response->redirect(urldecode($replaced_url));
					}
				}
			}
		}
		
	}
	
	public function getRedirect($current_url){
			$query = $this->db->query("SELECT redirect, author FROM `" . DB_PREFIX . "error` WHERE error = '".$this->db->escape($current_url)."' LIMIT 1");
			if ($query->num_rows > 0){
				$redirect = trim($query->row['redirect']);

				if ($redirect === '') {
					return false;
				}

				// Author 3 means the target was guessed automatically. Historical
				// records include thousands of unrelated product/home redirects, so
				// only explicit admin/keyword mappings (authors 1/2) may redirect.
				if ((int)$query->row['author'] === 3) {
					return false;
				}

				return $redirect;
			}else{
				return false;
			}
		}

		public function getResponse($current_url){
			$query = $this->db->query("SELECT type FROM `" . DB_PREFIX . "error` WHERE error = '".$this->db->escape($current_url)."' LIMIT 1");
			$response = $query->row ? (int)$query->row['type'] : 301;

			return in_array($response, array(301, 302, 307, 308), true) ? $response : 301;
	}
	
	public function updateRedirecthits($current_url) {
		$this->db->query("UPDATE `" . DB_PREFIX . "error` SET redirect_hits = redirect_hits+1 WHERE error = '".$this->db->escape($current_url)."'");
	}
	
	public function removeQueryStringParameter($url, $varname) {
		$parsedUrl = parse_url($url);
		$query = array();
	
		if (isset($parsedUrl['query'])) {
			parse_str($parsedUrl['query'], $query);
			unset($query[$varname]);
		}
	
		$path = isset($parsedUrl['path']) ? $parsedUrl['path'] : '';
		$query = !empty($query) ? '?'. http_build_query($query) : '';
	
		$outputurl = $parsedUrl['scheme']. '://'. $parsedUrl['host']. $path. $query;
		return urldecode($outputurl);
	}
	
	public function checkandredirect(){
		//adding delete query to auto delete unwanted records	
		$this->db->query("DELETE FROM `" . DB_PREFIX . "error` WHERE `hits` < '".(int)$this->config->get('hb_brokenlinks_adel_count')."' AND `author` = 3 AND store_id = '".(int)$this->config->get('config_store_id')."' AND date_added < DATE_SUB(NOW(), INTERVAL ".(int)$this->config->get('hb_brokenlinks_adel_days')." DAY)");

		if (isset($_SERVER['HTTPS']) && (($_SERVER['HTTPS'] == 'on') || ($_SERVER['HTTPS'] == '1'))) {
			$http_protocol = "https://";
		}else{
			$http_protocol = "http://";
		}
		$current_url= $http_protocol.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
		if(isset($_SERVER['HTTP_REFERER'])) {
			$referrer_url = urlencode($_SERVER['HTTP_REFERER']);
		} else{
			$referrer_url = NULL;
		}
			$ip = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '';
		$user_agent = (isset($_SERVER['HTTP_USER_AGENT']))?$_SERVER['HTTP_USER_AGENT']:'';
		
		$queryparameters = $this->config->get('hb_brokenlinks_excludequery');
		
		if (isset($queryparameters) and strlen(trim($queryparameters))>3){
			$queryparameters = explode(',',$queryparameters);
			foreach ($queryparameters as $queryparameter){
				$current_url = $this->removeQueryStringParameter($current_url, $queryparameter);
			}
		}
		
		$current_url = urlencode($current_url);
		
		$skip = false;
		//CHECK IF TO SKIP REDIRECT BASED ON IP
		$exclude_ip = $this->config->get('hb_brokenlinks_ignoreip');
		
			if ($ip !== '' && $exclude_ip && strpos($exclude_ip,$ip) !== false){
			$skip = true;
		}
		
		//CHECK IF TO SKIP REDIRECT BASED ON USER AGENT
		$bots = $this->config->get('hb_brokenlinks_ignoreagents'); 
		if (!empty($bots) and $skip === false){ 
			$bots = explode(',',$bots);
			foreach ($bots as $bot){
				$excludebot = trim($bot);
				if (strpos($user_agent,$excludebot) !== false){$skip = true;}
			} 
		}
				
		//CHECK IF TO SKIP REDIRECT BASED ON TERMS IN THE URL
		$excludes = $this->config->get('hb_brokenlinks_excludeterms'); 
		if (!empty($excludes) and $skip === false){ 
			$excludes = explode(',',$excludes);
			foreach ($excludes as $exclude){
				$term = urlencode(trim($exclude));
				if (strpos($current_url,$term) !== false){$skip = true;}
			} 
		}

		if ($skip === false){ 
			$this->insertErrorLog($current_url,$referrer_url, $user_agent, $ip);
			$redirect_url = $this->getRedirect($current_url);
			if ($redirect_url != false){
				$redirect_url = urldecode($redirect_url);
				$this->updateRedirecthits($current_url);
				$response_code = $this->getResponse($current_url);
				$this->response->redirect($redirect_url, $response_code);
			}
	   }
	}

}
