<?php
	include "config.php";
	
	$cId = $_GET['id'];
	$page = @$_GET['page'];
	
	if (!$page) $page=1;
		
	$query = $db->Query("select * from manga_chapter where id='" . $cId . "'");
	$dChapter = mysql_fetch_array($query);
	
	$query = $db->Query("select * from manga_name where id='" . $dChapter['id_manga'] . "'");
	$dManga = mysql_fetch_array($query);
	
	$cCq = $db->Query("select * from manga_chapter where id_manga='" . $dManga['id'] . "'");
	$cque = $db->Query("select * from manga_pict where id_manga='" . $dManga['id'] . "' and id_chapter='" . $dChapter['id'] . "' order by page desc");
	
	$last_page = 1;
	if ($dat = mysql_fetch_array($cque)) $last_page = $dat['page'];
	
	if ($page == "last")
		$page = $last_page;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo String::mangaRename($dChapter['name'], $dManga['name']); ?> - Read Manga</title>
<link rel="shortcut icon" href="icon.ico">
<link href="style.css" rel="stylesheet" type="text/css">
<script language="javascript" type="text/javascript" src="js/jquery-ui-1.10.3/jquery-1.9.1.js"></script>
<script language="javascript" type="text/javascript" src="js/jquery-ui-1.10.3/ui/jquery-ui.js"></script>
<script language="javascript" type="text/javascript" src="js/common.js"></script>
</head>

<body class="read-mode">
<div class="header">
	<div class="centered w_limit">
		<div class="menu">
			<a href="index.php">Manga</a><?php echo "<a href=\"index.php?sk=mg&id=".$dManga['id']."\">" . $dManga['name'] . "</a><a href=\"read.php?id=".$cId."\">" . String::mangaRename($dChapter['name'], $dManga['name']) . "</a>"; ?>
    	</div>
    </div>
</div>
<?php if ($login == 1) { ?>
	<div class="user">
    	<div class="name">
        	
			<?php
                echo $dUser['username'];
            ?>,
            <a href="handler/logout.php">log out?</a>
        </div>
    </div>
<?php } ?>
<div class="read-control">
    <div class="w_limit centered rc-bg">
    	<div class="clearfix warp">
            <div class="left"><h1><?php echo $dManga['name']; ?></h1></div>
            <div class="right">Chapter: 
                <select onchange="document.location='read.php?id='+this.value">
                    <?php
                        $cDat = array();
                        $cSort = array();
                        while ($dat = mysql_fetch_array($cCq)) {
                            $dat['name'] = String::mangaRename($dat['name'], $dManga['name']);
                            $cSort[] = $dat['name'];
                            $cDat[$dat['name']] = $dat;
                        }
                        natsort($cSort);
                        $cSort = array_reverse($cSort);
                        $first_chapter = 0;
                        $last_chapter = 0;
                        $prev = 0; $next = 0;
                        $prev_ch=1; $next_ch=1;
                        foreach($cSort as $cs) {
                            if ($prev) {
                                $prev_ch = $cDat[$cs]['id'];
                                $prev = 0;
                            }
                            echo "<option ".($cDat[$cs]['id']==$cId?"selected=\"selected\"":"")." value=\"".$cDat[$cs]['id']."\">".$cDat[$cs]['name']."</option>";
                            if ($cDat[$cs]['id']==$cId) {
                                $prev = 1;
                                $next = 1;	
                            }
                            if (!$next) {
                                $next_ch = $cDat[$cs]['id'];	
                            }
                            $first_chapter = $cDat[$cs]['id'];
                            if (!$last_chapter) $last_chapter = $cDat[$cs]['id'];
                        }
                    ?>
                </select>
                Page:
                <select onchange="document.location='read.php?id=<?php echo $cId ?>&page='+this.value">
                    <?php
						$page_first = 1;
						$page_last = 1;
						
						$seq_last = $last_page;
						$seq_display = 12;
						for ($seq_page=0;($seq_page*$seq_display)<$last_page;$seq_page++) 
						{
							$limit_first = (($seq_page*$seq_display)+1);
							$limit_last = (($seq_page+1)*$seq_display);
							if ( (($seq_page+1)*$seq_display) > $last_page) $limit_last = $seq_last;
							
							if ($limit_first <= $page) 
							{
								$page_first = $limit_first;	
								$page_last = $limit_last;
							}
							
							echo "<option " . (($limit_first<=$page && $limit_last >= $page)?"selected=\"selected\"":"") . " value=\"".$limit_first."\">".$limit_first."-".$limit_last."</option>";
						}
						
						if ($login) 
						{
							$db->Query("insert into history values('','".$dManga['id']."','".$dChapter['id']."','".$page_first."','".$dUser['id']."','".time()."')");	
						}
						/*
                        $last_page = 1;
                        while ($dat = mysql_fetch_array($cque)) {
                            echo "<option ".($dat['page']==$page?"selected=\"selected\"":"")." value=\"".$dat['page']."\">".$dat['page']."</option>";
                            $last_page = $dat['page'];
                        }
						*/	
                    ?>
                </select>
                of <?php echo $last_page; ?>
            </div>
         </div>
    </div>
