<?php
class accountController extends baseController {
		
	public function index() {
		$this->registry->Template->page_title = "Account";

		if (!$this->registry->Account->login_check()) {
			header("Location: /account/login");
		}
		
		// Load the index template
		$this->registry->Template->show('account');
	}
	
	function logout() {
		$this->registry->Account->logout();
		header("Location: /");
	}

	function login() {		
		$this->registry->Template->logged_in = $this->registry->Account->login_check();
		$this->registry->Template->js = array(
			array("url" => "js/sha512.js"),
			array("url" => "js/account_forms.js")
		);

		$this->registry->Template->page_title = "Sign In";
		$this->registry->Template->showLogin = true;

		$this->registry->Template->show('account');
	}

	function register() {
		$this->registry->Template->logged_in = $this->registry->Account->login_check();
		$this->registry->Template->js = array(
			array("url" => "js/sha512.js"),
			array("url" => "js/account_forms.js")
		);

		$this->registry->Template->page_title = "Register New Account";
		$this->registry->Template->showRegister = true;

		$this->registry->Template->show('account');
	}
	
	function onAjax() {
		if (isset($_POST['action'])) {
			if ($_POST['action'] == 'login') {
				if ($this->registry->Account->process_login()) {
					$this->ajax_return(array(
						"success" => true
					));
				} else {
					$this->ajax_return(array(
						"success" => false
					));
				}
			}
			if ($_POST['action'] == 'register') {
				if ($this->registry->Account->process_register()) {
					$this->ajax_return(array(
						"success" => true
					));
				} else {
					$this->ajax_return(array(
						"success" => false
					));
				}
			}
		}
		$this->registry->Log->error("AJAX `action` POST value not found.");
		$this->ajax_return("Ajax Error");
	}
	
}