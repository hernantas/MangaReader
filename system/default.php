<?php
	function full_url()
	{
		$s = empty($_SERVER["HTTPS"]) ? '' : ($_SERVER["HTTPS"] == "on") ? "s" : "";
		$sp = strtolower($_SERVER["SERVER_PROTOCOL"]);
		$protocol = substr($sp, 0, strpos($sp, "/")) . $s;
		$port = ($_SERVER["SERVER_PORT"] == "80") ? "" : (":".$_SERVER["SERVER_PORT"]);
		$host = (isset($_SERVER['HTTP_HOST']) && !empty($_SERVER['HTTP_HOST']))? $_SERVER['HTTP_HOST']:$_SERVER['SERVER_NAME'];
		Debug::write("Getting Full Url");
		return $protocol . "://" . $host . $port . $_SERVER['REQUEST_URI'];
	}
	
	function difdate($date1) {
		//if (is_string($date1)) $date1 = strtotime($date1);
		//if (is_string($date2)) $date2 = strtotime($date2);
		$date1 += 6*3600;
		$today = strtotime("today");
		$yesterday = strtotime("-1 days");
		if ($date1 >= $today)
			return date("h:i A", $date1);
		elseif ($date1 >= $yesterday)
			return "Yesterday, ".date("h:i A", $date1);
		else
			return date("M d, Y", $date1);		
	}
	
	function pagination($page, $rowPerPage, $total, $link, $pageBetween=5, $first=true, $last=true, $prev=true, $next=true)
	{
		if (!isset($page))
			$page = 1;
		if ((($page-1)*$rowPerPage > $total) ||
			$page < 1)
			return "Invalid Page";
		
		echo "<ul class=\"navigation-page\">";
			if ($first)
				echo "<li><a href=\"".$link."\">First</a></li>";
			if ($prev)
				echo "<li><a href=\"".$link."&page=".($page-1)."\">Previous</a></li>";
			
			$start = $page-floor($pageBetween/2);
			if ($start < 1)
				$start = 1;
			
			if (abs($page-$total/$rowPerPage)+1 < $pageBetween)
				$start = $page-($pageBetween-abs($page-$total/$rowPerPage))+1;
			
			for ($i = $start; ($i <= $total/$rowPerPage && $pageBetween > 0); $i++, $pageBetween--)
			{
				if ($i==$page)
					echo "<li>".$i."</li>";
				else
					echo "<li><a href=\"".$link."&page=".($i)."\">".$i."</a></li>";
			}
			if ($next)
				echo "<li><a href=\"".$link."&page=".($page+1)."\">Next</a></li>";
			if ($last)
				echo "<li><a href=\"".$link."&page=".($total/$rowPerPage)."\">Last</a></li>";
		echo "</ul>";
	}
	
	function getDataURI($file, $resize=0) {
		$imageinfo = getimagesize($file);
		$contents = file_get_contents($file);
		
		if ($resize == 1 || $resize == 2)
		{
			$image = null;
			$lim = ($resize==1?209:418);
			$Limage = null;
			// get image height
			list($w, $h, $ext) = $imageinfo;
			
			$Limage = @imagecreatefromstring($contents);
			/*
			if ($ext == IMAGETYPE_JPEG)
				$Limage=@imagecreatefromjpeg($file);
			elseif ($ext == IMAGETYPE_PNG)
				$Limage=@imagecreatefrompng($file);
			elseif ($ext == IMAGETYPE_GIF)
				$Limage=@imagecreatefromgif($file);
				*/
			
			$r = $lim/$w;
			if ($w > $h) $r = $lim/$h;
			
			$nh = $r * $h;
			$nw = $r * $w;
			
			$image = @imagecreatetruecolor($lim,$lim);
			@imagecopyresampled($image,$Limage,0,0,0,0,$nw,$nh,$w,$h);
			
			imagealphablending($image, true);
			imagesavealpha($image, true);
			
			//Debug::write("Image-Type #".$id.": ".$imageinfo['mime']);
			//header('Content-Type: '.$imageinfo['mime']);
			// echo file_get_contents($imagepath);
			ob_start();
			if ($ext == IMAGETYPE_JPEG)
				imagejpeg($image);
			elseif ($ext == IMAGETYPE_PNG)
				imagepng($image);
			elseif ($ext == IMAGETYPE_GIF)
				imagegif($image);
			$contents =  ob_get_contents();
			// imagejpeg($Limage);
			ob_end_clean();
			imagedestroy($image);
		}
		
		$base64 = base64_encode($contents); 
		return ('data:' . $imageinfo['mime'] . ';base64,' . $base64);
	}
	
	// Function to get the client ip address
	
?>