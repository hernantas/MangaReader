<?php
    namespace Page;

    class Home
    {
        public function index()
        {
            $this->load->storeView('Home',[
                'word' => 'Date Today: ' . date('d-m-y')
            ]);

            $this->load->layout('Default');
        }
    }

?>
