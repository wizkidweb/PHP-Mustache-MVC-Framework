<?php

class Router {
	
	private $registry;
	
	private $path;
	private $args = array();
	
	public $file;
	public $controller;
	public $action;
	
	function __construct($registry) {
		$this->registry = $registry;
	}
	
	private function getController() {
		// get the route from the URL
		$route = (empty($_GET['rt'])) ? '' : $_GET['rt'];
		
		if (empty($route)) {
			$route = 'index';
		} else {
			// get the parts of the route
			$parts = explode('/', $route);
			$this->controller = $parts[0];
			if (isset($parts[1])) {
				$this->action = $parts[1];
			}
		}
		
		if (empty($this->controller)) {
			$this->controller = 'index';
		}
		
		// Get action
		if (empty($this->action)) {
			$this->action = 'index';
		}
		
		// Set the file path
		$this->file = $this->path . '/' . $this->controller . 'Controller.class.php';
	}
	
	function setPath($path) {
		if (is_dir($path) == false) {
			throw new Exception('Invalid controller path: `' . $path . '`');
		}
		
		$this->path = $path;
	}
	
	public function loader() {
		// Check the route
		$this->getController();
		
		// If the file is not there, 404
		if (is_readable($this->file) == false) {
			if (is_readable(__SITE_PATH . '/controller/error404Controller.class.php')) {
				$this->controller = 'error404';
				$this->file = __SITE_PATH . '/controller/error404Controller.class.php';
			} else {
				echo '`'.$this->file;
				die('`: 404 Not Found');
			}
		}
		
		// Include the controller
		include $this->file;
		
		// create a new instance of controller class
		$class = $this->controller . 'Controller';
		$controller = new $class($this->registry);
		
		// set controller action property
		$controller->action = $this->action;
		
		// check to see if the action is callable
		if (is_callable(array($controller, $this->action)) == false) {
			$action = 'index';
		} else {
			$action = $this->action;
		}
		
		// run the action
		$controller->$action();
	}
}