<?php
class indexController extends baseController {
	
	public function index() {
		$this->registry->Template->page_title = "Home";
		$username = $this->registry->Account->get_this_user_data('username');
		
		// Load the index template
		$this->registry->Template->show('index');
	}
	
}