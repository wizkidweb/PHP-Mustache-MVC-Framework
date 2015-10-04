<?php
define('ENVIRONMENT', 'development');

$site_path = realpath(dirname(__FILE__));
define('__SITE_PATH', $site_path);

include __SITE_PATH . '/includes/error_handler.php';
include __SITE_PATH . '/includes/init.php';

$registry->Router->setPath(__SITE_PATH . '/controller');
$registry->Router->loader();