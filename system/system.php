<?php
	class System {
		var $db;
		var $string;
		var $app;
		
		public function __construct() {
			
		}
		
		public function load($package, $class) {
			$sFile = "/".$package . "/" . $class . ".php";
			if (file_exists($package . "/" . $class . ".php")) {
				include $sFile;
			} else {
				Debug::error("File not found \"".$sFile."\"");
			}
		}
	}
	
	$sys = new System();
	$sys->load("database", "db");
	$sys->load("script","application");
?>