<?php
	$startIncludeTime = microtime(true); 
	
	$seek = "home";
	if (isset($_GET["sk"])) $seek = $_GET["sk"];
	
	Debug::write("Attempt to include an application wtih key:[".$seek."]");
	if ($app->exists($seek) && file_exists($CFG['FOLDER_PATH'] . "\\application\\" . $app->getFile($seek))) {
		include $CFG['FOLDER_PATH'] . "\\application\\" . $app->getFile($seek);
		Debug::write("Application Found, include successfully");
	} else {
		Debug::write("Application not Found, include failed: ".$app->getFile($seek));
		$string = "error.php?link=".full_url()."&code=".md5("link").md5(full_url());
?>

<div class="panel">
	<h1>Page not found</h1>
    <div class="warp desc">
        Sory, your requested page is not found in our website. If you believe this is an error, please click this link to send error.<br />
        <a href="<?php echo $string ?>"><?php echo $string ?></a><br />
        Or <a href="index.php">click here</a> you can go back to home and continue enjoy our provided manga.
    </div>
</div>
<?php 
	} 
	$endIncludeTime = microtime(true);
	if (ENVIRONMENT == 'development') {
		echo "<div id=\"runtime\">";
		echo "Include Run Time: " . ($endIncludeTime-$startIncludeTime) . "s";
		echo "</div>"; 	
	}
	Debug::write("Include time: [".($endIncludeTime-$startIncludeTime)."] seconds");
?>