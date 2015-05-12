<?php
class accountController extends baseController {
	
	private $account;
	
	function __construct($registry) {
		parent::__construct($registry);
		//$this->account = new Account();
	}
	
	public function index() {
		$this->registry->Template->page_title = "Account";
		
		// Load the index template
		$this->registry->Template->show('account');
	}
	
	function logout() {
		$this->registry->Template->page_title = "Log Out";
		
		// $this->account->logout();
		header("Location: /");
	}
	
}