<?php

abstract class baseController {
	
	protected $registry;
	public $action;
	
	function __construct($registry) {
		$this->registry = $registry;
		
		// If AJAX
		if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
			$this->baseAjax();
			$this->onAjax($_SERVER["REQUEST_METHOD"]);
			die();
		}
	}
	
	/* All controllers must contain an index method */
	abstract function index();
	
	function onAjax($req) {}
	
	
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
	
	function ajax_return($x = null) {
		$args = func_get_args();
		if (count($args) > 1) {
			$x = [$x];
			for ($i = 1; $i < count($args); $i++) {
				$x[] = $args[$i];
			}
		} else {
			$x = $args[0];
		}

		$js_console = $this->registry->Log->return_console();

		if (is_string($x) || count($js_console) > 0) {
			if (count($js_console) > 0) {
				die(json_encode([$x]));
			} else {
				die(json_encode([$x, $js_console]));
			}
		} else {
			die(json_encode($x));
		}
	}
}