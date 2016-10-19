<?php
    namespace Library;

    class Manga
    {
        public function nameFix($name, $manga)
        {
            // Batoto Rename type 1 (Vol. with Ch.)
			if ($pos = (strpos($name,"Vol.")) === 0)
            {
                $pos = strpos($name, "Ch.");
				$name = substr($name, $pos+3);
                $name = str_replace(' Read Online', '', $name);
				return $manga . " " . $name;
			}

            // Batoto Rename type 2 (Ch.)
            if (strpos($name, "Ch.")===0)
			{
				$name = substr($name, 3);
                $name = str_replace(' Read Online', '', $name);
				return $manga . " " .$name;
			}

            // MangaSee Rename
            if (strpos($name, "Chapter")===0)
			{
                $name = str_replace('Chapter', $manga, $name);
				return $name;
			}
<<<<<<< HEAD
            elseif (strpos($name, "#")===0)
			{
                // Rename Type 2
                $name = str_replace('#', $manga, $name);
				return $name;
			}
=======
>>>>>>> 44a3dfba23baa4606b3146026345fd2af327fcac

            // Rename for Mangafox
            if (strpos($name,'Volume') === 0)
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

        public function toFriendlyNameFix($chapter, $manga)
        {
            $name = $this->nameFix($chapter, $manga);
            return $this->toFriendlyName($name);
        }
    }
?>
