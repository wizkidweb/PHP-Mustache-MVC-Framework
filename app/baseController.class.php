<?php

abstract class baseController {
	
	protected $registry;
	public $action;
	
	function __construct($registry) {
		$this->registry = $registry;
		
		// If AJAX
		if ((!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') || isset($_POST['ajax'])) {
			$this->baseAjax();
			$this->onAjax();
			die();
			//$this->ajax_return();
		}
	}
	
	/* All controllers must contain an index method */
	abstract function index();
	
	function onAjax() {}
	
	
	/* Ajax Functions */
	private function baseAjax() {
		if (isset($_POST['action'])) {
			if (ENVIRONMENT == "development") {
				switch ($_POST['action']) {
					case "getLang":
						$this->ajax_return($this->registry->Lang->termsArr(), false);
					break;
					case "getSession":
						$this->ajax_return($_SESSION);
					break;
					case "registry":
						$data = $_POST['data'];
						$this->ajax_return($this->registry->$data['c']->$data['a']);
					break;
				}
			}
			switch ($_POST['action']) {
				case "checkLogin":
					$this->ajax_return(array(
						"logged_in" => $this->registry->Account->logged_in
					));
				break;
			}
		}
	}
	
	function ajax_return($x="", $console = true) {
		if (ENVIRONMENT == "development") {
			if (is_string($x)) {
				$msg = array();
				$msg[] = $x;
			} else {
				$msg = $x;
			}
			if ($console) $msg["php_console"] = $this->registry->Log->return_console();
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