<div class="page-divider">
	<div class="panel">
        <h3 class="warp-text"><?php echo L_TOP_10_MANGA; ?></h3>
        <ul class="list-panel">
        <?php
            $query = $db->Query("select manga_name.* from manga_name, manga_chapter where manga_name.id=manga_chapter.id_manga group by manga_name.id order by read_count/COUNT(manga_chapter.id) desc limit 0,10");
            
            $count = 1;
            while ($dat = mysql_fetch_array($query)) {
                echo "<li><a href=\"index.php?sk=mg&id=".$dat['id']."\"><span class=\"desc\">".$count."</span> ".$dat['name']."</a></liv>";
                $count++;
            }
        ?>
        	<li class="last"><a href="index.php?sk=ml"><?php echo L_MORE; ?></a></li>
        </ul>
    </div>
</div>