
<?php
	//$strtotime = date("o-\WW");
	$start = time();//strtotime($strtotime);
	
	/*
	$end = strtotime("+6 days 23:59:59", $start);
	echo "$start: ".date("r", $start)."<br />";
	echo "$end: ".date("r", $end)."<br />";
	echo "$beforeStart: ".date("r", $beforeStart)."<br />";
	echo "$beforeEnd: ".date("r", $beforeEnd)."<br />";
	*/
	
	$sql = NULL;
	$query = NULL;
	
	// $query = $db->Query("select manga_chapter.*, manga_name.name as name_manga, manga_name.id as id_manga, manga_name.add_time as add_manga, manga_name.last_update as update_manga from manga_name, manga_chapter where manga_name.id=manga_chapter.id_manga and manga_chapter.date_add > ".($start)." group by manga_chapter.id order by manga_chapter.id desc");
	$query = $db->Query("select * from manga_name");
	
	if (mysql_num_rows($query) > 0)
	{
	
		$contQ = 0;
		for ($i=0;true;$i++) {
			$beforeStart = strtotime("-".$i." week", $start);
			$beforeEnd = strtotime("+7 days", $beforeStart);
			
			$sql = "select manga_chapter.*, manga_name.name as name_manga, manga_name.id as id_manga, manga_name.add_time as add_manga, manga_name.last_update as update_manga from manga_name, manga_chapter where manga_name.id=manga_chapter.id_manga and manga_chapter.date_add > ".($beforeStart)." and manga_chapter.date_add < ".($beforeEnd)." group by manga_chapter.id order by manga_chapter.id desc";
			$query = $db->Query($sql);
			
			$contQ = mysql_num_rows($query);
			if ($contQ > 0) break;
		}
		
		$hAdd = NULL;
		$iAdd = NULL;
		while ($dat = mysql_fetch_array($query)) {
			if ($hAdd == NULL) 
				$hAdd = "history.id_chapter='".$dat['id']."'";
			else
				$hAdd .= " or history.id_chapter='".$dat['id']."'";	
				
			if ($iAdd == NULL)
				$iAdd = "id_chapter='".$dat['id']."'";
			else
				$iAdd .= " or id_chapter='".$dat['id']."'";
			
		}
		$hQuery = "";
		$hDat = array();
		if (isset($dUser)) {
			$hQuery = $db->Query("select history.id_chapter from history where (".$hAdd.") and history.user='".$dUser['id']."'");
		
			$hDat = array();
			while ($dat = mysql_fetch_array($hQuery)) {
				$hDat[$dat['id_chapter']] = true;	
			}
		}
		
		/*
		$iQuery = $db->query("select * from manga_pict where (".$iAdd.") and page='1' group by id_chapter");
		$iDat1 = array();
		while ($dat = mysql_fetch_array($iQuery)) {
			$iDat1[$dat['id_chapter']] = $dat['name'];
		}
		
		$iQuery = $db->query("select * from manga_pict where (".$iAdd.") and page='2' group by id_chapter");
		$iDat2 = array();
		while ($dat = mysql_fetch_array($iQuery)) {
			$iDat2[$dat['id_chapter']] = $dat['name'];
		}
		*/
		
		mysql_data_seek($query,0);
?>
<div id="article" class="clearfix">
<?php
	$MAX_CHAPTER = 10;
	$marker = array("black","blue","red","green");
	$dat = mysql_fetch_array($query);
	$datNext = true;
	$started = false;
	$open = false;
	$finished = true;
	$chp = 0;
	$markerColour = 0;
    while ($datNext) {
		$datNext = mysql_fetch_array($query);
		if (!$open) {
			$finished = true;
			$open = true;
			$chp = 0;
			echo "<div id=\"feed\" class=\"nf article panel\">";
				echo "<div class=\"warp-tab\"><div class=\"nf_title\"><a href=\"?sk=mg&id=".$dat["id_manga"]."\">".$dat["name_manga"]."</a></div></div>";
				echo "<div class=\"chapter-container\">";
		}
		
		$chp++;
		if ($finished) $finished = array_key_exists($dat["id"],$hDat);
		if ($chp <= $MAX_CHAPTER)
		{
			echo "<div class=\"".(array_key_exists($dat["id"],$hDat)?"marker " . $marker[($markerColour%count($marker))]:"")."\"><div class=\"warp-tab-left\"><a href=\"read.php?id=".$dat["id"]."\" class=\"title\">".String::mangaRename($dat['name'], $dat['name_manga'])."</a></div></div>";
		} elseif ($chp == $MAX_CHAPTER+1) {
			echo "<div class=\"".(array_key_exists($dat["id"],$hDat)?"marker" . $marker[($markerColour%count($marker))]:"")."\"><div class=\"warp-tab-left\"><a href=\"read.php?id=".$dat["id"]."\" class=\"title\">~ More...</a></div></div>";
		}
		
		if ($datNext['id_manga'] != $dat['id_manga']) {
			if ($open && $datNext['id_manga'] != $dat['id_manga']) echo "</div>";
			echo "<div class=\"".($finished?"readed":"")." image-container\">";
				///*
				echo "<div class=\"thumb-".$dat['id']."-1\"></div>";
				echo "<div class=\"thumb-".$dat['id']."-2\"></div>";
				//*/
				/*
				if (array_key_exists($dat['id'], $iDat2))
				{
					$imagepath = $CFG['MANGA_PATH']."/".$dat['name_manga']."/".$dat['name']."/".$iDat1[$dat['id']];
					$dataUrl = getDataURI($imagepath, 1);
					echo "<img src=\"".$dataUrl."\" />";
					$imagepath = $CFG['MANGA_PATH']."/".$dat['name_manga']."/".$dat['name']."/".$iDat2[$dat['id']];
					$dataUrl = getDataURI($imagepath, 1);
					echo "<img src=\"".$dataUrl."\" />";
				}
				else
				{
					$imagepath = $CFG['MANGA_PATH']."/".$dat['name_manga']."/".$dat['name']."/".$iDat1[$dat['id']];
					$dataUrl = getDataURI($imagepath, 2);
					echo "<img src=\"".$dataUrl."\" />";
				}
				*/
				//echo "<img src=\"handler/image.php?id=".$iDat[$dat['id']]."&res=1\" data-report='error' data-id=\"".$iDat[$dat['id']]."\" />";
				//echo "<img src=\"handler/image.php?id=".($iDat[$dat['id']]+1)."&res=1\" data-report='error' data-id=\"".($iDat[$dat['id']]+1)."\" />";
				//$img = $db->query("select name from manga_pict where id_chapter=\"".$dat['id']."\" limit 0,2");
			echo "</div>";
		}
		
		if ($open && $datNext['id_manga'] != $dat['id_manga']) {
			$open = false;
				echo "<div>";
					echo "<a href=\"?sk=mg&id=".$dat["id_manga"]."\"><input type=\"button\" value=\"Chapter List\" class=\"white\" /></a>";
					echo "<a href=\"?sk=mg&id=".$dat["id_manga"]."\" target=\"_blank\"><button class=\"white image\"><img src=\"images/open_in_new_window.png\" /></button></a>";
				echo "</div>";
			echo "</div>";
			$markerColour++;
		}
		
		$dat = $datNext;
	}
	//echo "</div>";
	
	}
	else
	{
		?>
		
		<div class="dt panel">
			<div class="warp">
				No manga available, add more to in directory and <a href="?sk=sc">Scan Directory</a> to add it.
			</div>
		</div>
		
		<?php
	}
?>
</div>