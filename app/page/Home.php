<?php
    namespace Page;

    class Home
    {
        public function index()
        {
            $this->load->model('Manga');
            $this->load->library('Manga', 'MangaLib');
            $this->load->library('Image');

            $cfg = $this->config->loadInfo('Manga');

            $feed = $this->manga->getFeed();
            $this->load->storeView('Newsfeed', [
                'feed'=>$feed,
                'mangapath'=>$cfg['path']
            ]);

            $this->load->layout('Fresh', [
                'additionalJs'=>[
                    'newsfeed'
                ]
            ]);
        }
    }

?>
