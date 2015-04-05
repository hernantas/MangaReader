<?php
	$search = $_GET['search'];
	
	$eSearch = explode(" ", $search);
	$numBer = count($eSearch);
	
	
	
	$sql = "";
	for($i=0;$i<$numBer;$i++) {
		if ($i>0) $sql .= " or ";
		$sql .= "(manga_name.name like '%". $eSearch[$i] . "%')";	
	}
	$sql = "select manga_name.*, COUNT(manga_chapter.id) as ch_cnt from manga_name, manga_chapter where manga_name.id=manga_chapter.id_manga and (".$sql.") group by manga_name.id order by manga_name.read_count desc";
	/*
	for($i=0;$i<$numBer;$i++) {
		for ($j=0;$j<=$i;$j++) {
			if ($i>0) echo " or ";
			echo "(name like '%";
			for ($k=0;$k<($numBer-$i);$k++) {
				echo $eSearch[$j+$k];
				if ($k<($numBer-$i)-1) echo " ";
			}
			echo "%')";
		}
	}*/
	$query = $db->Query($sql);
	$sql = "select * from manga";
?>
<div class="dt panel">
	<div class="warp"><h1>Search Result</h1></div>
	<table cellpadding="5" cellspacing="0" border="0">
		<tr>
	    	<td><b><?php echo L_MANGA_NAME; ?></b></td>
	        <td width="45" align="center"><b><?php echo L_CHAPTER; ?></b></td>
	        <td width="45" align="center"><b><?php echo L_READ; ?></b></td>
	        <td width="95" align="center"><b><?php echo L_LAST_UPDATE; ?></b></td>
	    </tr>
	    <?php
			while ($dat = mysql_fetch_array($query)) {
				echo "<tr class=\"blk\">";
					echo "<td class=\"tb_bot first-column\"><a href=\"?sk=mg&id=" . $dat['id'] . "\">" . $dat['name'] . "</a></td>";
					echo "<td class=\"tb_bot\" align=\"center\">" . $dat['ch_cnt']. "</td>";
					echo "<td class=\"tb_bot\" align=\"center\">" . $dat['read_count'] . "</td>";
					$updateT = date("d-M-Y",$dat['last_update']);
					$todayT = date("d-M-Y");
					echo "<td class=\"tb_bot desc\" align=\"center\">" . ($updateT==$todayT?L_TODAY:$updateT) . "</td>";
				echo "</tr>";
			}
		?>
	</table>
</div>