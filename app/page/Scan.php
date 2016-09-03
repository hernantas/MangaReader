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
            $this->load->library('Manga');

            if ($this->manga->isScanEmpty())
            {
                $this->manga->startScan();
            }
        }

        public function status()
        {
            $result = $this->db->query('SELECT * from manga where id < ?', ['100']);

            $this->load->library('Manga');

            if (!$this->manga->isScanEmpty())
            {
                $startTime = microtime(true);
                $this->manga->flushScan();
                $duration = microtime(true) - $startTime;

                echo "{
                    \"result\": \"success\",
                    \"time\": \"$duration\"
                }";
            }
            else
            {
                echo "{'result':'done'}";
            }
        }
    }

?>
