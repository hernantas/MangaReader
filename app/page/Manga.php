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

        private function chapter()
        {
            $this->load->model('Manga');
            $this->load->library('Manga', 'MangaLib');
            $this->load->library('Date');

            $manga = $this->manga->getMangaF($this->uri->segment(2));
            $result = $this->manga->getChapters($manga->id);

            $order = array();
            $chapters = array();
            while ($row = $result->row())
            {
                $name = $this->mangalib->nameFix($row->name, $manga->name);
                $order[] = $name;
                $chapters[$name] = $row;
            }

            natsort($order);

            $this->load->storeView('MangaChapter', [
                'manga'=>$manga,
                'chapters'=>$chapters,
                'order'=>$order
            ]);

            $this->load->layout('Fresh', [
                'title'=>$manga->name
            ]);
        }

        private function read($chapter)
        {
            $this->load->layout('Fresh', [
                'title'=>'Directory'
            ]);
        }

        public function route()
        {
            if (($chapter = $this->uri->pair('chapter')) !== false)
            {
                $this->read($chapter);
            }
            else
            {
                $this->chapter();
            }
        }
    }
?>
