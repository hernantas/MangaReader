<?php
	include "../config.php";
	
	$id = $_GET['id'];
	$usr= $_GET['usr'];
	
	$qMC = $db->Query("select manga_chapter.* from manga_chapter left join (select * from history where user='".$usr."')his on manga_chapter.id=his.id_chapter where manga_chapter.id_manga='".$id."' and (his.id is null) group by manga_chapter.id");
	
	$SQL = "";
	
	$first = true;
	
	$count = 0;
	
	while ($dat = mysql_fetch_array($qMC)) {
		if ($first) 
			$SQL .= "('','".$dat['id_manga']."','".$dat['id']."','1','".$usr."','".time()."')"; 
		else 
			$SQL .= ",('','".$dat['id_manga']."','".$dat['id']."','1','".$usr."','".time()."')";
		$count++;
		$first = false;
	}
	
	if (!empty($SQL)) {
		$q = $db->Query("insert into history values" . $SQL);
		if ($q) $db->Query("update manga_name set read_count=read_count+".$count." where id='".$id."'");
	}	
	header("location:../index.php?sk=mg&id=".$id);
?>