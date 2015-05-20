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
		//die("<pre>".print_r($this->vars,true)."</pre>");
		
		$filepath = __SITE_PATH . '/views/' . $name . '/' . $action . '.html';
		$dirpath = __SITE_PATH . '/views/' . $name;
		
		if (file_exists($filepath) == false) {
			throw new Exception('Template not found in ' . $path);
			return false;
		}
		
		$options = array('extension' => '.html');
		
		$m = new Mustache_Engine(array(
			'loader' => new Mustache_Loader_FilesystemLoader($dirpath, $options),
			'partials_loader' => new Mustache_Loader_FilesystemLoader(__SITE_PATH . '/views/_global/partials', $options)
		));
		
		$tpl = $m->loadTemplate($action);
		
		echo $tpl->render($this->vars);
	}
	
	private function set_vars() {
		// Set config vars
		$this->vars['config'] = $this->registry->Config;
		// Set basic globals
		$this->vars['year'] = date("Y");
		// Set directory options
		$dir = new StdClass();
		$dir->views = "/views";
		$dir->view = $dir->views . "/" . $this->registry->Router->controller;
		//die("<pre>".print_r($this->registry->Router,true)."</pre>");
		$this->vars['dir'] = $dir;
		// Set account globals
		if ($this->registry->Config->account->enable) {
			$user = new StdClass();
			$user->logged_in = $this->registry->Account->logged_in;
			$this->vars['user'] = $user;
		}
	}
}