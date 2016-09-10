<?php
    namespace Page;

    class Home
    {
        public function index()
        {
            $this->load->storeView('FeedContainer');
            $this->load->layout('Fresh', [
                'additionalJs'=>[
                    'newsfeed'
                ]
            ]);
        }

        public function feed()
        {
            $this->load->model('Manga');
            $feed = 0;

            $page = (int)$this->input->post('page', 0);
            $feed = $this->manga->getFeed($page);

            if ($feed->isEmpty())
            {
                if ($this->manga->hasFeed($page))
                {
                    echo "1";
                }
                else
                {
                    echo "0";
                }
            }
            else
            {
                $this->load->library('Manga', 'MangaLib');
                $this->load->library('Image');
                $cfg = $this->config->loadInfo('Manga');
                $this->load->view('Newsfeed', [
                    'feed'=>$feed,
                    'mangapath'=>$cfg['path']
                ]);
            }
        }
    }

?>
