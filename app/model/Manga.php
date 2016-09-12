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
                    ->where('rankings', $current->rankings-1)
                    ->where('rankings', '!=', '0')
                    ->where('views', '<=', $current->views)
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

        public function hasFeed($index=0)
        {
            return !$this->db->table('manga_chapter')
                ->where('manga_chapter.added_at', '<=', time()-($index*86400))
                ->limit(0,1)
                ->get()->isEmpty();
        }

        public function getFeed($index=0)
        {
            return $this->db->table('manga_chapter')
                ->join('manga', 'manga.id', 'manga_chapter.id_manga')
                ->where('manga_chapter.added_at', '>', time()-(($index+1)*86400))
                ->where('manga_chapter.added_at', '<=', time()-($index*86400))
                ->order('manga_chapter.added_at', false)
                ->get('manga_chapter.*, manga.id as idmanga, manga.name as manga, manga.friendly_name as fmanga');
        }
    }

?>
