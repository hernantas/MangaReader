<?php
	$id = $_GET['id'];
	
	$getHTML = file_get_contents("http://www.mangaupdates.com/series.html?id=".$id);
	$getPos = strpos($getHTML, "<div class=\"sContent\" style=\"text-align:justify\">");
	$getPosStart = strpos($getHTML,">",$getPos)+1;
	$getPosEnd = strpos($getHTML,"</div>",$getPosStart)-$getPosStart-1;
	echo substr($getHTML, $getPosStart, $getPosEnd);
?>