<?php
    namespace Page;

    class Home
    {
        public function index()
        {
            $this->load->model('Manga');
            $this->load->library('Manga', 'MangaLib');

            $feed = $this->manga->getFeed();
            $this->load->storeView('Newsfeed', ['feed'=>$feed]);

            $this->load->layout('Fresh', [
                'additionalJs'=>[
                    'newsfeed'
                ]
            ]);
        }
    }

?>
