<?php
/*
 * PHP MVC Configuration File
 * v1.0
 * ALPHA BUILD
 */

class Config {
	
	public $site;
	public $db;
	
	function __construct() {
		$this->site = new StdClass();
		$this->db = new StdClass();
		
		$this->site->title = "Anthony Martin Web Design & Development";
		$this->site->showCopyright = true;
		$this->site->copyright = "Anthony Martin Web Design & Development LLC";
		
		$this->db->enable = true;
		$this->db->server = 'localhost';
		$this->db->user = 'root';
		$this->db->pass = '';
		$this->db->database = 'php_mvc';
	}
	
}