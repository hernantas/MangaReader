<?php
	include "../config.php";
	// Deletion of unvalid folder
	
	Debug::write("Delete removed manga, chapter, picture and history from history");
	$db->Query("delete from manga_name where valid='0'");
	$db->Query("delete from manga_chapter where valid='0'");
	
	$db->Query("delete from manga_chapter where manga_chapter.id not in (select id_chapter from manga_pict)");
	$db->Query("delete from history where history.id_manga not in (select id from manga_name) or history.id_chapter not in (select id from manga_chapter)");
	$db->Query("delete from manga_pict where manga_pict.id_chapter not in (select id from manga_chapter)");
	Debug::write("Finish Scanning Manga");
?>