</div>
<?php
    $pUrl = "";
    $nUrl = "";
	if ($page_first<=$seq_display)
		if ($cId==$first_chapter)
			$pUrl = "index.php?sk=mg&id=".$dManga['id'];
		else
			$pUrl = "read.php?id=".$prev_ch."&page=last";
	else
		$pUrl = "read.php?id=".$cId."&page=".($page_first-$seq_display);

	
	if ($page_last >= $last_page)
		if ($cId == $last_chapter) 
			$nUrl = "index.php?sk=mg&id=".$dManga['id'];
		else
			$nUrl = "read.php?id=".$next_ch;	
	else 
		$nUrl = "read.php?id=".$cId."&page=".($page_last+1);
		
	/*
    if ($page == 1) {
        if ($cId==$first_chapter) {
            $pUrl = "index.php?sk=mg&id=".$dManga['id'];
        } else {
            $qr = $db->QueryNum("select * from manga_pict where id_manga=".$dManga['id']." and id_chapter=".$prev_ch);
            $pUrl = "read.php?id=".$prev_ch."&page=".$qr;
        }
    } else {
        $pUrl = "read.php?id=".$cId."&page=".($page-1);
    }
    
    if ($page==$last_page) {
        if ($cId==$last_chapter)
            $nUrl = "index.php?sk=mg&id=".$dManga['id'];
        else
            $nUrl = "read.php?id=".$next_ch."&page=1";
    } else {
        $nUrl = "read.php?id=".$cId."&page=".($page+1);
    }
	
    if ($page == "") {
        if ($cId==$first_chapter) $pUrl = "index.php?sk=mg&id=".$dManga['id']; else $pUrl = "read.php?id=".$prev_ch;
        if ($cId==$last_chapter) $nUrl = "index.php?sk=mg&id=".$dManga['id']; else $nUrl = "read.php?id=".$next_ch;
    }
	*/
?>
<div class="read">
	<?php
        // if (isset($page)) {
            $query = $db->Query("select * from manga_pict where id_manga='" . $dManga['id'] . "' and id_chapter='" . $dChapter['id'] . "' order by page asc limit ".($page_first-1).",".$seq_display);
            for ($i=1;$dat = mysql_fetch_array($query);$i++) {
				echo "<div>";
					echo "<div class=\"image\">";
						echo "<div class=\"page-warp\">";
							/*
							echo "<div class=\"left\">";
								// echo "<div class=\"container tooltip-right\"><a href=\"read.php?id=".$cId."\"><img src=\"data/image/24/gallery.png\" /></a><span>Back to Page list	</span></div>";
								echo "<div class=\"container tooltip-right\"><a href=\"".$pUrl."\"><img src=\"data/image/24/back.png\" /></a><span>Back to Previous Page</span></div>";
								echo "<div class=\"container tooltip-right\"><a href=\"".full_url()."\"><img src=\"data/image/24/refresh.png\" /></a><span>Refresh</span></div>";
								echo "<div class=\"container tooltip-right\"><a href=\"index.php?sk=hl\"><img src=\"data/image/24/info.png\" /></a><span>Help</span></div>";
							echo "</div>";
							echo "<div class=\"right\">";
							*/
								if ($i==($page_last-$page_first)+1)
									echo "<a href=\"" . $nUrl . "\">";
								else if ($i==1)
									echo "<a href=\"" . $pUrl . "\">";
								
								//--------------------
								$imagepath = $CFG['MANGA_PATH']."/".$dManga['name']."/".$dChapter['name']."/".$dat['name'];
								$imageinfo = getimagesize($imagepath);
								$dataUrl = getDataURI($imagepath);
								echo "<img src=\"".$dataUrl."\" />";
								//--------------------
								
								//echo "<img data-report='error' data-id=\"".$dat['id']."\" src=\"handler/image.php?id=".$dat['id']."\" />";
								if ($i==1 || $i==($page_last-$page_first)+1) echo "</a>";
							// echo "</div>";
						echo "</div>";
					echo "</div>";
				echo "</div>";
			}
            $db->Query("update manga_name set read_count=read_count+1 where id='".$dManga['id']."'");
       /* } else {
            echo "<div class=\"img_prev centered w_limit\">";
            $query = $db->Query("select * from manga_pict where id_manga='" . $dManga['id'] . "' and id_chapter='" . $dChapter['id'] . "' order by page asc");
            while ($dat = mysql_fetch_array($query)) {
                echo "<div class=\"box no-color\"><a href=\"?id=".$cId."&page=".$dat['page']."\"><img src=\"handler/image.php?mid=".$dManga['id']."&cid=".$dChapter['id']."&id=".$dat['id']."&res=1\" /></a></div>";
            }
            echo "</div>";	
        }
		*/
    ?>
</div>

<div class="read_more">
	<a href="<?php echo $nUrl; ?>">Next Page</a>
</div>

<script language="javascript" type="text/ecmascript">
	var prevUrl = <?php echo "\"".$pUrl."\""; ?>;
	var nextUrl = <?php echo "\"".$nUrl."\""; ?>;
</script>
<script language="javascript" type="text/ecmascript" src="js/read_control.js"></script>
</body>
</html>