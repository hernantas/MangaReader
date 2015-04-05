<div class="panel dt">
	<div class="warp-text warp-text-first page-divider">
    	<h1><?php echo L_SERVER_STATUS; ?></h1>
    </div>
    <div class="warp-text">
		<?php
            echo "<div>Manga: ".$db->QueryNum("select * from manga_name")."</div>";
            echo "<div>".L_CHAPTER.": ".$db->QueryNum("select * from manga_chapter")."</div>";
            echo "<div>".L_PICTURE.": ".$db->QueryNum("select * from manga_pict")."</div>";
            echo "<div>History: ".$db->QueryNum("select * from history")."</div>";
            $query = $db->Query("show table status");
            $dbSize = 0;
            while ($dat = mysql_fetch_array($query)) {
                $dbSize += $dat['Data_length'];
            }
			
            echo "<div>".L_DB_SIZE.": ".number_format($dbSize/1024/1024, 2)." MB</div>";
			$numAvg = $db->Query("select manga_chapter.* , COUNT(manga_pict.id) as ppc from manga_chapter, manga_pict where manga_chapter.id=manga_pict.id_chapter group by manga_chapter.id");
			
			$avgNum = 0;
			$num = 0;
			while ($dat = mysql_fetch_array($numAvg)) 
			{
                $avgNum += $dat['ppc'];
				$num++;
            }
			echo "<div>AVG Pict: " . ceil($avgNum/$num) . "</div>";
        ?>
    </div>
</div>