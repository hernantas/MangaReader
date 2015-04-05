<?php
	include "../config.php";
	$un = $_POST['un']; $ps = $_POST['ps']; $rps = $_POST['rps'];
	
	$invalid = false;
	if (isset($un) && isset($ps) && isset($rps) && $ps == $rps) {
		if ($db->QueryNum("select * from user where username='".$un."'") > 0) {
			$invalid = true;
		}
	} else {
		$invalid = true;	
	}
	if (!$invalid) {
		$db->Query("insert into user values('','" . $un . "','" . $ps . "',2)");
		$_SESSION['un'] = $un;
		$_SESSION['ps'] = $ps;
		header("location:../index.php");	
	} else {
		header("location:../index.php?sk=rg");
	}
	
?>