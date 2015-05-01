<?php

include __SITE_PATH . '/app/baseController.class.php';
include __SITE_PATH . '/app/registry.class.php';
include __SITE_PATH . '/app/router.class.php';
include __SITE_PATH . '/app/template.class.php';
include __SITE_PATH . '/app/config.class.php';

function __autoload($class_name) {
	$filename = strtolower($class_name) . '.class.php';
	$file = __SITE_PATH . '/model/' . $filename;
	if (file_exists($file) == false) {
		return false;
	}
	include ($file);
}

if (!function_exists('classAutoLoader')) {
	function classAutoLoader($class_name) {
		$filename = strtolower($class_name) . '.class.php';
		$file = __SITE_PATH . '/model/' . $filename;
		if (file_exists($file) && !class_exists($class_name)) {
			include $file;
		}
	}
}

spl_autoload_register('classAutoLoader');


// Create new registry object
$registry = new Registry;

// Add configuration settings to registry object
$registry->Config = new Config();

// Create the database registry object
$registry->DBase = new DBase($registry);

// Load the router
$registry->Router = new Router($registry);

// Load the template engine
$registry->Template = new Template($registry);