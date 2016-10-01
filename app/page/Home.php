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
            $this->load->library('Manga', 'MangaLib');
            $this->load->library('Image');
            
            $page = (int)$this->input->post('page', 0);
            $data['feed'] = $this->manga->getFeed($page);
            echo json_encode($data, JSON_PARTIAL_OUTPUT_ON_ERROR);
        }
    }

?>
