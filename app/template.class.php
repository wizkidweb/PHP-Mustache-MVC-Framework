<?php

include(__SITE_PATH . '/includes/lib/Mustache/Autoloader.php');

class Template {
	
	private $registry;
	
	private $vars = array();
	
	function __construct($registry) {
		$this->registry = $registry;
		Mustache_Autoloader::register();
		$this->set_vars();
	}
	
	public function __set($index, $value) {
		$this->vars[$index] = $value;
	}
	
	function show($name,$action = 'index') {
		
		$filepath = __SITE_PATH . '/views/' . $name . '/' . $action . '.html';
		$dirpath = __SITE_PATH . '/views/' . $name;
		
		if (file_exists($filepath) == false) {
			throw new Exception('Template not found in ' . $path);
			return false;
		}
		
		$options = array('extension' => '.html');
		
		$m = new Mustache_Engine(array(
			'loader' => new Mustache_Loader_FilesystemLoader($dirpath, $options),
			'partials_loader' => new Mustache_Loader_FilesystemLoader(__SITE_PATH . '/views/global/partials', $options)
		));
		
		$tpl = $m->loadTemplate($action);
		
		echo $tpl->render($this->vars);
	}
	
	private function set_vars() {
		// Set config vars
		$this->vars['config'] = $this->registry->Config;
		// Set basic globals
		$this->vars['year'] = date("Y");
	}
}