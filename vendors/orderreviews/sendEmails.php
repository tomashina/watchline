<?php  
$folder = dirname(dirname(dirname(__FILE__)));
chdir($folder);
require_once('config.php');
$domain = parse_url(HTTP_SERVER);
$host = $domain['host'];
putenv('SERVER_NAME='.$host);
$_SERVER['SERVER_NAME'] = $host;
if (isset($argv[1]) && $argv[1] == 'https') {
	$_SERVER['SERVER_PORT'] = 443;
	$_SERVER['HTTPS'] = 'on';
} else {
	$_SERVER['SERVER_PORT'] = 80;
}
$_SERVER['SERVER_PROTOCOL'] = 'HTTP/1.1';
$_GET['route'] = 'extension/module/orderreviews/sendEmails';
require_once('index.php');
?>