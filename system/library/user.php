<?php if (!defined("BASEPATH")) exit("NO DIRECT SCRIPT ACCESS ALLOWED");
	
	class SF_user
	{
		private $loggedin = false;
		private $username = '';
		private $password = '';
		
		public function get_username() { return $this->username; }
		public function get_password() { return $this->password; }
		
		private function generate_token()
		{
			if (isset($this->username) && isset($this->password))
				return sha1("prefix".$this->username.$this->password."suffix");
			else
				return sha1("prefix".time()."suffix");
		}
		
		public function set_authentication($username, $password, $stay_alive = false)
		{
			$this->username = $username;
			$this->password = $password;
			$this->loggedin = true;
			
			// 1 Year Cookies Age
			$expire = time()+(60*60);
			if ($stay_alive) $expire = time() + (365 * 24 * 60 * 60);
			$router = &load_class('router');
			setcookie("username", $this->username, $expire, '/'.$router->folder);
			setcookie("password", $this->password, $expire, '/'.$router->folder);
			setcookie("token", $this->generate_token(), $expire, '/'.$router->folder);
		}
		public function logout()
		{
			$expire = time()-(3600);
			$router = &load_class('router');
			setcookie("username", $this->username, $expire, '/'.$router->folder);
			setcookie("password", $this->password, $expire, '/'.$router->folder);
			setcookie("token", $this->generate_token(), $expire, '/'.$router->folder);
		}
		public function _confirm()
		{
			if (isset($_COOKIE['username']) && isset($_COOKIE['password']) && isset($_COOKIE['token']))
			{
				$this->username = $_COOKIE['username'];
				$this->password = $_COOKIE['password'];
				
				if ($_COOKIE['token'] == $this->generate_token()) 
				{
					$this->loggedin = true;
				}
			}
		}
		public function privilege()
		{
			$db = &load_class("db");
			$result = $db->query("select * from user where username='".$this->username."' and password='".$this->password."'" );
			
			if ($result->length > 0) {
				$data = $result->get_next();
				return $data['privilege'];	
			}
		}
		public function is_loggedin()
		{
			return $this->loggedin;
		}
	}
?>