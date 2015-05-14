<?php
/*
Sample Model Class

This class includes the registry object, so you can access the database, configuration options,
and other features of PHP Mustache MVC.
*/
class SampleModel {
	
	protected $registry;
	
	function __construct($registry) {
		$this->registry = $registry;
	}
	
}