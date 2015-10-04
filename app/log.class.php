<?php
class Log {
	
	protected $registry;
	private $logtype;
	private $jsconsole;
	private $jserror;
	
	function __construct($registry) {
		$this->registry = $registry;
		if ($this->registry->Config->db->enable) {
			$this->logtype = 'db';
		} else {
			$this->logtype = 'txt';
		}
		$this->jsconsole = array();
	}
	
	public function add($msg) {
		if (is_string($msg) && strlen($msg) <= 128) {
			$ip = $_SERVER['REMOTE_ADDR'];
			switch($this->logtype) {
				case "db":
					$this->registry->DBase->NonQuery("INSERT INTO logs (message,ip) VALUES (?,?)", "ss", $msg, $ip);
					return true;
				break;
				case "txt":
					// Add log into txt file
					return true;
				break;
			}
		}
		throw new Exception("Log Entered must be a string of length <= 128!");
		return false;
	}
	
	public function console() {
		$args = func_get_args();
		for ($i = 0; $i < count($args); $i++) {
			$this->jsconsole[] = $args[$i];
		}
	}
	
	public function return_console() {
		if (ENVIRONMENT == 'development') {
			return $this->jsconsole;
		} else {
			return [];
		}
	}
	
	public function error($msg) {
		$this->jserror[] = $msg;
	}
	
	public function return_errors() {
		if (count($this->jserror) > 0) {
			return $this->jserror;
		}
		return false;
	}
}