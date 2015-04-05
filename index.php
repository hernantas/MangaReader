<?php
	//$clearDebugger = true;
	include "config.php";
	$sk = @$_GET['sk'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo ($app->exists($sk)?$app->getTitle($sk)." - ":""); ?>Read Manga</title>
<link rel="shortcut icon" href="icon.ico">
<link href="style.css" rel="stylesheet" type="text/css">

<?php

	if ($sk=="" || $sk="home")
	{
		$filenum = 1;
		while (file_exists("thumb/thumb-".$filenum.".css"))
		{
			echo "<link href=\"thumb/thumb-".$filenum.".css\" rel=\"stylesheet\" type=\"text/css\">";
			$filenum++;
		}
	}
	
?>
<script language="javascript" type="text/javascript" src="js/jquery-ui-1.10.3/jquery-1.9.1.js"></script>
<script language="javascript" type="text/javascript" src="js/jquery-ui-1.10.3/ui/jquery-ui.js"></script>
<script language="javascript" type="text/javascript" src="js/jquery.animate-shadow.js"></script>
<script language="javascript" type="text/javascript" src="js/common.js"></script>
</head>

<body>
<div class="controlpanel">
	<?php
		// Navigation Menu Settup
		$navMenu[] = array(	"index"=> 0,
							"url" => "index.php", 
							"image" => "home-32.png", 
							"text" => "Home");
		$navMenu[] = array(	"index"=> 1, 
							"url" => "index.php?sk=ml", 
							"image" => "book-32.png", 
							"text" => "Manga Directory");
		$navMenu[] = array(	"index"=> 1, 
							"url" => "index.php?sk=ml&srt=read_count&odr=desc", 
							"image" => "hot-32.png", 
							"text" => "Popular Manga");
		$navMenu[] = array(	"index"=> 1, 
							"url" => "index.php?sk=ml&srt=last_update&odr=desc", 
							"image" => "new-32.png", 
							"text" => "Newest Chapter");
		if ($login==1) {
			$navMenu[] = array(	"index"=> 2, 
								"url" => "index.php?sk=sc", 
								"image" => "scan-32.png", 
								"text" => "Scan Directory");
			$navMenu[] = array(	"index"=> 2, 
								"url" => "index.php?sk=er", 
								"image" => "erroricon.png", 
								"text" => "Error Reporting");
			$navMenu[] = array(	"index"=> 2, 
								"url" => "index.php?sk=ss", 
								"image" => "list-128.png", 
								"text" => "Server Status");
			$navMenu[] = array(	"index"=> 3, 
								"url" => "index.php?sk=hs", 
								"image" => "history-32.png", 
								"text" => "History");
			$navMenu[] = array(	"index"=> 3, 
								"url" => "handler/logout.php", 
								"image" => "shutdown-128.png", 
								"text" => "Log Out");
		} else {
			$navMenu[] = array(	"index"=> 2, 
								"url" => "index.php?sk=lg", 
								"image" => "shutdown-128.png", 
								"text" => "Login");
		}
		
		// Translate Navigation Menu
		$navDif = -1;
		$navFirst = true;
		foreach($navMenu as $nav) {
			if ($navDif != $nav["index"] && !$navFirst) echo "</ul>";
			if ($navDif != $nav["index"]) echo "<ul ".($navFirst?"class=\"first\"":"").">";
			
			echo "<li ".($navFirst?"class=\"selected\"":"").">";
				echo "<a href=\"".$nav["url"]."\">";
				if ($nav["image"] != NULL) echo "<img src=\"images/".$nav["image"]."\" />";
				echo "<span ".(($nav["image"] == NULL)?"class=\"no-image\"":"").">".$nav["text"]."</span>";
				echo "</a>";
			echo "</li>";
			
			$navDif = $nav["index"];
			$navFirst = false;
		}
		?>
        <a href="#close" class="close">Close</a>
        <?php
		echo "</ul>";
	?>
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
<div class="header">
	<div class="search-header">
		<form method="GET">
			<input type="hidden" name="sk" value="ml" />
			<input type="text" id="tsearch" name="search" placeholder="Search Manga..." value="<?php echo isset($_GET["search"])?$_GET["search"]:""; ?>" />
			<input type="submit" value="Search" />
		</form>
	</div>
    <?php /*<div class="center">
        <a href="#" class="selected">All</a>
        <a href="#">Going</a>
        <a href="#">Completed</a>
        <a href="#">Unread</a>
        <a href="#">Readed</a>
    </div> */
	?>
</div>
<div class="body" id="body" style="width: 460px;">
    <?php
        include $CFG['FOLDER_PATH'] . "\\system\\includer.php";
    ?>
    <div id="debugger">
    </div>
</div>
</body>
</html>