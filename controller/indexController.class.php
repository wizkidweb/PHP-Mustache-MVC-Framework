<?php
class indexController extends baseController {
	
	public function index() {
		$this->registry->Template->css = [
			["url" => "index.scss"]
		];

		$this->registry->Template->js = [
			["url" => "index.js"]
		];
		
		// Load the index template
		$this->registry->Template->show('index');
	}
	
}