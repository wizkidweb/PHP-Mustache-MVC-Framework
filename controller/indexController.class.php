<?php
class indexController extends baseController {
	
	public function index() {
		$this->registry->Template->page_title = "Home";
		
		// Load the index template
		$this->registry->Template->show('index');
	}
	
}