<?php
    namespace Page;

    class Install
    {
        public function index()
        {
            $this->load->storeView('Install');

            $this->load->layout('Fresh', [
                'simpleMode'=>true
            ]);
        }
    }
?>
