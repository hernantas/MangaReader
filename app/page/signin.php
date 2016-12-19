<?php
    namespace Page;

    class SignIn
    {
        public function index()
        {
            $this->auth->requireNoLogin();
            
            if ($this->input->hasPost())
            {
                $this->login();
            }

            $this->load->storeView('SignIn');

            $this->load->layout('Fresh');
        }

        private function login()
        {
            $username = $this->input->post('username');
            $password = $this->input->post('password');
            $keep = $this->input->hasPost('keep');

            if ($this->auth->createSession($username, $password, $keep))
            {
                $this->router->redirect('');
            }
            else
            {
                $this->message->error('Username or password is not valid, please'.
                    ' check your username and password again.');
            }
        }
    }

?>
