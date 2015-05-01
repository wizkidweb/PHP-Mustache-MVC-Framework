<?php
/*
 * PHP MVC Configuration File
 * v1.0
 */

class Config {
	
	public $site;
	public $db;
	
	function __construct() {
		$this->site = new StdClass();
		$this->db = new StdClass();
		
		$this->site->title = "Anthony Martin Web Design & Development";
		
		$this->db->server = 'localhost';
		$this->db->user = 'root';
		$this->db->pass = '';
		$this->db->database = 'wizkidweb';
	}
	
}