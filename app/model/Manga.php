<?php
    namespace Model;

    class Manga
    {
        public function isScanEmpty()
        {
            $result = $this->db->table('manga_scan')->limit(0,1)->get();
            return $result->isEmpty();
        }

        public function addScan($list)
        {
            $build = '';
            foreach ($list as $item)
            {
                if ($build !== '')
                {
                    $build .= ', ';
                }

                $item[0] = $this->db->escape($item[0]);
                $item[1] = isset($item[1]) ? $this->db->escape($item[1]) : '';
                $item[2] = isset($item[2]) ? $this->db->escape($item[2]) : '';

                $build .= "('', '".($item[0])."', '".($item[1])."', '".($item[2])."')";
            }
            $this->db->query("INSERT INTO `manga_scan` VALUES $build");
            // $this->db->table('manga_scan')->insert(['', $manga, $chapter, $img]);
        }

        public function currentScan($limit)
        {
            $result = $this->db->table('manga_scan')->limit(0,$limit)->order('id')->get();
            return $result;
        }

        public function removeScan($ids)
        {
            $query = $this->db->table('manga_scan');
            foreach ($ids as $id)
            {
                $query->whereOr('id', $id);
            }
            $query->delete();
        }

        public function hasManga($name)
        {
            $name = page()->manga->toFriendlyName($name);
            return $this->hasMangaF($name);
        }

        public function hasMangaF($name)
        {
            $res = $this->db->table('manga')->where('friendly_name', $name)
                ->limit(0,1)->get();
            return !$res->isEmpty();
        }

        public function getManga($name)
        {
            $name = page()->manga->toFriendlyName($name);
            return $this->getMangaF($name);
        }

        public function getMangaF($name)
        {
            $result = $this->db->table('manga')->where('friendly_name', $name)
                ->limit(0,1)->get();
            return $result->first();
        }

        public function addManga($list)
        {
            $build = '';
            foreach ($list as $item)
            {
                if ($build !== '')
                {
                    $build .= ', ';
                }

                $item[0] = $this->db->escape($item[0]);
                $item[1] = $this->db->escape($item[1]);

                $build .= "('', '".($item[0])."', '".($item[1])."', '".time()."', '".time()."', 'false')";
            }
            $this->db->query("INSERT INTO `manga` VALUES $build");
        }

        public function hasChapterF($id_manga, $name)
        {
            $result = $this->db->table('manga_chapter')->where('id_manga', $id_manga)
                ->where('friendly_name', $name)->limit(0,1)->get();
            return !$result->isEmpty();
        }

        public function getChapterF($id_manga, $name)
        {
            $result = $this->db->table('manga_chapter')->where('id_manga', $id_manga)
                ->where('friendly_name', $name)->limit(0,1)->get();
            return $result->first();
        }

        public function addChapter($list)
        {
            $build = '';
            foreach ($list as $item)
            {
                if ($build !== '')
                {
                    $build .= ', ';
                }

                $item[0] = $item[0];
                $item[1] = $this->db->escape($item[1]);
                $item[2] = $this->db->escape($item[2]);

                $build .= "('', '".($item[0])."', '".($item[1])."', '".($item[2])."', '".time()."')";
            }
            $this->db->query("INSERT INTO `manga_chapter` VALUES $build");
        }

        public function countImage($id_manga, $id_chapter)
        {
            $result = $this->db->table('manga_image')
                ->where('id_manga', $id_manga)
                ->where('id_chapter', $id_chapter)
                ->get();
            return $result->count();
        }

        public function removeImage($id_manga, $id_chapter)
        {
            $this->db->table('manga_image')
                ->where('id_manga', $id_manga)
                ->where('id_chapter', $id_chapter)
                ->delete();
        }
    }
?>
