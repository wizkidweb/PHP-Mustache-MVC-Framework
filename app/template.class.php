<?php

class Template {
	
	private $registry;
	
	private $vars = array();
	private $view;
	
	function __construct($registry) {
		$this->registry = $registry;
		Mustache_Autoloader::register();
	}
	
	public function __set($index, $value) {
		$this->vars[$index] = $value;
	}
	
	function show($name, $action = 'index') {
		//die("<pre>".print_r($this->vars,true)."</pre>");
		$this->view = $name;
		
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
		
		$this->set_vars();
		
		die($tpl->render($this->vars));
	}
	
	private function set_vars() {
		// Set config vars
		$this->vars['site_title'] = $this->registry->Config->site->title;

		// Set basic globals
		$this->vars['year'] = date("Y");

		// Set directory options
		$dir = new StdClass();
		$dir->views = "/views";
		$dir->view = $dir->views . "/" . $this->view;
		$this->vars['dir'] = $dir;

		// Loop through css for SCSS and CSS compression
		if (array_key_exists('css', $this->vars)) {
			for ($i = 0; $i < count($this->vars['css']); $i++) {
				$this->vars['css'][$i]['url'] = $this->registry->Compiler->compile_scss($dir->view, $dir->view . "/" . $this->vars['css'][$i]['url']);
			}
		}

		// Loop through js for compression
		if (array_key_exists('js', $this->vars)) {
			for ($i = 0; $i < count($this->vars['js']); $i++) {
				$this->vars['js'][$i]['url'] = $this->registry->Compiler->compile_js($dir->view, $dir->view . "/" . $this->vars['js'][$i]['url']);
			}
		}
		
		// Set account globals
		if ($this->registry->Config->account->enable) {
			$user = new StdClass();
			$user->logged_in = $this->registry->Account->logged_in;
			$user->info = $this->registry->Account->get_this_user_data("username");
			$this->vars['user'] = $user;
		}
	}
}