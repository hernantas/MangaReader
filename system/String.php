<?php
	class String {
		public static function numF($num, $dec=0) {
			return number_format($num, $dec);	
		}
		
		private static function actualRenamer($name, $manga)
		{
			if (substr($name, 0, 3)=="Ch.")
			{
				Debug::write("Batoto rename type-1");
				if ($manga == "")
					return $name;
				else {
					$name = substr($name, 3);
					return $manga . " " .$name;
				}
			}
			if (substr($name, 0, 4)=="Vol.") {
				Debug::write("Batoto rename type-2");
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
				Debug::write("Mangafox rename type-1");
				$pos = strpos($name, "-");
				return substr($name,$pos+1);
			}
			
			// Rename Mangahere
			if (!(strpos($name,"Vol ") === false))
			{
				Debug::write("Mangahere rename type-1");
				
				$pos = strpos($name,"Vol ");
				$nextSpace = strpos($name, " ", $pos+4);
				
				//return $name;
				if ($nextSpace === false)
					return substr($name, 0, $pos-3);
				else
					return substr($name, 0, $pos-3) . " - " . substr($name, $nextSpace);
			}
			
			return $name;
		}
		
		public static function mangaRename($name, $manga="") {
			// Rename for Batoto
			$oldName = $name;
			
			$name = self::actualRenamer($name, $manga);
			
			if ($oldName != $name)
				Debug::write("Rewriting name for: \"".$oldName ."\" to \"".$name."\"");
			return $name;
		}
	}
?>