<div class="panel dt">
	<div class="warp-text warp-text-first page-divider">
    	<h1>Image Error Report</h1>
        <div class="desc">Fixed must be done manually.</div>
    </div>
    <?php
        if ($login) {
    ?>
    <table cellpadding="5" cellspacing="0" border="0" width="100%">
    	<tr>
        	<td><b>Manga</b></td>
            <td><b>Chapter</b></td>
            <td><b>Image</b></td>
            <td><b>Fixed?</b></td>
        </tr>
    <?php
        $qH = $db->Query("select report_pict.fixed, manga_name.id as manga_id, manga_chapter.id as chapter_id, report_pict.id_pict, manga_chapter.name as chapter, manga_name.name as manga from report_pict, manga_pict, manga_chapter, manga_name where report_pict.id_pict=manga_pict.id and manga_pict.id_manga=manga_name.id and manga_pict.id_chapter=manga_chapter.id order by report_pict.fixed asc limit 0,100");	
        
        while ($dat = mysql_fetch_array($qH)) {
            echo "<tr class=\"blk\">
					<td class=\"first-column tb_bot\"><label for=\"err-img-chk\"><a href=\"?sk=mg&id=".$dat['manga_id']."\">".$dat['manga']."</a></td>
					<td class=\"tb_bot\"><a href=\"read.php?id=".$dat['chapter_id']."\">".$dat['chapter']."</a></td>
                    <td class=\"last-column tb_bot\"><a href=\"handler/image.php?id=".$dat['id_pict']."\" target=\"_blank\">".$dat['id_pict']."</a></td>
					<td class=\"last-column tb_bot\"><label style=\"display:block;\"><input id=\"err-img-chk\" type=\"checkbox\" data-id=\"".$dat['id_pict']."\" ".($dat['fixed']?"checked":"")." /></label></td>
                </tr>";
        }
    ?>
    </table>
    
    <?php 
    } else {
        echo "Login first";	
    }
	?>
</div>