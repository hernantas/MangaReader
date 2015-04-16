<?php
	include "../config.php";
	
	/*
		Mode:
			Fast = Only scaning manga (except total chapter is different)
			Medium = Only scaning until chapter (except total chapter is different)
			Slow - Scan all file and folder
	*/
	$manga = htmlspecialchars_decode($_GET['manga']);
	$mode = @$_GET['mode'];
	
	if (!isset($mode))
	{
		$mode = 1;
	}
	
	$stTime = time();
	
	$dManga = array();
	$newChapter = false;
	$startTime = microtime(true);
	$newManga = false;

	$chDirs = scandir($CFG['MANGA_PATH'] . "/" . $manga);
	
	if (count($chDirs) > 2) 
	{
			
		$query = $db->Query("select * from manga_name where name=\"" . $manga . "\" limit 0,1");
		if (mysql_num_rows($query) == 0) {
			$db->Query("insert into manga_name values('', \"" . $manga . "\", " . $stTime . ", " . $stTime . ", 0, 0, 1)"); 
			$query = $db->Query("select * from manga_name where name=\"" . $manga . "\" limit 0,1");
			
			$newManga = true;
		}
		
		$dManga = mysql_fetch_array($query);
		$db->Query("update manga_name set valid='1' where id=" . $dManga['id']);
		
		if ($mode == 0)
		{
			$db->Query("update manga_chapter set valid='1' where id_manga=" . $dManga['id']);
		}
		
		if ($newManga || $mode > 0)
		{
			$newCh = "";
			$sValCh = "";
			foreach($chDirs as $chDir) 
			{
				if (is_dir($CFG['MANGA_PATH'] . "/" . $manga . "/" . $chDir) && $chDir != '.' && $chDir != "..") {
					$query = $db->Query("select * from manga_chapter where id_manga=\"" . $dManga['id'] . "\" and (name=\"" .  $chDir . "\") limit 0,1");	
					if (mysql_num_rows($query) == 0) {
						if ($newCh == "")
							$newCh = "('',\"" . $chDir . "\"," . $dManga['id'] . "," . $stTime . ",'1')";
						else
							$newCh .= ",('',\"" . $chDir . "\"," . $dManga['id'] . "," . $stTime . ",'1')";
						$newChapter = true;
					} else {
						$dChapter = mysql_fetch_array($query);
						if ($sValCh == "")
							$sValCh = "id=" . $dChapter['id'];
						else
							$sValCh .= " or id=" . $dChapter['id'];	
					}
				}
			}
			if ($sValCh != "") $db->Query("update manga_chapter set valid='1' where " . $sValCh);
			
			if ($newChapter) 
			{
				$db->Query("update manga_name set last_update='" . $stTime . "', valid='1' where id=" . $dManga['id']);
				$db->Query("insert into manga_chapter values" . $newCh);
			}
			
			$query = $db->Query("select manga_chapter.* from manga_chapter where manga_chapter.id_manga='" . $dManga['id'] . "' and valid='1'");
			$picIns = "";
			while ($dat = mysql_fetch_array($query)) 
			{
				// echo $dat['name'] . ": " . $dat['date_add'] . "-" . $stTime . "<br />";
				if ($dat['date_add']==$stTime || $mode > 1) 
				{
					$pcScs = scandir($CFG['MANGA_PATH'] . "/" . $dManga['name'] . "/" . $dat['name']);
					natsort($pcScs);
					
					/*
					if (!$newChapter)
					{
						$pictCount = $db->QueryNum("select manga_pict.* from manga_pict where id_manga='" . $dManga['id'] . "' and id_chapter='".$dat['id']."'");
						
						if ($pictCount == count($pcScs))
						{
							$pcScs = array();
						}
					}
					*/
					
					//if (count($pcScs) == $dat['c_pc']) {
						//$db->Query("delete from manga_pict where id_chapter='".$dat['id']."'");	
					$page= 1;
					foreach($pcScs as $pcSc) 
					{
						if ($pcSc != '.' && $pcSc != '..') 
						{
							if ($picIns == "")
								$picIns = "('',\"" . $pcSc . "\",'" . $dManga['id'] . "','" . $dat['id'] . "','".$page."')";
							else
								$picIns .= ",('',\"" . $pcSc . "\",'" . $dManga['id'] . "','" . $dat['id'] . "','".$page."')";
							$page++;
						}
					}
					//}
				}
			}
			
			if ($mode > 1)
			{
				//echo "delete from manga_pict where id_manga=".$dManga['id']."<br />";
				$db->Query("delete from manga_pict where id_manga=".$dManga['id']);
			}
			if ($picIns != "")
			{
				$db->Query("insert into manga_pict values".$picIns);
			}
		}
	}
	$endTime = microtime(true);
	
	echo number_format($endTime - $startTime, 2);
?>