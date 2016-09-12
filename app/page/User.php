<?php
    namespace Page;

    class User
    {
        public function index()
        {
            $this->router->redirect('user/profile');
        }

        public function profile()
        {
            $this->auth->requireLogin();

            $this->load->model('Manga');
            $this->load->library('Manga', 'MangaLib');
            $this->load->library('Image');
            $this->load->library('Date');

            $cfg = $this->config->loadInfo('Manga');

            $mangas = $this->manga->getUserManga(
                $this->auth->getUserId(), 0, 36);
            $history = $this->manga->getUserHistory(
                $this->auth->getUserId(), 0, 31);

            $this->load->storeView('User', [
                'mangas'=>$mangas,
                'history'=>$history,
                'mangapath'=>$cfg['path']
            ]);

            $this->load->layout('Fresh', [
                'title'=>'My Reading History',
                'additionalJs'=>[
                    'cardsorter'
                ]
            ]);
        }

        public function history()
        {
            $this->auth->requireLogin();
            
            $this->load->model('Manga');
            $this->load->library('Manga', 'MangaLib');
            $this->load->library('Date');

            $history = $this->manga->getUserHistory(
                $this->auth->getUserId(), 0, 100);

            $this->load->storeView('History', [
                'history'=>$history,
                'single'=>true
            ]);

            $this->load->layout('Fresh', [
                'title'=>'My Reading History'
            ]);
        }
    }

?>
