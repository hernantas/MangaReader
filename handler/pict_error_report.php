<?php
	include "../config.php";
	
	$id = $_GET['id'];
	
	if (!isset($_GET['chk']))
	{
		$query = "select * from report_pict where id_pict='".$id."'";
		
		if ($db->QueryNum($query) == 0)
		{
			$query = "insert into report_pict values('','".$id."',0)";
			$db->Query($query);
		}
	}
	else
	{
		$query = "update report_pict set fixed='".$_GET['chk']."' where id_pict='".$id."'";
		$db->Query($query);
	}
?>