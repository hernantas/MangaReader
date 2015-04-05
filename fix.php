<?php
	include "config.php";
	$q = $db->Query("select manga_name.*, COUNT(manga_chapter.id) as ch_cnt from manga_name, manga_chapter where manga_name.id=manga_chapter.id_manga group by manga_name.id");
	
	$limit = -1;
	echo "<textarea><Bookmarks>\n";
	while ($data = mysql_fetch_array($q))
	{
		if ($limit == 0) break;
		echo "  <Bookmark>\n";
		echo "    <MangaLink>http://mangafox.com/manga/";
		echo str_replace("&", "_", str_replace(")", "", str_replace("(", "", str_replace(" ", "_", strtolower($data['name'])))));
		echo "/</MangaLink>\n";
		echo "    <OldNumChapters>".$data['ch_cnt']."</OldNumChapters>\n";
		echo "    <MangaName>".$data['name']."</MangaName>\n";
		echo "  </Bookmark>\n";
		$limit--;
	}
	echo "</Bookmarks></textarea>total: ".mysql_num_rows($q);
?>
