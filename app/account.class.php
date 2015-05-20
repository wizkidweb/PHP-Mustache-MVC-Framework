<?php
class Account {
	
	protected $registry;
	private $db;
	private $lang;
	public $logged_in;
	
	function __construct($registry) {
		$this->registry = $registry;
		$this->sec_session_start();
		$this->db = $this->registry->DBase;
		$this->lang = $this->registry->Lang;
		$this->logged_in = $this->login_check();
	}

	private function sec_session_start() {
		if (session_id() == '' || !isset($_SESSION)) {
			$session_name = $this->registry->Config->account->session_name;
			$secure = $this->registry->Config->account->secure;
			// Stops JavaScript access of session id
			$httponly = true;
			// Forces session to use cookies
			if (ini_set('session.use_only_cookies', 1) === false) {
				header("Location: /?error=Could not initiate a safe session (ini_set)");
				exit();
			}
			// Get cookie params
			$cookieParams = session_get_cookie_params();
			session_set_cookie_params($cookieParams['lifetime'], $cookieParams['path'], $cookieParams['domain'], $secure, $httponly);
			session_name($session_name);
			session_start();
			session_regenerate_id(true);
		}
	}
	
	private function login($email, $password) {
		$qry = $this->db->Query("SELECT id, username, password, salt FROM members WHERE email = ? LIMIT 1", 's', $email);
		if ($qry) {
			$this->registry->Log->console($qry);
			$user_id = $qry[0]['id'];
			$username = $qry[0]['username'];
			$db_password = $qry[0]['password'];
			$salt = $qry[0]['salt'];
			
			$password = hash('sha512', $password.$salt);
			
			if (count($qry) == 1) {
				if ($this->checkbrute($user_id) == true) { // Check if too many login attempts
					// Account is locked
					$this->registry->Log->error($this->lang->E_ACCOUNT_LOCKED);
					return false;
				} else {
					// Check for matching passwords
					if ($db_password == $password) {
						if (session_id() == '' || !isset($_SESSION)) {
							$this->registry->Log->error("Session not started.");
							return false;
						}
						// Password is correct
						$user_browser = $_SERVER['HTTP_USER_AGENT'];
						// XSS protection
						$user_id = preg_replace("/[^0-9]+/", "", $user_id);
						$_SESSION['user_id'] = $user_id;
						$username = preg_replace("/[^a-zA-Z0-9_\-]+/", "", $username);

						$_SESSION['username'] = $username;
						$_SESSION['login_string'] = hash('sha512', $password . $user_browser);
						// Login successful
						return true;
					} else {
						// Password is incorrect
						// Record attempt in DB
						$now = time();
						$qry = $this->db->NonQuery("INSERT INTO login_attempts (user_id, time) VALUES (?, ?)", "is", $user_id, $now);
						$this->registry->Log->error($this->lang->E_INCORRECT_PASSWORD);
						return false;
					}
				}
			} else {
				// No user exists
				$this->registry->Log->error($this->lang->E_NO_USER_FOUND);
				$this->registry->Log->console("Login Error: No User found with email: ".$email);
				return false;
			}
		}
	}

	private function checkbrute($user_id) {
		$now = time();

		// Earliest login attempt counted 2 hours ago
		$valid_attempts = $now - (2*60*60);

		$qry = $this->db->Query("SELECT time FROM login_attempts WHERE user_id = ? AND time > ?", "is", $user_id, $valid_attempts);
		if (count($qry) > 5) {
			return true;
		} else {
			return false;
		}
	}

	private function esc_url($url) {
		if ('' == $url) {
			return $url;
		}

		$url = preg_replace('|[^a-z0-9-~+_.?#=!&;,/:%@$\|*\'()\\x80-\\xff]|i', '', $url);

		$strip = array('%0d', '%0a', '%0D', '%0A');
		$url = (string) $url;

		$count = 1;
		while ($count) {
			$url = str_replace($strip, '', $url, $count);
		}

		$url = str_replace(';//', '://', $url);
		$url = htmlentities($url);
		$url = str_replace('&amp;', '&#038;', $url);
		$url = str_replace("'", '&#039;', $url);
		if ($url[0] !== '/') {
			return '';
		} else {
			return $url;
		}
	}

