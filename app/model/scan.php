<?php
    namespace Model;

    class Scan
    {
        public function isScanEmpty()
        {
            $result = $this->db->table('manga_scan')->limit(0,1)->get();
            return $result->isEmpty();
        }

        public function existsSet()
        {
            $this->db->table('manga')->update(['exists'=>'0']);
            $this->db->table('manga_chapter')->update(['exists'=>'0']);
            $this->reset();
        }

        public function reset()
        {
            $this->db->query('TRUNCATE TABLE `manga_scan`');
        }

        public function addScan($list)
        {
            $build = '';
            $data = array();
            $row = 0;
            foreach ($list as $item)
            {
                if ($build !== '')
                {
                    $build .= ', ';
                }

                $build .= "(NULL, :col_".$row."_1, :col_".$row."_2, :col_".$row."_3)";
                $data["col_".$row."_1"] = $item[0];
                $data["col_".$row."_2"] = isset($item[1]) ? $item[1] : '';
                $data["col_".$row."_3"] = isset($item[2]) ? $item[2] : '';
                $row++;
            }
            $this->db->bind("INSERT INTO `manga_scan` VALUES $build", $data);
        }

        public function currentScan($limit)
        {
            $result = $this->db->table('manga_scan')->limit(0,$limit)->order('id', 'desc')->get();
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

        public function hasMangaF($name)
        {
            $res = $this->db->table('manga')->where('friendly_name', $name)
                ->limit(0,1)->get();
            return !$res->isEmpty();
        }

        public function getMangaF($name)
        {
            $result = $this->db->table('manga')->where('friendly_name', $name)
                ->limit(0,1)->get();
            return $result->first();
        }

        public function getMangas($page, $limit)
        {
            $this->db->table('manga')->join('manga_chapter', 'manga.id', 'manga_chapter.id_manga')
                ->order('manga.friendly_name')->limit($page, $limit)
                ->get('manga.*, count(manga_chapter.id)');
        }

        public function addManga($list)
        {
            $build = '';
            $data = array();
            $row = 0;
            foreach ($list as $item)
            {
                if ($build !== '')
                {
                    $build .= ', ';
                }

                $build .= "(NULL, :col_".$row."_1, :col_".$row."_2, '".time()."', '".time()."', '0', '0', '1')";
                $data["col_".$row."_1"] = $item[0];
                $data["col_".$row."_2"] = $item[1];
                $row++;
            }
            $this->db->bind("INSERT INTO `manga` VALUES $build", $data);
        }

        public function setExistsManga($list)
        {
            $query = $this->db->table('manga');
            foreach ($list as $item)
            {
                $query->whereOr('friendly_name', $item);
            }
            $query->update(['exists'=>'1']);
        }

        public function updateMangaTime($ids)
        {
            $query = $this->db->table('manga');
            foreach ($ids as $id)
            {
                $query->whereOr('id', $id);
            }
            $query->update(['update_at'=>time()]);
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
            $data = array();
            $row = 0;
            foreach ($list as $item)
            {
                if ($build !== '')
                {
                    $build .= ', ';
                }

                $build .= "(NULL, :col_".$row."_1, :col_".$row."_2, :col_".$row."_3, '".time()."', '1')";
                $data["col_".$row."_1"] = $item[0];
                $data["col_".$row."_2"] = $item[1];
                $data["col_".$row."_3"] = $item[2];
                $row++;
            }
            $this->db->bind("INSERT INTO `manga_chapter` VALUES $build", $data);
        }

        public function setExistsChapter($list)
        {
            $query = $this->db->table('manga_chapter');
            foreach ($list as $item)
            {
                $query->whereOr('id', $item[1]);
            }
            $query->update(['exists'=>'1']);
        }

        public function countImage($id_manga, $id_chapter)
        {
            $result = $this->db->table('manga_image')
                ->where('id_manga', $id_manga)
                ->where('id_chapter', $id_chapter)
                ->get();
            return $result->count();
        }

        public function removeImage($list)
        {
            $build = '';
            $data = array();
            $row = 0;
            foreach ($list as $item)
            {
                if ($build !== '')
                {
                    $build .= ' OR ';
                }

                $build .= "(`id_manga`=:col_".$row."_1 AND `id_chapter`=:col_".$row."_2)";
                $data["col_".$row."_1"] = $item[0];
                $data["col_".$row."_2"] = $item[1];
                $row++;
            }
            $this->db->bind("DELETE FROM `manga_image` WHERE $build", $data);
        }

        public function addImage($list)
        {
            $build = '';
            $data = array();
            $row = 0;
            foreach ($list as $item)
            {
                if ($build !== '')
                {
                    $build .= ', ';
                }

                $build .= "(NULL, :col_".$row."_1, :col_".$row."_2, :col_".$row."_3, :col_".$row."_4)";
                $data["col_".$row."_1"] = $item[0];
                $data["col_".$row."_2"] = $item[1];
                $data["col_".$row."_3"] = $item[2];
                $data["col_".$row."_4"] = $item[3];
                $row++;
            }
            $this->db->bind("INSERT INTO `manga_image` VALUES ".$build, $data);
        }

        public function removeDeleted($limitManga=200, $limitChapter=100)
        {
            $result = $this->db->table('manga')->where('exists', '0')
                ->order('id')->limit(0,$limitManga)->get();
            if (!$result->isEmpty())
            {
                // Delete manga only (prevent Execution Timeout),
                // chapter will be deleted next
                $query = $this->db->table('manga');
                while ($row = $result->row())
                {
                    $query->whereOr('id', $row->id);
                }
                $query->delete();

                // Delete Manga Option
                $result->reset();
                $query = $this->db->table('manga_option');
                while ($row = $result->row())
                {
                    $query->whereOr('id_manga', $row->id);
                }
                $query->delete();
                return true;
            }

            $result = $this->db->table('manga_chapter')
                ->where('exists', '0')->order('id')->limit(0,$limitChapter)->get();

            if (!$result->isEmpty())
            {
                // Delete manga chapter
                $chapter = $this->db->table('manga_chapter');
                while ($row = $result->row())
                {
                    $chapter->whereOr('id', $row->id);
                }
                $chapter->delete();

                // Delete chapter image
                $result->reset();
                $image = $this->db->table('manga_image');
                while ($row = $result->row())
                {
                    $image->whereOr('id_chapter', $row->id);
                }
                $image->delete();

                // Delete user history
                $result->reset();
                $history = $this->db->table('user_history');
                while ($row = $result->row())
                {
                    $history->whereOr('id_chapter', $row->id);
                }
                $history->delete();
                return true;
            }

            return false;
        }
    }
?>
