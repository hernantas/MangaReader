<?php
	include "../config.php";
	
	$id=$_GET['id'];
	$res=@$_GET['res'];
	
	$query = $db->Query("select manga_name.name as name_manga, manga_chapter.name as name_chapter, manga_pict.name as name_pict from manga_name, manga_chapter, manga_pict where manga_name.id=manga_pict.id_manga and manga_chapter.id=manga_pict.id_chapter and manga_pict.id='" . $id . "' group by manga_pict.id limit 0,1");
	$dat = mysql_fetch_array($query);
	
	$imagepath=$CFG['MANGA_PATH']."/".$dat['name_manga']."/".$dat['name_chapter']."/".$dat['name_pict'];
	
	$imageinfo = getimagesize($imagepath);

	$lim = 209;
	$image = NULL;
	// get image height
	list($w, $h, $ext) = $imageinfo;
	
	if ($ext == IMAGETYPE_JPEG)
		$Limage=@imagecreatefromjpeg($imagepath);
	elseif ($ext == IMAGETYPE_PNG)
		$Limage=@imagecreatefrompng($imagepath);
	elseif ($ext == IMAGETYPE_GIF)
		$Limage=@imagecreatefromgif($imagepath);
		
	if ($res) {
		$r = $lim/$w;
		if ($w > $h) $r = $lim/$h;
		
		$nh = $r * $h;
		$nw = $r * $w;
		
		$image = @imagecreatetruecolor($lim,$lim);
		imagecopyresampled($image,$Limage,0,0,0,0,$nw,$nh,$w,$h);
	} else {
		$image = $Limage;	
	}
	
	// fix for PNG-24
	imagealphablending($image, true);
	imagesavealpha($image, true);
	
	//Debug::write("Image-Type #".$id.": ".$imageinfo['mime']);
	header('Content-Type: '.$imageinfo['mime']);
	// echo file_get_contents($imagepath);
	if ($ext == IMAGETYPE_JPEG)
		imagejpeg($image);
	elseif ($ext == IMAGETYPE_PNG)
		imagepng($image);
	elseif ($ext == IMAGETYPE_GIF)
		imagegif($image);
	// imagejpeg($Limage);
	imagedestroy($image);
?>