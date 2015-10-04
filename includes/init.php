<?php

include __SITE_PATH . '/app/baseController.class.php';
include __SITE_PATH . '/app/registry.class.php';
include __SITE_PATH . '/app/router.class.php';
include __SITE_PATH . '/app/template.class.php';
include __SITE_PATH . '/app/config.class.php';
include __SITE_PATH . '/app/dbase.class.php';
include __SITE_PATH . '/app/account.class.php';
include __SITE_PATH . '/app/log.class.php';
include __SITE_PATH . '/app/lang.class.php';
include __SITE_PATH . '/app/compiler.class.php';

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

// Initialize composer autoloader
include __SITE_PATH . '/vendor/autoload.php';

// Create new registry object
$registry = new Registry;

// Add configuration settings to registry object
$registry->Config = new Config();

// Create the database registry object
if ($registry->Config->db->enable || $registry->Config->account->enable) {
	$registry->DBase = new DBase($registry);
}

// Load the logger
$registry->Log = new Log($registry);

// Load language
$registry->Lang = new Lang($registry);

// Load the account engine
if ($registry->Config->account->enable) {
	$registry->Account = new Account($registry);
}

// Load the router
$registry->Router = new Router($registry);

// Load SCSS Compiler
$registry->Compiler = new Compiler($registry);

// Load the template engine
$registry->Template = new Template($registry);