<?php
	include "../config.php";

	$expired = time() - (12 * 30 * 24 * 60 * 60);	// 1 Year
	setcookie("MR_EXP_LOG","",$expired);
	
	$db->Query("delete from login where id_user='".$dUser['id']."'");
	
	header("location:../index.php");
?>