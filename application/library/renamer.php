<?php if (!defined("BASEPATH")) exit("NO DIRECT SCRIPT ACCESS ALLOWED");
	
	class renamer
	{
		public function manga_rename($name, $manga="") {
			// Rename for Batoto
			//Debug::write("Rewriting name for: ".$name);
			if (substr($name, 0, 3)=="Ch.")
			{
				//Debug::write("Batoto rename type-1");
				if ($manga == "")
					return $name;
				else {
					$name = substr($name, 3);
					return $manga . " " .$name;
				}
			}
			if (substr($name, 0, 4)=="Vol.") {
				//Debug::write("Batoto rename type-2");
				$name = substr($name,6);
				if ($manga == "")
					return $name;
				else {
					$pos = strpos($name, "Ch.");
					$name = substr($name, $pos+3);
					return $manga . " " .$name;
				}
			}
			// Rename for Mangafox
			if (substr($name,0,6)=="Volume") {
				//Debug::write("Mangafox rename type-1");
				$pos = strpos($name, "-");
				return substr($name,$pos+1);
			}
			return $name;
		}
		
		public function format_date($date)
		{
			
		}
		
		public function format_number($number)
		{
			
		}
	}
?>