<?php
/*
 * PHP MVC Configuration File
 * v0.5
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
		$this->db->pass = ''; // MySQL Database Password
		$this->db->database = 'php_mvc'; // MySQL Database Name
		
		$this->account->fb_enable = false;
	}
	
}