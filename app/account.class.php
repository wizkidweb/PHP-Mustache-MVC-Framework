<?php
class Account {
	
	protected $registry;
	
	function __construct($registry) {
		$this->registry = $registry;
		if ($this->registry->Config->account->fb_enable) {
			define('FACEBOOK_SDK_V4_SRC_DIR', __SITE_PATH . '/includes/lib/Facebook/');
			require __SITE_PATH . '/includes/lib/Facebook/autoload.php';
		}			
	}
	
	function check_login() {
		return false;
	}
	
}