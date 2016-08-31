<?php
    namespace Page;

    class Install
    {
        public function index()
        {
            $this->setup->installOnly();

            if ($this->input->hasPost())
            {
                if ($this->input->hasPost('agree'))
                {
                    $this->setup->finish();
                    $this->router->redirect();
                }
                else
                {
                    $this->message->error("If you want to use the website, you must agree
                        to the license.");
                }
            }

            $this->load->storeView('InstallWelcome');

            $this->load->layout('Fresh', [
                'simpleMode'=>true,
                'title'=>'Welcome'
            ]);
        }
    }
?>
