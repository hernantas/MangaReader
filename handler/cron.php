<?php
	include "config.php";
	
	echo dirname( dirname(__FILE__) );
	
	$path = $CFG['MANGA_PATH'];
	$manga_folder = scandir($CFG['MANGA_PATH']);
	$f = null;
	
	foreach ($manga_folder as $f)
	{
		if ($f != "." && $f != ".." && is_dir($path.'\\'.$f))
		{
			echo $f . "\n";
		}
	}
?>