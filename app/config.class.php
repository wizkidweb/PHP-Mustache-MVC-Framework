<?php
/*
 * PHP MVC Configuration File
 * v0.8
 * ALPHA BUILD
 */

class Config {
	
	public $site;
	public $db;
	
	function __construct() {
		$this->site = new Registry();
		$this->db = new Registry();
		$this->account = new Registry();
		$this->template = new Registry();
		
		$this->site->title = "PHP Mustache MVC"; // Window Title Name
		$this->site->name = "PHP Mustache MVC"; // Site Header Name
		$this->site->showCopyright = true; // Used if footer can show copyright
		$this->site->copyright = "PHP Mustache MVC"; // Copyright Name
		
		$this->db->enable = true;
		$this->db->server = 'localhost'; // MySQL Database Server
		$this->db->user = 'root'; // MySQL Database User
		$this->db->pass = ''; // MySQL Database Password
		$this->db->database = 'pmmvc'; // MySQL Database Name
		
		$this->account->enable = false;
		$this->account->session_name = "PMMVC_Session";
		$this->account->secure = false;

		$this->template->compress_css = true;
		$this->template->compress_js = true;
	}
	
}