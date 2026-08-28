<?php
class ModelExtensionHbseoHbCanonical extends Model {
	private $hb_extension_version = '4.0.1';

	public function install(){
		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "category_canonical` (
			`id` int(11) NOT NULL AUTO_INCREMENT,
			`category_id` int(11) NOT NULL,
			`path` varchar(100) NOT NULL,
			PRIMARY KEY (`id`)
		)DEFAULT CHARSET=utf8");
		
		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "custom_canonical` (
			`id` int(11) NOT NULL AUTO_INCREMENT,
  			`url` varchar(300) NOT NULL,
 			`canonical` varchar(300) NOT NULL,
			PRIMARY KEY (`id`)
		)DEFAULT CHARSET=utf8");
		
		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "product_canonical` (
			`id` int(11) NOT NULL AUTO_INCREMENT,
			`product_id` int(11) NOT NULL,
			`path` varchar(100) NOT NULL,
			PRIMARY KEY (`id`)
		)DEFAULT CHARSET=utf8");
			
		if (!$this->db->query("SHOW INDEX FROM `" . DB_PREFIX . "category_canonical` WHERE Key_name = 'category_id'")->num_rows) {
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "category_canonical` ADD INDEX (`category_id`)");
		}
		if (!$this->db->query("SHOW INDEX FROM `" . DB_PREFIX . "product_canonical` WHERE Key_name = 'product_id'")->num_rows) {
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "product_canonical` ADD INDEX (`product_id`)");
		}
		if (!$this->db->query("SHOW INDEX FROM `" . DB_PREFIX . "custom_canonical` WHERE Key_name = 'url'")->num_rows) {
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "custom_canonical` ADD INDEX (`url`)");
		}
			
		$this->syncModification();
	}

	public function syncModification() {
		if ((version_compare(VERSION,'2.0.0.0','>=' )) and (version_compare(VERSION,'2.1.0.0','<' ))) {
			$ocmod_filename = 'ocmod_canonical_20xx.txt';
			$ocmod_name = 'SEO - Canonical URL [20xx]';
		}else if ((version_compare(VERSION,'2.1.0.0','>=' )) and (version_compare(VERSION,'2.2.0.0','<' ))) {
			$ocmod_filename = 'ocmod_canonical_21xx.txt';
			$ocmod_name = 'SEO - Canonical URL [21xx]';
		}else if ((version_compare(VERSION,'2.2.0.0','>=' )) and (version_compare(VERSION,'2.3.0.0','<' ))) {
			$ocmod_filename = 'ocmod_canonical_22xx.txt';
			$ocmod_name = 'SEO - Canonical URL [22xx]';
		}else if ((version_compare(VERSION,'2.3.0.0','>=' )) and (version_compare(VERSION,'3.0.0.0','<' ))) {
			$ocmod_filename = 'ocmod_canonical_23xx.txt';
			$ocmod_name = 'SEO - Canonical URL [23xx]';
		}else if (version_compare(VERSION,'3.0.0.0','>=' )) {
			$ocmod_filename = 'ocmod_canonical_3xxx.txt';
			$ocmod_name = 'SEO - Canonical URL [3xxx]';
		}
		
		$ocmod_version = $this->hb_extension_version;
		$ocmod_code = 'huntbee_seo_canonical_ocmod';	
		$ocmod_author = 'HuntBee OpenCart Services';
		$ocmod_link = 'https://www.huntbee.com/';

		$file = DIR_APPLICATION . 'view/template/extension/hbseo/ocmod/'.$ocmod_filename;

		if (!is_file($file)) {
			return false;
		}

		$ocmod_xml = file_get_contents($file);
		if ($ocmod_xml === false) {
			return false;
		}

		$ocmod_xml = str_replace('{huntbee_version}', $ocmod_version, $ocmod_xml);
		$dom = new DOMDocument('1.0', 'UTF-8');
		$dom->preserveWhiteSpace = false;
		if (!$dom->loadXML($ocmod_xml) || !$dom->getElementsByTagName('code')->length || trim($dom->getElementsByTagName('code')->item(0)->textContent) !== $ocmod_code) {
			return false;
		}

		$current = $this->db->query("SELECT modification_id FROM " . DB_PREFIX . "modification WHERE code = '" . $this->db->escape($ocmod_code) . "' LIMIT 1");
		$values = "name = '" . $this->db->escape($ocmod_name) . "', author = '" . $this->db->escape($ocmod_author) . "', version = '" . $this->db->escape($ocmod_version) . "', link = '" . $this->db->escape($ocmod_link) . "', xml = '" . $this->db->escape($ocmod_xml) . "'";

		if ($current->row) {
			$this->db->query("UPDATE " . DB_PREFIX . "modification SET " . $values . " WHERE modification_id = '" . (int)$current->row['modification_id'] . "'");
		} else {
			$this->db->query("INSERT INTO " . DB_PREFIX . "modification SET code = '" . $this->db->escape($ocmod_code) . "', " . $values . ", status = '1', date_added = NOW()");
		}

		return true;
	}
	
	public function uninstall() {
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "category_canonical`");
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "custom_canonical`");
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "product_canonical`");
		$this->db->query("DELETE FROM " . DB_PREFIX . "modification WHERE `code` = 'huntbee_seo_canonical_ocmod'");
	}
	
	public function getRecords($data){
		$sql = "SELECT * FROM `".DB_PREFIX."custom_canonical`";
		if ($data['search']){
			$sql .= " WHERE (`url` LIKE '%".$this->db->escape($data['search'])."%' OR `canonical` LIKE '%".$this->db->escape($data['search'])."%')";
		}

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
	
	public function getTotalRecords($data){
		$sql = "SELECT count(*) as count FROM `".DB_PREFIX."custom_canonical`";	
		if ($data['search']){
			$sql .= " WHERE (`url` LIKE '%".$this->db->escape($data['search'])."%' OR `canonical` LIKE '%".$this->db->escape($data['search'])."%')";
		}
		$results = $this->db->query($sql);
		return $results->row['count'];
	}
	
	public function insertCanonical($url, $canonical) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "custom_canonical` WHERE url = '".$this->db->escape($url)."'");
		if ($query->rows){
			return false;
		}else{
			$this->db->query("INSERT INTO `" . DB_PREFIX . "custom_canonical` (`url`,`canonical`) VALUES ('".$this->db->escape($url)."','".$this->db->escape($canonical)."')");
			return true;
		}
	}
	
	public function deleteRecord($id) {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "custom_canonical` WHERE id = '" . (int)$id . "'");
	}
	
	
}
?>
