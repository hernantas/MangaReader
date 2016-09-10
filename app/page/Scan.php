<?php
    namespace Page;

    class Scan
    {
        private $path = '';

        public function index()
        {
            $this->auth->requireLogin();

            if ($this->auth->getUserOption('privilege') != 'admin')
            {
                $this->router->redirect('');
            }

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
            $this->auth->requireLogin();

            $this->load->library('Scan');

            if ($this->scan->isScanEmpty() &&
                $this->auth->getUserOption('privilege') == 'admin')
            {
                $this->scan->startScan();
            }

            $this->router->redirect('admin/scan');
        }

        public function status()
        {
            $this->load->library('Scan');
            $status = "failure";

            $startTime = microtime(true);
            if (!$this->scan->isScanEmpty())
            {
                $this->scan->flushScan();
                $status = "success";
            }
            else
            {
                if ($this->scan->cleanUp())
                {
                    $status = "success";
                }
                else
                {
                    $status = "done";
                }
            }
            $duration = microtime(true) - $startTime;

            $warningI = 0;

            echo "{";
            echo "\"result\": \"$status\",";
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

        private function cleanup()
        {

        }
    }

?>
