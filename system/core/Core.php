<?php if (!defined("BASEPATH")) exit("NO DIRECT SCRIPT ACCESS ALLOWED");
	
/**
 * SYSTEM codename is SnowFlake (SF) based on code igniter code. Using 
 * Front-Controller pattern style and using front end. For php 5 or later.
 *
 * @author hernantas@gmail.com
 * @version 1.0
 * @copyright 2014 Hernantas
 * @package SnowFlakes
 */
 
// Load all basic function needed
include (BASEPATH."core/common.php");

$benchmark = &load_class("benchmark");
$benchmark->start();

// Load log class
$log = &load_class("log");
// Load Config Class
$config = &load_class("config");
// Load configration default.ini
$config->load_config('default');

if (ENVIRONMENT == "development")
	$log->enable();

$router = &load_class("router");
$router->folder = "newmanga";
$db = &load_class("db");

// Load base class controller
load_class("controller");

$user = &load_class("user", "library");
$user->_confirm();

// Add routing table

$router->extract_url($router->full_url());
?>