<div class="page-divider">
	<div class="panel">
        <h3 class="warp-text">Latest 10 Manga Update</h3>
        <ul class="list-panel">
        <?php
            $query = $db->Query("select * from manga_name order by last_update desc limit 0,10");
            
            $count = 1;
            while ($dat = mysql_fetch_array($query)) {
                echo "<li><a href=\"index.php?sk=mg&id=".$dat['id']."\" class=\"\"><span class=\"desc\">".date("j.n",$dat['last_update'])."</span> ".$dat['name']."</a></li>";
                $count++;
            }
        ?>
        	<li class="last"><a href="index.php?sk=ml"><?php echo L_MORE; ?></a></li>
        </ul>
    </div>
</div>