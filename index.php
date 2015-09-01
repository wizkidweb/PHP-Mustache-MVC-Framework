<?php
define('ENVIRONMENT', 'development');

include 'includes/error_handler.php';

$site_path = realpath(dirname(__FILE__));
define('__SITE_PATH', $site_path);

include 'includes/init.php';

$registry->Router->setPath(__SITE_PATH . '/controller');
$registry->Router->loader();