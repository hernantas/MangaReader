<?php
	$searchHMTL = file_get_contents("http://www.mangaupdates.com/search.html?search=Yotsuba");
	$searchPos = 0;
	$searchPos = strpos($searchHMTL,"http://www.mangaupdates.com/series.html?id");
	if ($searchPos!==false) {
		$searchPosStart = strpos($searchHMTL,"=",$searchPos)+1;
		$searchPosEnd = strpos($searchHMTL," ",$searchPosStart)-$searchPosStart-1;
		$id = substr($searchHMTL,$searchPosStart,$searchPosEnd);
	} else {
		echo "There is no result found in it so no manga";	
	}
?>
<?php
	//$id = $_GET['id'];
	
	$getHTML = file_get_contents("http://www.mangaupdates.com/series.html?id=".$id);
	$getPos = strpos($getHTML, "<div class=\"sContent\" style=\"text-align:justify\">");
	$getPosStart = strpos($getHTML,">",$getPos)+1;
	$getPosEnd = strpos($getHTML,"</div>",$getPosStart)-$getPosStart-1;
	echo substr($getHTML, $getPosStart, $getPosEnd);
?>