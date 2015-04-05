<div class="page-divider">
	<div class="panel">
        <h3 class="warp-text">Recommended to Read</h3>
        <ul class="list-panel">
        <?php
            $query = $db->Query("select * from manga_name where completed='0' order by last_update asc limit 0,1");
            
            while ($dat = mysql_fetch_array($query)) {
                echo "<li><a href=\"index.php?sk=mg&id=".$dat['id']."\" class=\"\">".$dat['name']." <span class=\"desc\">".date("d/M/Y",$dat['last_update'])."</span></a></li>";
            }
        ?>
        </ul>
    </div>
</div>