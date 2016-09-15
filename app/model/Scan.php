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
            $this->db->query('TRUNCATE TABLE `manga_scan`');
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
            foreach ($list as $item)
            {
                if ($build !== '')
                {
                    $build .= ', ';
                }

                $item[0] = $this->db->escape($item[0]);
                $item[1] = $this->db->escape($item[1]);

                $build .= "('', '".($item[0])."', '".($item[1])."', '".time()."', '".time()."', '0', '0', '1')";
            }
            $this->db->query("INSERT INTO `manga` VALUES $build");
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
            foreach ($list as $item)
            {
                if ($build !== '')
                {
                    $build .= ', ';
                }

                $item[0] = $item[0];
                $item[1] = $this->db->escape($item[1]);
                $item[2] = $this->db->escape($item[2]);
                $build .= "('', '".($item[0])."', '".($item[1])."', '".($item[2])."', '".time()."', '1')";
            }
            $this->db->query("INSERT INTO `manga_chapter` VALUES $build");
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
            foreach ($list as $item)
            {
                if ($build !== '')
                {
                    $build .= ' OR ';
                }
                $build .= "(`id_manga`='$item[0]' AND `id_chapter`='$item[1]')";
            }
            $this->db->query("DELETE FROM `manga_image` WHERE $build");
        }

        public function addImage($list)
        {
            $build = '';
            foreach ($list as $item)
            {
                if ($build !== '')
                {
                    $build .= ', ';
                }

                $build .= "('', '$item[0]', '$item[1]', '$item[2]', '$item[3]')";
            }
            $this->db->query("INSERT INTO `manga_image` VALUES ".$build);
        }

        public function setExistsImage($list)
        {
            $build = '';
            foreach ($list as $item)
            {
                if ($build !== '')
                {
                    $build .= ' AND ';
                }

                $build .= "(`id_manga`='$item[0]' OR `id_chapter`='$item[1]')";
            }
            $this->db->query("UPDATE `manga_image` set `exists`='1' WHERE $build");
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
