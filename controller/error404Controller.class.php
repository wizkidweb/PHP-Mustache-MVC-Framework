<?php
class error404Controller extends baseController {
	
	public function index() {
		$this->registry->Template->css = [
			["url" => "404.scss"]
		];
		
		$this->registry->Template->page_title = "Page Not Found";
		
		$this->registry->Template->show("error404");
	}
	
}