<?php
	include "../config.php";
	
	Debug::write("Initialize Database Manga for scanning");
	$db->Query("update manga_name set valid='0' where completed!='1'");
	$db->Query("update manga_chapter, manga_name set manga_chapter.valid='0' where manga_chapter.id_manga=manga_name.id and manga_name.valid!='1'");
	
	// $db->Query("TRUNCATE TABLE manga_name");
	// $db->Query("TRUNCATE TABLE manga_chapter");
	
	// $db->Query("TRUNCATE TABLE manga_pict");
?>