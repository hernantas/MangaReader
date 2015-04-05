<?php
	include "../config.php";
	
	$manga = $_GET['manga'];
	
	$query = $db->Query("select * from manga_name where name='".$manga."' limit 0,1");
	$dat = mysql_fetch_array($query);
	
	$db->Query("update manga_name set valid='1' where id='".$dat['id']."'");
	$db->Query("update manga_chapter set valid='1' where id_manga='".$dat['id']."'");
?>