<?php
	include "../config.php";

	$skip = false;
	
	$i=@$_GET['index'];
	if (!isset($i))
		$i = 1;
	
	$fileNum = 1;
	
	if ($i==1)
	{
		$sql = "select manga_chapter.*, manga_name.name as name_manga, manga_name.id as id_manga, manga_name.add_time as add_manga, manga_name.last_update as update_manga from manga_name, manga_chapter where manga_name.id=manga_chapter.id_manga group by manga_chapter.id order by manga_chapter.id desc";
		$rowCount = $db->QueryNum($sql);
		
		if ($rowCount == 0)
		{
			$skip = true;
		}
		
		$skip = true;
		
		$fileNumCheck = 1;
		while (file_exists("../thumb/thumb-".$fileNumCheck.".css"))
		{
			unlink("../thumb/thumb-".$fileNumCheck.".css");
			$fileNumCheck++;
		}
	}
	else
	{
		while (file_exists("../thumb/thumb-".$fileNum.".css"))
			$fileNum++;
	}
	
	if (!$skip)
	{
		$statMicroTime = microtime(true); 

		$start = time();
		$sql = NULL;
		$query = NULL;
		$contQ = 0;
		$number = 1;

		$beforeStart = strtotime("-".$i." days", $start);
		$beforeEnd = strtotime("+1 days", $beforeStart);

		$sql = "select manga_chapter.*, manga_name.name as name_manga, manga_name.id as id_manga, manga_name.add_time as add_manga, manga_name.last_update as update_manga from manga_name, manga_chapter where manga_name.id=manga_chapter.id_manga and manga_chapter.date_add > ".($beforeStart)." and manga_chapter.date_add < ".($beforeEnd)." group by manga_chapter.id order by manga_chapter.id desc";
		$query = $db->Query($sql);

		$contQ = mysql_num_rows($query);

		$iAdd = NULL;
		while ($dat = mysql_fetch_array($query)) 
		{
			if ($iAdd == NULL)
				$iAdd = "id_chapter='".$dat['id']."'";
			else
				$iAdd .= " or id_chapter='".$dat['id']."'";

		}

		$iQuery = $db->query("select * from manga_pict where (".$iAdd.") and page='1' group by id_chapter");
		$iDat1 = array();
		while ($dat = mysql_fetch_array($iQuery)) 
		{
			$iDat1[$dat['id_chapter']] = $dat['name'];
		}



		$iQuery = $db->query("select * from manga_pict where (".$iAdd.") and page='2' group by id_chapter");
		$iDat2 = array();
		while ($dat = mysql_fetch_array($iQuery)) 
		{
			$iDat2[$dat['id_chapter']] = $dat['name'];
		}

		mysql_data_seek($query,0);

		$file = NULL;
		$thumbNum = 0;
		$dat = mysql_fetch_array($query);
		$datNext = true;
		while ($datNext)
		{
			$datNext = mysql_fetch_array($query);

			if ($datNext['id_manga'] != $dat['id_manga'])
			{
				if ($file == NULL)
				{
					$file = fopen("../thumb/thumb-".$fileNum.".css", "w");
				}

				if (array_key_exists($dat['id'], $iDat1))
				{
					fwrite($file, ".thumb-".$dat['id']."-1 {\n");
					$imagepath = $CFG['MANGA_PATH']."/".$dat['name_manga']."/".$dat['name']."/".$iDat1[$dat['id']];
					if (array_key_exists($dat['id'], $iDat2))
					{
						$dataUrl = getDataURI($imagepath, 1);
						fwrite($file, "\t background: url(\"".$dataUrl."\") no-repeat left center;\n");
						fwrite($file, "\t float: left;\n");
						fwrite($file, "\t width: 209px;\n");
						fwrite($file, "\t height: 209px;\n");
					}
					else
					{
						$dataUrl = getDataURI($imagepath, 2);
						fwrite($file, "\t background: url(\"".$dataUrl."\") no-repeat left center;\n");
						fwrite($file, "\t float: left;\n");
						fwrite($file, "\t width: 418px;\n");
						fwrite($file, "\t height: 418px;\n");
					}
					fwrite($file, "}\n");
				}

				if (array_key_exists($dat['id'], $iDat2))
				{
					fwrite($file, ".thumb-".$dat['id']."-2 {\n");
					$imagepath = $CFG['MANGA_PATH']."/".$dat['name_manga']."/".$dat['name']."/".$iDat2[$dat['id']];
					$dataUrl = getDataURI($imagepath, 1);
					fwrite($file, "\t background: url(\"".$dataUrl."\") no-repeat left center;\n");
					fwrite($file, "\t float: right;\n");
					fwrite($file, "\t width: 209px;\n");
					fwrite($file, "\t height: 209px;\n");
					fwrite($file, "}\n");
				}

				$thumbNum+=2;
				if ($thumbNum >= 99)
				{
					$thumbNum = 0;
					$fileNum++;
					fclose($file);
					$file = NULL;
				}
			}

			$dat = $datNext;
			// echo (microtime(true)-$statMicroTime) . "s<br />";
		}
		fclose($file);

		if ($contQ > 0)
		{
			echo (microtime(true)-$statMicroTime);
		}
	}
?>