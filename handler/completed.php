<?php
	include "../config.php";
	
	$id = $_GET['id'];
	$reverse = @$_GET['reverse'];
	
	$db->Query("update manga_name set completed='".(isset($reverse)?0:1)."' where id='".$id."'");
	
	header("location:../index.php?sk=mg&id=".$id);
?>