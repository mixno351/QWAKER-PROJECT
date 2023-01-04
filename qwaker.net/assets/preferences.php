<?php
	$default_theme = 'theme-default-light';
	$default_theme_site = '/assets/css/';
	$default_api = 'http://api.qwaker.net';
	$default_api2 = 'http://api.qwaker.net';
	$default_domain = 'http://qwaker.net';
	$default_domain_preview = 'https://qwaker.net';
	$default_name = 'QWAKER.fun';

	error_reporting(0);
?>
<?php if (trim($_COOKIE['color-scheme'])) {
	$default_theme = $_COOKIE['color-scheme'];
} ?>
<?php if (trim($_COOKIE['theme'])) {
	$default_theme_site = $_COOKIE['theme'];
} ?>
<?php
	$vk_client_id = '7933767';
?>