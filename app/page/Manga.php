<?php
    namespace Page;

    class Manga
    {
        public function directory()
        {
            $this->load->model('Manga');
            $this->load->library('Date');
            $this->load->library('Image');
            $this->load->helper('Paging');

            $cfg = $this->config->loadInfo('Manga');
            $count = $this->manga->getCount();
            $maxPage = $count / 36;

            $curPage = 1;
            if (($page = $this->uri->pair('page')) !== false)
            {
                $curPage = $page;
            }

            $result = $this->manga->getList($curPage-1);

            $this->load->storeView('MangaDirectory', [
                'mangalist'=>$result,
                'mangapath'=>$cfg['path'],
                'page'=>paging($curPage, $maxPage),
                'curpage'=>$curPage
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

        public function chapter()
        {
            $this->load->storeView('MangaChapter');

            $this->load->layout('Fresh', [
                'title'=>'Directory'
            ]);
        }
    }
?>
