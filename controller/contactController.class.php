<?php
class contactController extends baseController {
	
	public function index() {
		$this->registry->Template->css = array(
			array("url" => "contact.css")
		);
		
		$this->registry->Template->show('contact');
	}
	
}