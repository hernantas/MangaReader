<?php if (!defined("BASEPATH")) exit("NO DIRECT SCRIPT ACCESS ALLOWED");
	
	class user extends SF_controller
	{
		public static function login_restrict()
		{
			$user = &load_class("user","library");
			
			if ($user->is_loggedin())
				header("location:".load_class("router")->home_url());
		}
		function login()
		{
			self::login_restrict();
			
			$data['view'] = "login";
			if (isset($_POST['username']))
			{
				$data['error'] = array();
				if ($this->db->query("select * from user where username='".$_POST['username']."' and password='".md5($_POST['password'])."'")->length == 0)
					$data['error'][] = "Username or Password is invalid";
				
				if (count($data['error']) == 0)
				{
					$stay = ($_POST['stay'] == "stay");
					$this->user->set_authentication($_POST['username'], md5($_POST['password']), $stay);
					header("location: ".load_class("router")->home_url());
				}
			}
			$data['db'] = $this->config->get('','db');
			$this->loader->view("home", $data);
		}
		function register()
		{
			self::login_restrict();
			if (isset($_POST['username']))
			{
				$data['error'] = array();
				if (!preg_match('/^[A-Za-z]{1}[A-Za-z0-9]{3,31}$/', $_POST['username']))
					$data['error'][] = "Invalid username";
				if (!preg_match('/^[A-Za-z0-9]{5,31}$/', $_POST['password']))
					$data['error'][] = "Invalid password";
				if ($_POST['password'] != $_POST['repassword'])
					$data['error'][] = "Confirmed password must same with password you entered";
				if ($this->db->query("select * from user where username='".$_POST['username']."'")->length > 0)
					$data['error'][] = "Username is already used, try different name.";
				
				if (count($data['error']) == 0)
				{
					$level = ($this->db->query("select * from user")->length==0)?0:2;
					$this->db->query("insert into user values ('','".$_POST['username']."','".md5($_POST['password'])."','".$level."')");
					$this->user->set_authentication($_POST['username'], md5($_POST['password']));
					header("location: ".load_class("router")->home_url());
				}
			}
			
			$data['view'] = "register";
			$this->loader->view("home", $data);
		}
		public function logout()
		{
			load_class("user", "library")->logout();
			header("location: ".load_class("router")->home_url());
		}
	}
?>