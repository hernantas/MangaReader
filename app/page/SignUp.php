<?php
    namespace Page;

    class SignUp
    {
        public function index()
        {
            $this->load->storeView('SignUp');

            $this->load->layout('Fresh');
        }
    }

?>