	public function login_check() {
		// Check for all session variables
		if (isset($_SESSION['user_id'], $_SESSION['username'], $_SESSION['login_string'])) {
			$user_id = $_SESSION['user_id'];
			$login_string = $_SESSION['login_string'];
			$username = $_SESSION['username'];

			// Get user-agent string
			$user_browser = $_SERVER['HTTP_USER_AGENT'];

			$qry = $this->db->Query("SELECT password FROM members WHERE id = ? LIMIT 1", 'i', $user_id);
			if (count($qry) == 1) {
				$password = $qry[0]['password'];
				$login_check = hash('sha512', $password . $user_browser);

				if ($login_check == $login_string) {
					// Logged in
					return true;
				} else {
					$this->registry->Log->console("check =/= string", $login_check, $login_string);
					// Not logged in
					return false;
				}
			} else {
				$this->registry->Log->console("count(qry) =/= 1", $qry);
				// Not logged in
				return false;
			}
		} else {
			$this->registry->Log->console("session not set", $_SESSION);
			// Not logged in
			return false;
		}
	}

	public function process_login() {

		if (isset($_POST['email'], $_POST['p'])) {
			$email = $_POST['email'];
			$password = $_POST['p'];

			if ($this->login($email, $password) == true) {
				// Login success
				return true;
			} else {
				// Login failed
				return false;
			}
		} else {
			// Invalid Request
			return false;
		}
	}
	
	public function process_register() {
		if (isset($_POST['user'], $_POST['email'], $_POST['p'])) {
			$user = $_POST['user'];
			$email = $_POST['email'];
			$p = $_POST['p'];
			
			if ($this->register($user, $email, $p)) {
				// Register success
				return true;
			} else {
				// Register failed
				return false;
			}
		} else {
			// Invalid Request
			return false;
		}
	}

	public function logout() {

		// Unset all session values
		$_SESSION = array();

		$params = session_get_cookie_params();

		// Delete cookie
		setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);

		session_destroy();
		return true;
	}

	private function register($username, $email, $p) {
		$username = filter_var($username, FILTER_SANITIZE_STRING);
		$email = filter_var($email, FILTER_SANITIZE_EMAIL);
		$email = filter_var($email, FILTER_VALIDATE_EMAIL);

		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			// Invalid Email
			$this->registry->Log->error($this->lang->E_EMAIL_INVALID);
			return false;
		}

		$password = filter_var($p, FILTER_SANITIZE_STRING);
		if (strlen($password) != 128) {
			// Invalid Password Hash
			$this->registry->Log->error($this->lang->E_PASSWORD_INVALID);
			return false;
		}

		// Check existing email
		$qry = $this->db->Query("SELECT id FROM members WHERE email = ? LIMIT 1", 's', $email);
		if (count($qry) == 1) {
			// A user with this email already exists
			$this->registry->Log->error($this->lang->E_EMAIL_EXISTS);
			return false;
		}

		// Check existing username
		$qry = $this->db->Query("SELECT id FROM members WHERE username = ? LIMIT 1", 's', $username);
		if (count($qry) == 1) {
			// A user with this username already exists
			$this->registry->Log->error($this->lang->E_USERNAME_EXISTS);
			return false;
		}

		$random_salt = hash('sha512', uniqid(mt_rand(1, mt_getrandmax()), true));
		$password = hash('sha512', $password . $random_salt);

		$qry = $this->db->NonQuery("INSERT INTO members (username, email, password, salt) VALUES (?, ?, ?, ?)", 'ssss', $username, $email, $password, $random_salt);
		if (!$qry) {
			return false;
		}

		return true;
	}
	
	public function get_this_user_data() {
		if ($this->logged_in) {
			$user = $_SESSION['user_id'];
			$cols = func_get_args();
			$colstr = "";
			for ($i = 0; $i < count($cols); $i++) {
				$colstr .= $cols[$i];
				if ($i !== count($cols)-1) $colstr .= ",";
			}
			$qry = $this->db->Query("SELECT ".$colstr." FROM members WHERE id = ?", "i", $user);
			if ($qry) {
				return $qry;
			} else {
				return false;
			}
		}
		return false;
	}
}