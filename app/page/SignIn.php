<?php
    namespace Page;

    class SignIn
    {
        public function index()
        {
            $this->load->storeView('SignIn');

            $this->load->layout('Fresh');
        }
    }

?>
