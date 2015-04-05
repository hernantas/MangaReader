
<?php
	$sort_by = @$_GET['srt'];
	$odr = @$_GET['odr'];
	if (!($sort_by == "name" || $sort_by == "ch_cnt" || $sort_by == "read_count" || $sort_by == "last_update")) $sort_by = "name";
	if ($sort_by == "read_count") $sort_by = "read_count/COUNT(manga_chapter.id)";
	if (!($odr == "asc" || $odr == "desc")) $odr = "asc";
	$search = @$_GET['search'];
	
	if (isset($search))
		$search = trim($search);
?>
<h1><?php echo $search?"Search Result for \"".$search."\"":"Manga Directory" ?></h1>
<div>
	<?php
		//pagination(@$_GET['page'], 10, 100, "?sk=ml");
		
		// Pre Proccess Search
		$eSearch = explode(" ", $search);
		$numBer = count($eSearch);
		$sql = "";
		for($i=0;$i<$numBer;$i++) {
			if ($i>0) $sql .= " or ";
			$sql .= "(manga_name.name like '%". $eSearch[$i] . "%')";	
		}
		$query = $db->Query("select manga_name.*, COUNT(manga_chapter.id) as ch_cnt, read_count/COUNT(manga_chapter.id) as read_count_normal from manga_name, manga_chapter where manga_name.id=manga_chapter.id_manga and (".$sql.") group by manga_name.id order by ".$sort_by." ".$odr);
		
		$mList = array();
		$mNameSrt = array();
		$readed = array();
		$mSrtVal = array();
		while($dat = mysql_fetch_array($query)) {
			//$chpt_num = $db->QueryNum("select * from manga_chapter where id_manga='" . $dat['id'] . "'");
			// $dat['ch_cnt'] = $chpt_num;
			$mNameSrt[] = $dat["name"];
			$mSrtVal[$dat["name"]] = 0;
			
			if (isset($search))
			{
				// a | b | c | d | e | f
				// a b | b c | c d | d e | e f
				// a b c | b c d | c d e | d e f
				// a b c d | b c d e | c d e f
				for ($j=0;$j<$numBer;$j++)
				{
					if ($j==0)
						$cSearch = $eSearch;
					
					for($i=0;$i<count($cSearch);$i++) 
					{
						if ($i > 0)
							$cSearch[$i-1] .= " " . $eSearch[$i+$j];
						
						// echo "<div>".$cSearch[$i]."</div>";
						
						if (strpos(strtolower($dat["name"]), strtolower($cSearch[$i])) !== false)
							$mSrtVal[$dat["name"]]++;
					}
					
					unset($cSearch[count($cSearch)-1]);
				}
			}
			
			$mList[$dat["name"]] = $dat;
		}
		
		if (isset($search) && count($mSrtVal) > 0)
		{
			arsort($mSrtVal);
			$mNameSrt = array();
			foreach ($mSrtVal as $key => $val)
			{
				$mNameSrt[] = $key;
			}
		}
		else if ($sort_by == "name") {
			natsort($mNameSrt);
			if ($odr == "desc") {
				$mNameSrt = array_reverse($mNameSrt);
			}
		}
		if ($login) {
			$qHis = $db->Query("select id_manga, count(distinct id_chapter) as cnr from history where user='".$dUser['id']."' group by id_manga");
			while ($dat = mysql_fetch_array($qHis)) {
				$readed[$dat['id_manga']] = $dat['cnr'];	
			}
		}
	?>
	<div class="clearfix">
		<?php
			if (mysql_num_rows($query)==0)
			{
			?>
				<div class="dt panel">
					<div class="warp">
						No manga available, add more to in directory and <a href="?sk=sc">Scan Directory</a> to add it.
					</div>
				</div>
			<?php
			}
			$id = 1;
		    $last_alphabet = "";
			echo "<div>";
			$card_counter = 0;
			foreach($mNameSrt as $mNL) {
				$new = $mList[$mNL]['last_update']>time()-(6*24*3600)?true:false;
				
				if ($last_alphabet != substr($mList[$mNL]['name'],0,1) && $sort_by == "name" && !isset($search)) {
					echo "</div><div class=\"card_title panel\"><h2>#".substr($mList[$mNL]['name'],0,1)."</h2></div><div class=\"clearfix dt\">";
					$last_alphabet = substr($mList[$mNL]['name'],0,1);
					$card_counter = 0;
				}
				
				// Need Optimization
				$ur_q = $mList[$mNL]['ch_cnt'];
				if (array_key_exists($mList[$mNL]['id'], $readed)) {
					$ur_q -= $readed[$mList[$mNL]['id']];
				}
				
				echo "<div class=\"card ".(($ur_q>0 || !$login)?($new?"new":""):"blur")." ".($mList[$mNL]['completed']==1?"completed":"")." panel\"><div class=\"warp-tab over\">";
				echo "<div class=\"title\"><a href=\"?sk=mg&id=" . $mList[$mNL]['id'] . "\">" . $mList[$mNL]['name'] . "</a>";
				//echo " " . $mSrtVal[$mNL];
					echo (($mList[$mNL]['add_time']==$mList[$mNL]['last_update'] &&  $new)?" <font class=\"green\">New!</font>":"");
				echo "</div>";
				echo "<div class=\"desc\">".($mList[$mNL]['read_count']==0?"Never Read":"Read ".$mList[$mNL]['read_count']." times")."</div>";
				echo "<div>Status: ";
					if ($mList[$mNL]['completed']==1) 
						echo "<img src=\"images/book.PNG\" width=\"16\" title=\"Completed\" height=\"16\" /> Completed";
					else
						echo "Ongoing";
				echo "</div>";
				echo "<div>Chapter: ".$mList[$mNL]['ch_cnt']."</div>";
				echo "<div>Last Update: ".difdate($mList[$mNL]['last_update'])."</div>";
				echo "</div>";
				echo "<div class=\"act\"><a href=\"?sk=mg&id=" . $mList[$mNL]['id'] . "\"><input type=\"button\" class=\"white\" value=\"Chapter List\" /></a></div></div>";
				$card_counter++;
			}
			echo "</div>";
		?>
	</div>
</div>