<?php
    namespace Page;

    class Importer
    {
        public function index()
        {
            $this->load->storeView('ImportOption');
            $this->load->layout('Fresh', [
                'title'=>'Import/Export'
            ]);
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
