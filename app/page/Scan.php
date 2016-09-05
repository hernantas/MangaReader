<?php
    namespace Page;

    class Scan
    {
        private $path = '';

        public function index()
        {
            $this->load->library('Scan');

            $this->load->storeView('Scan',[
                'scanEmpty'=>$this->scan->isScanEmpty()
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

            $this->load->library('Scan');

            if ($this->scan->isScanEmpty())
            {
                $this->scan->startScan();
            }

            $this->router->redirect('admin/scan');
        }

        public function status()
        {
            $this->load->library('Scan');

            if (!$this->scan->isScanEmpty())
            {
                $startTime = microtime(true);
                $this->scan->flushScan();
                $duration = microtime(true) - $startTime;

                $warningI = 0;

                echo "{";
                echo "\"result\": \"success\",";
                echo "\"warning\": [";
                if (count($this->scan->getScanWarning()) > 0)
                {
                    $first = true;
                    foreach ($this->scan->getScanWarning() as $warning)
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
