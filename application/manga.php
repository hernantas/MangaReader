<?php
	$AJX = @$_GET['ajx'];
	
	if ($AJX) require_once('../config.php');

	$mid = $_GET['id'];
	$query = $db->Query("select manga_chapter.*, manga_name.name as name_manga, manga_name.completed from manga_name, manga_chapter where manga_name.id=manga_chapter.id_manga and manga_chapter.id_manga='".$mid."'");
	$cDat = array();
	$cSort = array();
	$readed = array();
	$mangaName = "";
	$completed = 0;
	while ($dat = mysql_fetch_array($query)) {
		$mangaName = $dat['name_manga'];
		$completed = $dat['completed'];
		$dat['history_count'] = 0;
		
		// Need Optimization	
		// if ($login) $dat['history_count'] = ($db->QueryNum("select id from history where id_chapter='".$dat['id']."' and user='".$dUser['id']."' limit 1"));
		
		$ren = String::mangaRename($dat['name'], $mangaName);
		if (!array_key_exists($ren, $cDat))
			$dat['name'] = $ren;
		$cSort[] = $dat['name'];
		$cDat[$dat['name']] = $dat;
	}
	natsort($cSort);
	$cSort = array_reverse($cSort);
	if ($login) {
		$qHis = $db->Query("select * from history where id_manga='".$mid."' and user='".$dUser['id']."' group by id_chapter order by id asc");
		while ($dat = mysql_fetch_array($qHis)) {
			$readed[$dat['id_chapter']] = true;
		}
	}
?>
<div class="dt">
	<div class="opt-out">
		<a href="?sk=ml"><button>
			Return <br />
			to <br />
			Manga List
		</button></a>
	</div>
	<div class="panel multi">
	    <div class="warp-text <?php echo (!$AJX)?"warp-text-first":""; ?> page-divider">
	        <?php if (!$AJX) echo "<h1>".$mangaName . ($completed?" <img src=\"images/book.PNG\" width=\"16\" title=\"Completed\" height=\"16\" />":"") . "</h1>"; ?>
	        
	        <div class="clearfix">
	            <?php /* if (!$AJX && $completed) { ?>
	                <div class="option-left">
	                    <span class="desc"><img src="images/book.PNG" width="16" height="16" />Completed</span>
	                </div>
	            <?php }*/ ?>
	            <?php if ($login) { ?>
	            <div class="<?php echo (!$AJX)?"option-right":"option-left"; ?>"><a href="handler/completed.php?id=<?php echo $mid . ($completed?"&reverse=1":""); ?>">Mark as <?php echo ($completed?"Ongoing":"Completed"); ?></a></div>
	            <?php } ?>
	            <div class="<?php  echo (!$AJX)?"option-right":"option-left"; ?>"><a href="handler/read_all.php?id=<?php echo $mid; ?>&amp;usr=<?php echo $dUser['id']; ?>">Read All</a></div>
	        </div>
	    </div>
	    <div class="warp-text-first page-divider">
	    <table class="table-list" cellpadding="0" cellspacing="0" border="0" width="100%">
	        <tr>
	            <td><b>Name</b></td>
	            <td align="center" width="125"><b>Date Add</b></td>
	        </tr>
	        <?php
	            $timeLimit = time()-(6*24*3600);
	            foreach ($cSort as $cs) {
	                $new = $cDat[$cs]['date_add']>$timeLimit?true:false;
	                            
	                echo "<tr class=\"blk ".(array_key_exists($cDat[$cs]['id'],$readed)?"tb_blur":($new?"cnew":""))."\">";
	                    echo "<td class=\"tb_bot first-column\"><a href=\"read.php?id=".$cDat[$cs]['id']."\" ".(array_key_exists($cDat[$cs]['id'],$readed)?"class=\"disabled\"":"").">" . $cDat[$cs]['name'] . "</a></td>";
	                    echo "<td class=\"desc tb_bot\" align=\"center\"><span class=\"desc\">" . difdate($cDat[$cs]['date_add']) . "</span></td>";
	                echo "</tr>";
	            }
	        ?>
	    </table>
	    </div>
	</div>
</div>
<?php if (!$AJX) include "history.php"; ?>