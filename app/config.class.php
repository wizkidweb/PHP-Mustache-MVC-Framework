<?php
/*
 * PHP MVC Configuration File
 * v0.58
 * ALPHA BUILD
 */

class Config {
	
	public $site;
	public $db;
	
	function __construct() {
		$this->site = new StdClass();
		$this->db = new StdClass();
		$this->account = new StdClass();
		
		$this->site->title = "PHP Mustache MVC"; // Window Title Name
		$this->site->name = "PHP Mustache MVC"; // Site Header Name
		$this->site->showCopyright = true; // Used if footer can show copyright
		$this->site->copyright = "PHP Mustache MVC"; // Copyright Name
		
		$this->db->enable = true;
		$this->db->server = 'localhost'; // MySQL Database Server
		$this->db->user = 'root'; // MySQL Database User
		$this->db->pass = 'root'; // MySQL Database Password
		$this->db->database = 'mvc'; // MySQL Database Name
		
		$this->account->enable = true;
		$this->account->session_name = "PHP_Mustache_MVC";
		$this->account->secure = false;
	}
	
}