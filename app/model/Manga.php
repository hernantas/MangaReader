<?php
    namespace Model;

    class Manga
    {
        public function getList($page=0, $sort='friendly_name', $limit=36)
        {
            return $this->db->table('manga')
                ->join('manga_chapter','manga.id', 'manga_chapter.id_manga')
                ->order('manga.friendly_name')->limit($page*$limit, $limit)
                ->group('manga.id')->order($sort, ($sort=='friendly_name'))
                ->get("manga.*, count(manga.id) as cnt");
        }

        public function getCount()
        {
            return $this->db->table('manga')
                ->get("count(manga.id) as cnt")->first()->cnt;
        }

        public function findManga($search, $page=0)
        {
            return $this->db->table('manga')
                ->join('manga_chapter','manga.id', 'manga_chapter.id_manga')
                ->order('manga.friendly_name')->limit($page*36, 36)
                ->where('manga.name', 'LIKE', "%$search%")
                ->group('manga.id')
                ->get("manga.*, count(manga.id) as cnt");
        }

        public function getSearchCount($search)
        {
            return $this->db->table('manga')
                ->where('manga.name', 'LIKE', "%$search%")
                ->get("count(manga.id) as cnt")->first()->cnt;
        }

        public function getImage($id)
        {
            return $this->db->table('manga_image')
                ->join('manga','manga.id', 'manga_image.id_manga')
                ->join('manga_chapter','manga_chapter.id', 'manga_image.id_chapter')
                ->where('manga.id', $id)
                ->where('manga_image.page', '1')
                ->order('manga_image.id')->limit(0, 1)->group('manga_image.id')
                ->get("manga_image.name, manga.name as manga_name, manga_chapter.name as chapter_name");
        }

        public function getImages($idManga, $idChapter, $start, $limit)
        {
            return $this->db->table('manga_image')
                ->where('id_manga', $idManga)
                ->where('id_chapter', $idChapter)
                ->limit($start, $limit)->get();
        }

        public function getImageCount($idManga, $idChapter)
        {
            $result = $this->db->table('manga_image')->where('id_manga', $idManga)
                ->where('id_chapter', $idChapter)->get('count(id) as cnt');
            return $result->first()->cnt;
        }

        public function addReadCount($id)
        {
            $this->db->table('manga')->where('id', $id)
                ->update('`views`=`views`+1');
            $current = $this->db->table('manga')->where('id', $id)
                ->get()->first();

            if (strcmp($current->rankings, '0') === 0)
            {
                // Place at the last of rank
                $lastRank =  $this->db->table('manga')
                    ->order('rankings', false)
                    ->limit(0,1)
                    ->get();
                $rank = 1;
                if (!$lastRank->isEmpty())
                {
                    $rank = $lastRank->first()->rankings + 1;
                }

                $this->db->table('manga')->where('id', $id)
                    ->update(['rankings'=>$rank]);
            }
            else
            {
                // Find rankings above this
                $above = $this->db->table('manga')
                    ->limit(0,1)
                    ->where('rankings', '!=', '0')
                    ->where('rankings', '<', $current->rankings)
                    ->where('views', '<', $current->views)
                    ->order('views', false)
                    ->get();

                // If there is above this and views is less than this
                if (!$above->isEmpty())
                {
                    $above = $above->first();
                    // Update ranking to the above
                    $this->db->table('manga')->where('id', $id)
                        ->update(['rankings'=>$above->rankings]);
                    // Switch Ranking
                    $this->db->table('manga')
                        ->where('id', '!=', $current->id)
                        ->where('rankings', '>=', $above->rankings)
                        ->where('rankings', '<', $current->rankings)
                        ->update('`rankings`=`rankings`+1');
                }
            }
            // Use following query to fix ranking:
            //  set@rownum=0; UPDATE manga SET `manga`.`rankings`= @rownum:=(@rownum+1) WHERE `manga`.`views` != 0 ORDER BY `manga`.`views` DESC
        }

        public function addHistory($idUser, $idManga, $idChapter, $page)
        {
            $result = $this->db->table('user_history')->limit(0,1)
                ->where('id_user', $idUser)
                ->where('id_manga', $idManga)
                ->where('id_chapter', $idChapter)
                ->get();
            if ($result->isEmpty())
            {
                // Insert new history
                $this->db->table('user_history')
                    ->insert(['', $idUser, $idManga, $idChapter, $page, time()]);
                return true;
            }

            // Update last
            $history = $result->first();
            $this->db->table('user_history')->limit(0,1)
                ->where('id_user', $idUser)
                ->where('id_manga', $idManga)
                ->where('id_chapter', $idChapter)
                ->update(['page'=>$page, 'update_at'=>time()]);

            return ($history->update_at < (time()-86400));
        }

        public function getUserHistory($idUser, $page=0, $limit=100)
        {
            return $this->db->table('user_history')
                ->join('manga', 'manga.id', 'user_history.id_manga')
                ->join('manga_chapter', 'manga_chapter.id', 'user_history.id_chapter')
                ->where('user_history.id_user', $idUser)
                ->order('user_history.update_at', false)
                ->limit($page*$limit, $limit)
                ->get(
                    'manga.name as manga, manga.friendly_name as fmanga'.
                    ', manga_chapter.name as chapter, manga_chapter.friendly_name as fchapter'.
                    ', user_history.update_at'
                );
        }

        public function getUserManga($idUser, $page, $limit=36)
        {
            return $this->db->table('user_history')
                ->join('manga', 'manga.id', 'user_history.id_manga')
                ->where('user_history.id_user', $idUser)
                ->order('user_history.update_at', false)
                ->group('user_history.id_manga')
                ->limit($page*$limit, $limit)
                ->get('manga.*');
        }

        public function getMangaHistory($idUser, $idManga)
        {
            return $this->db->table('user_history')
                ->join('manga', 'manga.id', 'user_history.id_manga')
                ->join('manga_chapter', 'manga_chapter.id', 'user_history.id_chapter')
                ->where('user_history.id_user', $idUser)
                ->where('user_history.id_manga', $idManga)
                ->order('user_history.update_at', false)
                ->get(
                    'manga.name as manga, manga.friendly_name as fmanga'.
                    ', manga_chapter.name as chapter, manga_chapter.friendly_name as fchapter'.
                    ', user_history.update_at'
                );
        }

        public function getHistoryCount($idUser, $idManga)
        {
            return $this->db->table('user_history')
                ->where('id_user', $idUser)
                ->where('id_manga', $idManga)
                ->get('count(id) as cnt')->first()->cnt;
        }

        public function getMangaF($name)
        {
            $result = $this->db->table('manga')->where('friendly_name', $name)
                ->limit(0,1)->get();
            return $result->first();
        }

        public function getChapters($id)
        {
            return $this->db->table('manga_chapter')->where('id_manga', $id)
                ->get();
        }

        public function getChapterF($fname)
        {
            $result = $this->db->table('manga_chapter')->where('friendly_name', $fname)
                ->limit(0,1)->get();
            return $result->first();
        }

        public function setOption($id, $key, $value)
        {
            $result = $this->db->table('manga_option')
                ->where('id_manga', $id)
                ->where('option_key', $key)
                ->limit(0,1)
                ->get();
            if ($result->isEmpty())
            {
                $this->db->table('manga_option')
                    ->insert(['', $id, $key, $value]);
            }
            else
            {
                $this->db->table('manga_option')
                    ->where('id_manga', $id)
                    ->where('option_key', $key)
                    ->limit(0,1)
                    ->update(['option_value'=>$value]);
            }
        }

        public function getOption($id, $key)
        {
            $result = $this->db->table('manga_option')
                ->where('id_manga', $id)
                ->where('option_key', $key)
                ->limit(0,1)
                ->get();
            return $result->isEmpty() ? false : $result->first()->option_value;
        }

        public function setTags($id, $tagString)
        {
            $tags = explode(',', $tagString);
            $count = count($tags);
            $sTags = '';
            for ($i = 0; $i < $count; $i++)
            {
                if ($sTags !== '') $sTags .= ', ';
                $sTags .= trim($tags[$i]);
            }
            $this->setOption($id, 'manga_tags', $sTags);
        }

        public function getTags($id)
        {
            $opt = $this->getOption($id, 'manga_tags');
            if ($opt === false)
            {
                return array();
            }
            $tags = explode(',', $tagString);
            $count = count($tags);
            for ($i = 0; $i < $count; $i++)
            {
                $tags[$i] = trim($tags[$i]);
            }
            return $tags;
        }

        public function getFeed($index=0)
        {
            $cfg = page()->config->loadInfo('Manga');
            $times = $this->db->table('manga_chapter')
                ->order('biggest_time', false)
                ->group('week')
                ->group('id_manga')
                ->limit($index*12, 12)
                ->get('id_manga, MAX(`added_at`) as biggest_time, FLOOR(`added_at`/86400) as week');

            $data = array();
            while ($time = $times->row())
            {
                $sql = "SELECT `manga_chapter`.*, `user_history`.`update_at` as history, `manga`.`id` as idmanga, `manga`.`name` as manga, `manga`.`friendly_name` as fmanga ".
                    "FROM `manga_chapter` LEFT JOIN `user_history` ON `manga_chapter`.`id`=`user_history`.`id_chapter`, `manga`".
                    "WHERE `manga_chapter`.`id_manga`=`manga`.`id` ".
                        "AND `manga_chapter`.`id_manga`=$time->id_manga ".
                        "AND `manga_chapter`.`added_at`<=$time->biggest_time ".
                        "AND `manga_chapter`.`added_at`>".($time->week*86400)." LIMIT 0,11";
                $query = $this->db->query($sql);
                $result = array();
                $manga = '';
                $fmanga = '';
                $date = 0;
                $more = false;
                $count = 0;
                $imgs = array();
                while ($row = $query->row())
                {
                    if ($count === 0)
                    {
                        // Get images
                        $thumbs = $this->getImages($row->idmanga, $row->id, 0, 2);

                        while ($thumb = $thumbs->row())
                        {
                            $size = ($thumbs->count()>1?157:314);
                            $img = page()->image->getContentCrop($cfg['path'] . '/' .
                                $row->manga . '/' .
                                $row->name . '/' .
                                $thumb->name,
                                $size, $size);
                            $imgs[] = [
                                'path'=>$img,
                                'size'=>$size
                            ];
                        }
                    }

                    $manga = $row->manga;
                    $fmanga = $row->fmanga;
                    $date = $row->added_at;

                    // Fix name before added to array
                    $row->name = page()->mangalib->nameFix($row->name, $row->manga);
                    // Fix history before added to array
                    $row->history = ($row->history!==null);

                    if ($count < 10)
                    {
                        $result[] = $row;
                    }
                    else
                    {
                        $more = true;
                    }
                    $count++;
                }

                $data[] = [
                    'name'=>$manga,
                    'fname'=>$fmanga,
                    'date'=>page()->date->relative($date),
                    'data'=>$result,
                    'imgs'=>$imgs,
                    'more'=>$more
                ];
            }
            return $data;
        }
    }

?>
