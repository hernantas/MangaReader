<?php
/**
 * System name is SnowFlake (SF), php project based on code igniter code. Using 
 * Front-Controller pattern style and using front end controller. 
 * For php 5 or later.
 *
 * @author hernantas@gmail.com
 * @version 1.0
 * @copyright 2014 Hernantas
 * @package SnowFlakes
 */

/*
 * Define development ENVIRONMENT
 * Option:
 * 		- development
 * 		- testing
 * 		- production
 */
define("ENVIRONMENT", "development");

if (defined('ENVIRONMENT'))
{
	switch (ENVIRONMENT)
	{
		case 'development':
			error_reporting(E_ALL);
		break;
	
		case 'testing':
		case 'production':
			error_reporting(0);
		break;

		default:
			exit('The application environment is not set correctly.');
	}
}

// Define path where system file is located
$system_path = "system";
// Define path where application file is located
$app_path = "application";

// Make sure path is have slash in the end of string and no slash in beginning
$system_path = trim($system_path, "/") . "/";
$app_path = trim($app_path, "/") . "/";
define("BASEPATH", $system_path);
define("APPPATH", $app_path);

// Bootstrapping the application
include (BASEPATH . "core/Core.php");
?>