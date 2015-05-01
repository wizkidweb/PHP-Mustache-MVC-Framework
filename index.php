<?php
define('ENVIRONMENT', 'development');

if (defined('ENVIRONMENT')) {
	switch (ENVIRONMENT) {
		case "development":
			error_reporting(E_ALL);
		break;
		case "production":
			error_reporting(0);
		break;
		default:
			die("Environment is not set correctly!");
	}
}

$site_path = realpath(dirname(__FILE__));
define('__SITE_PATH', $site_path);

include 'includes/init.php';

$registry->Router->setPath(__SITE_PATH . '/controller');
$registry->Router->loader();