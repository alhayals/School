<?php

	class Session
	{
		private $logged_in = false;
		public $member_id;

		function __construct()
		{
			session_start();
			$this->check_login();
		}

		public function is_logged_in()
		{
			return $this->logged_in;
		}

		public function login($member)
		{
			if($member)
			{
				$_SESSION['MEMBER_ID'] = $member->get_member_id();
				//$_SESSION['MEMBER_USERNAME'] = $member->get_member_username();
				$_SESSION['MEMBER_EMAIL'] = $member->get_member_email();
				$_SESSION['MEMBER_IS_ADMIN'] = $member->get_member_is_admin();

				//$_SESSION['LASTNAME'] = $member->get_member_lastname();
				//$_SESSION['COMPANY'] = $member->get_member_organization();
				$this->logged_in = true;
			}
		}

		public function logout()
		{
			unset($_SESSION['MEMBER_ID']);
			unset($_SESSION['MEMBER_IS_ADMIN']);
			unset($this->member_id);
			$this->logged_in = false;
		}

		private function check_login()
		{
			if(isset($_SESSION['MEMBER_ID']))
			{
				$this->member_id = $_SESSION['MEMBER_ID'];
				$this->logged_in = true;
			}
			else
			{
				unset($this->member_id);
				$this->logged_in = false;
			}
		}
	}
	
	if (function_exists ('ini_set'))
	{
	   	//Use cookies to store the session ID on the client side
	   	ini_set ('session.use_only_cookies', 1);
	   	//Disable transparent Session ID support
	   	ini_set ('session.use_trans_sid',    0);
	   	ini_set( 'magic_quotes_gpc', false );
		ini_set( 'magic_quotes_gpc', 0 );
		ini_set( 'magic_quotes_gpc', 'Off' );
	}

	global $session;
	$session = new Session();
?>