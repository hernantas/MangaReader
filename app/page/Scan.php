<?php
    namespace Page;

    class Scan
    {
        private $path = '';

        public function index()
        {
            $this->load->library('Manga');

            $this->load->storeView('Scan',[
                'scanEmpty'=>$this->manga->isScanEmpty()
            ]);

            $this->load->layout('Fresh', [
                'title'=>'Scan Directory',
                'additionalJs'=>['scan']
            ]);
        }

        public function start()
        {
            // $this->db->query("TRUNCATE TABLE `manga`");
            // $this->db->query("TRUNCATE TABLE `manga_chapter`");
            // $this->db->query("TRUNCATE TABLE `manga_image`");

            $this->load->library('Manga');

            if ($this->manga->isScanEmpty())
            {
                $this->manga->startScan();
            }

            $this->router->redirect('admin/scan');
        }

        public function status()
        {
            $this->load->library('Manga');

            if (!$this->manga->isScanEmpty())
            {
                $startTime = microtime(true);
                $this->manga->flushScan();
                $duration = microtime(true) - $startTime;

                $warningI = 0;

                echo "{";
                echo "\"result\": \"success\",";
                echo "\"warning\": [";
                if (count($this->manga->getScanWarning()) > 0)
                {
                    $first = true;
                    foreach ($this->manga->getScanWarning() as $warning)
                    {
                        if ($first)
                        {
                            echo "\"$warning\"";
                            $first = false;
                        }
                        else
                        {
                            echo ", \"$warning\"";
                        }
                    }
                }
                echo "],";
                echo "\"time\": \"$duration\"";
                echo "}";
            }
            else
            {
                echo "{\"result\": \"done\"}";
            }
        }
    }

?>
