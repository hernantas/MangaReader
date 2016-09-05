<?php
    namespace Library;

    class Manga
    {
        public function nameFix($name, $manga)
        {
            // Batoto Rename type 1
            if (strpos($name, "Ch.")===0)
			{
				if ($name == "")
                {
					return $name;
				}
                else
                {
					$name = trim($name, 3);
					return $manga . " " .$name;
				}
			}

            // Batoto Rename type 2
			if (strpos($name,"Vol.") !== false)
            {
				$name = substr($name,6);
				if ($manga == "")
                {
                    return $name;
                }
				else
                {
					$pos = strpos($name, "Ch.");
					$name = substr($name, $pos+3);
					return $manga . " " .$name;
				}
			}

			// Rename for Mangafox
			if (strpos($name,"Volume") !== false)
            {
				$pos = strpos($name, "-");
				return substr($name,$pos+1);
			}

			// Rename Mangahere
			if (($pos = strpos($name,"Vol ")) !== false)
			{
				$nextSpace = strpos($name, " ", $pos+4);
				if ($nextSpace === false)
					return substr($name, 0, $pos-3);
				else
					return substr($name, 0, $pos-3) . " - " . substr($name, $nextSpace);
			}
			return $name;
        }

        public function toFriendlyName($name)
        {
            $name = preg_replace('/[^a-z0-9 ]/i', ' ', $name);
            $name = preg_replace('/\s+/', ' ', $name);
            $name = trim($name);
            return strtolower(str_replace(' ', '_', $name));
        }
    }
?>
