<?php
	include "config.php";
	$q = $db->Query("select manga_name.*, COUNT(manga_chapter.id) as ch_cnt from manga_name, manga_chapter where manga_name.id=manga_chapter.id_manga group by manga_name.id");
	
	$limit = -1;
	echo "<textarea cols=100 rows=30><Bookmarks>\n";
	while ($data = mysql_fetch_array($q))
	{
		$name = htmlentities(htmlentities($data['name']));
		if ($limit == 0) break;
		echo "  <Bookmark>\n";
		echo "    <MangaLink>http://www.mangahere.co/manga/";
		echo str_replace("&", "_", str_replace(")", "", str_replace("(", "", str_replace(" ", "_", strtolower($data['name'])))));
		echo "/</MangaLink>\n";
		echo "    <OldNumChapters>".$data['ch_cnt']."</OldNumChapters>\n";
		echo "    <MangaName>".$name."</MangaName>\n";
		echo "  </Bookmark>\n";
		$limit--;
	}
	echo "</Bookmarks></textarea>total: ".mysql_num_rows($q);
?>
