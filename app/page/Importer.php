<?php
    namespace Page;

    class Importer
    {
        public function index()
        {
            $this->load->storeView('ImportOption');
            $this->load->layout('Fresh', [
                'title'=>'Import/Export',
                'additionalJs'=>[
                    'import'
                ]
            ]);
        }

        public function import0()
        {
            $this->load->library("Manga", "MangaLib");
            $timeStart = microtime(true);
            $dbname = $this->input->post('dbname');
            $page = $this->input->post('page', 0);

            $cfg = $this->config->load('DB');
            if ($cfg === false)
            {
                $cfg = $this->config->loadInfo('DB');
            }

            $mangaLimit = 10;
            $chapterLimit = 10;
            $historyLimit = 10;

            $this->db->database($dbname);
            $manga = $this->db->table('manga_name')
                ->limit($page*$mangaLimit, $mangaLimit)
                ->get('name, add_time, last_update');
            $chapter = $this->db->table('manga_chapter')
                ->join('manga_name', 'manga_name.id', 'manga_chapter.id_manga')
                ->order('manga_name.id')
                ->limit($page*$chapterLimit, $chapterLimit)
                ->get('manga_name.name as manga, manga_chapter.name as chapter'.
                    ', manga_chapter.date_add');
            $history = $this->db->table('history')
                ->join('manga_name', 'manga_name.id', 'history.id_manga')
                ->join('manga_chapter', 'manga_chapter.id', 'history.id_chapter')
                ->join('user', 'user.id', 'history.user')
                ->order('manga_name.id')
                ->limit($page*$historyLimit, $historyLimit)
                ->get('manga_name.name as manga, manga_chapter.name as chapter'.
                    ', user.username, history.time');

            $this->db->database($cfg['database']);

            while ($row = $manga->row())
            {
                $name = $this->mangalib->toFriendlyName($row->name);
                $this->db->table('manga')->where('friendly_name', "$name")
                    ->limit(0,1)
                    ->update([
                        'added_at'=>$row->add_time,
                        'update_at'=>$row->last_update
                    ]);
            }

            while ($row = $chapter->row())
            {
                $mangaName = $this->mangalib->toFriendlyName($row->manga);
                $chapterName = $this->mangalib->toFriendlyNameFix($row->chapter, $row->manga);
                $this->db->table('manga_chapter')
                    ->join('manga', 'manga.id', 'manga_chapter.id_manga')
                    ->where('manga_chapter.friendly_name', "$chapterName")
                    ->where('manga.friendly_name', "$mangaName")
                    ->limit(0,1)
                    ->update([
                        'manga_chapter.added_at'=>$row->date_add
                    ]);
            }

            $this->load->model('Manga');

            while ($row = $history->row())
            {
                $users = $this->db->table('user')->where('name', 'LIKE', $row->username)
                    ->get();
                if ($users->isEmpty()) continue;

                $mangaName = $this->mangalib->toFriendlyName($row->manga);
                $chapterName = $this->mangalib->toFriendlyNameFix($row->chapter, $row->manga);
                $mangas = $this->db->table('manga')
                    ->join('manga_chapter', 'manga_chapter.id_manga', 'manga.id')
                    ->where('manga_chapter.friendly_name', "$chapterName")
                    ->where('manga.friendly_name', "$mangaName")
                    ->limit(0,1)
                    ->get('manga.id as idmanga, manga_chapter.id as idchapter');
                if ($mangas->isEmpty()) continue;

                if ($this->manga->addHistory(
                    $users->first()->id,
                    $mangas->first()->idmanga,
                    $mangas->first()->idchapter, '1'))
                {
                    $this->manga->addReadCount($mangas->first()->idmanga);
                }
            }

            echo '{'.
                '"result": "'.(($manga->isEmpty() && $chapter->isEmpty() && $history->isEmpty()) ? 'done' : 'success') . '",' .
                '"time": '.(microtime(true) - $timeStart) .
            '}';
        }

        public function export()
        {
            if ($this->auth->getUserOption('privilege') != 'admin')
            {
                $this->router->redirect();
            }

            define("FILE_EOL", "<br />");

            $result = $this->db->table('user_history')
                ->join('manga', 'manga.id', 'user_history.id_manga')
                ->join('manga_chapter', 'manga_chapter.id', 'user_history.id_chapter')
                ->get('manga.name as manga, manga_chapter.name as chapter'.
                    ', user_history.update_at as read_time');
            while ($row = $result->row())
            {
                echo "($row->manga; $row->chapter; $row->read_time)".FILE_EOL;
            }

            $result = $this->db->table('manga')->get('name, added_at, update_at, views, rankings');
            while ($row = $result->row())
            {
                echo "($row->name; $row->added_at; $row->update_at; $row->views; $row->rankings)".FILE_EOL;
            }

            $result = $this->db->table('manga_chapter')->get('name, added_at');
            while ($row = $result->row())
            {
                echo "($row->name; $row->added_at)".FILE_EOL;
            }
        }
    }
?>
