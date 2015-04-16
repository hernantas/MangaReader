<div class="panel dt">
	<div class="warp-text warp-text-first page-divider">
    	<h1><?php echo L_HISTORY; ?></h1>
    </div>
    <?php
        if ($login) {
    ?>
    <table cellpadding="5" cellspacing="0" border="0" width="100%">
    <?php
        
        if (isset($mid)) {
            $qH = $db->Query("select history.*, manga_chapter.name, manga_name.name as manga_name from history, manga_chapter, manga_name where manga_name.id=manga_chapter.id_manga and history.id_manga='".$mid."' and manga_chapter.id=history.id_chapter and user='".$dUser['id']."' group by history.id order by id desc limit 0,32");	
        } else {
            $qH = $db->Query("select history.*, manga_chapter.name, manga_name.name as manga_name from history, manga_chapter, manga_name where manga_name.id=manga_chapter.id_manga and manga_chapter.id=history.id_chapter and user='".$dUser['id']."' group by history.id order by id desc limit 0,256");	
        }
        echo "<tr class=\"blk\">
						<td class=\"first-column\" colspan=\"3\">Today</td>
					</tr>";
		$nDate = "";
		if (mysql_num_rows($qH) == 0) 
		{
			echo "<tr class=\"blk\">
					<td class=\"first-column\" colspan=\"3\">No history yet for this manga</td>
				</tr>";	
		}
        while ($dat = mysql_fetch_array($qH)) 
		{
			$f = false;
			if ($nDate != date("d/m/Y",$dat['time']) && $nDate != "") {
				echo "<tr class=\"blk\">
						<td class=\"first-column\" colspan=\"3\">".$nDate."</td>
					</tr>";
					$f= true;
			}
            echo "<tr class=\"blk\">
					<td class=\"desc first-column double\">".(($dat['time']==0)?"??:?? ??":date("h:i A",$dat['time']))."</td>
                    <td class=\"\"><a href=\"read.php?id=".$dat['id_chapter']."\">".String::mangaRename($dat['name'], $dat['manga_name'])."</a></td>
                    <td class=\"last-column\"><a href=\"read.php?id=".$dat['id_chapter']."&page=".$dat['id_page']."\">Page ".$dat['id_page']."</a></td>
                </tr>";
				
			$nDate = date("d/m/Y",$dat['time']);
        }
    ?>
    </table>
    
    <?php 
    } else {
        echo "Login first";	
    }
	?>
</div>