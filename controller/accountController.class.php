<?php
class accountController extends baseController {
	
	private $account;
	
	public function index() {
		$this->registry->Template->page_title = "Account";

		if (!$this->registry->Account->login_check()) {
			header("Location: /account/login");
		}
		
		// Load the index template
		$this->registry->Template->show('account');
	}
	
	function logout() {
		$this->registry->Template->page_title = "Log Out";
		
		header("Location: /");
	}

	function login() {

		$this->registry->Template->logged_in = $this->registry->Account->login_check();

		$this->registry->Template->page_title = "Sign In";
		$this->registry->Template->showLogin = true;

		$this->registry->Template->show('account');
	}

	function register() {

		$this->registry->Template->logged_in = $this->registry->Account->login_check();

		$this->registry->Template->page_title = "Register New Account";
		$this->registry->Template->showRegister = true;

		$this->registry->Template->show('account');
	}
	
}