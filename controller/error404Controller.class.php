<?php
class error404Controller extends baseController {
	
	public function index() {
		$this->registry->Template->css = array(
			array("url" => "404.css")
		);
		
		$this->registry->Template->page_title = "Page Not Found";
		
		$this->registry->Template->show("error404");
	}
	
}