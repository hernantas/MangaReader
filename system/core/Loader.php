<?php if (!defined("BASEPATH")) exit("NO DIRECT SCRIPT ACCESS ALLOWED");

class SF_Loader
{
	public $controller = NULL;
	public function controller($controller_name)
	{
		if (file_exists(APPPATH."controller/".$controller_name.".php"))
			include (APPPATH."controller/".$controller_name.".php");
		
		if (!class_exists($controller_name))
			not_found();
		
		$this->controller = new $controller_name();
		return $this->controller;
	}
	public function view($view_name, $data=array())
	{
		$path = load_class("router")->home_url();
		if (file_exists(APPPATH."view/".$view_name.".php"))
			include (APPPATH."view/".$view_name.".php");
		elseif (file_exists(APPPATH."view/404.php"))
			include (APPPATH."view/404.php");
	}
	public function model($model_name)
	{
		if (file_exists(APPPATH."model/".$model_name.".php"))
			include (APPPATH."model/".$model_name.".php");
	}
}
	
?>
