<?php
    namespace Page;

    class Manga
    {
        public function directory()
        {
            $this->load->model('Manga');
            $this->load->library('Date');

            $currentPage = 1;
            if (($page = $this->uri->pair('page')) !== false)
            {
                $currentPage = $page;
            }

            $result = $this->manga->getList($currentPage-1);

            $this->load->storeView('MangaDirectory', [
                'mangalist'=>$result
            ]);

            $this->load->layout('Fresh', [
                'title'=>'Directory'
            ]);
        }

        public function hot()
        {

        }

        public function latest()
        {

        }
    }
?>
