<?php if (!defined("BASEPATH")) exit("NO DIRECT SCRIPT ACCESS ALLOWED");
	
	$loader = &load_class("loader");
	$loader->controller("home");
	// Redirect to Home
	class newsfeed extends home
	{
		
	}
?>