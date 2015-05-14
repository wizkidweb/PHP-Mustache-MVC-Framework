<?php

abstract class baseController {
	
	protected $registry;
	public $action;
	
	function __construct($registry) {
		$this->registry = $registry;
		// Set directory options
		$dir = new StdClass();
		$dir->views = "/views";
		$dir->view = $dir->views . "/" . $this->registry->Router->controller;
		
		$this->registry->Template->dir = $dir;
		
		// If AJAX
		if ((!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') || isset($_POST['ajax'])) {
			$this->onAjax();
			die();
			//$this->ajax_return();
		}
	}
	
	/* All controllers must contain an index method */
	abstract function index();
	
	function onAjax() {}
	
	
	/* Ajax Functions */
	function ajax_return($x="") {
		if (ENVIRONMENT == "development") {
			if (is_string($x)) {
				$msg = array();
				$msg[] = $x;
			} else {
				$msg = $x;
			}
			$msg["php_console"] = $this->registry->Log->return_console();
			if ($this->registry->Log->return_errors())
				$msg["errors"] = $this->registry->Log->return_errors();
			die(json_encode($msg));
		} else {
			if (is_string($x)) {
				die(json_encode(array($x)));
			} else {
				die(json_encode($x));
			}
		}
	}
}