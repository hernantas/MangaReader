<?php
	include "../config.php";
	
	$un = $_POST['un'];
	$ps = $_POST['ps'];
	
	$qr = $db->Query("select * from user where username='".$un."' and password='".$ps."'");
	
	if (isset($un) && isset($ps) && mysql_num_rows($qr) == 1) {
		$expired = time() + (12 * 30 * 24 * 60 * 60);	// 1 Year
		$salt = substr(md5(time()),0,3);
		$d = mysql_fetch_array($qr);
		$code = md5($salt . $un . $salt . $ps);
		setcookie("MR_EXP_LOG",$code,$expired,"/manga/");
		$db->Query("insert into login values ('','".$d['id']."','".$code."')");
	} 
	header("location:../index.php");
?>