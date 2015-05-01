<?php
class error404Controller extends baseController {
	
	public function index() {
		$this->registry->Template->css = array(
			array("url" => "404.css")
		);
		
		$this->registry->Template->show("error404");
	}
	
}