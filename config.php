<?php
	session_start();
	
	// BASIC Config
	$CFG["FOLDER_PATH"] 	=	addslashes(dirname(__FILE__));
	$CFG['DB_Name']			=	"manga";
	$CFG['DB_Host']			=	"127.0.0.1";
	$CFG['DB_UserName']		=	"root";
	$CFG['DB_Password']		=	"";
	$CFG['MANGA_PATH'] = "D:/Manga";
	
	// CONFIG CONSTANT
	define("ENVIRONMENT", 'development');		// If in Development Stage
	
	// Include Debugger
	include "/system/debug.php";
	Debug::$enabled = true;
	Debug::$maxfilecount = 100;
	if (isset($clearDebugger)) Debug::clear();
	Debug::write("==================== Session (".date("d/m/Y - H:i:s").") ====================");
	
	// Include System Files
	include "/system/db.php";
	include "/system/string.php";
	// include "/system/security/log.php";
	//include "/handler/image.php";
	
	// Include Script
	include "/system/default.php";
	include "/system/application.php";
	
	// Include Language
	include "/locale/en.php";
	
	// Add App Here
	$app->add('home',"news_feed.php");
	$app->add('nf',"news_feed.php");
	$app->add('sc',"scaning_page.php","Scaning");
	$app->add('ml',"manga_list.php","Manga List");
	$app->add('mg',"manga.php","Chapter List");
	$app->add('rg',"register.php","Register");
	$app->add('hs',"history.php","Your History");
	$app->add('sh',"search.php","Search Result");
	$app->add('lg',"login.php","Login");
	$app->add("er","error_reporting.php", "Error Reporting");
	$app->add("ss","server_status.php", "Server Status");
	
	// Add Panel here
	
	$login = false;
	
	if (isset($_COOKIE['MR_EXP_LOG'])) {
		$qUser = $db->Query("select login.id_user, user.username from login, user where login.id_user=user.id and code='".$_COOKIE['MR_EXP_LOG']."'");
		$dUser = mysql_fetch_array($qUser);
		$dUser['id'] = $dUser['id_user'];
		
		if (mysql_num_rows($qUser) == 1) {
			$login = true;
		}
	}
	//$log->startLog();
?>