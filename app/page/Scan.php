<?php
    namespace Page;

    class Scan
    {
        private $path = '';

        public function index()
        {
            $this->loadConfig();

            $this->load->storeView('Scan',[
                'path'=>$this->path,
                'mangaList'=>$this->scanDir()
            ]);
            $this->load->layout('Fresh', ['title'=>'Scan Directory']);
        }

        private function loadConfig()
        {
            $cfg = $this->config->loadInfo('Manga');

            if ($cfg === false)
            {
                logError('No Manga Configuration found.');
                exit();
            }

            $this->path = $cfg['path'];
        }

        private function scanDir()
        {
            $list = scandir($this->path);
            $array = array();
            $i = 0;
            foreach ($list as $manga)
            {
                if($manga !== '.' && $manga !== '..' && $manga !== '')
                {
                    $m['num'] = $i;
                    $m['name'] = $manga;
                    $array[] = $m;
                    $i++;
                }
            }
            return $array;
        }
    }

?>
