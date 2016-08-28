<?php
    namespace Page;

    class SignUp
    {
        public function index()
        {
            if ($this->input->hasPost('username'))
            {
                $this->addUser();
            }

            $this->load->storeView('SignUp');

            $this->load->layout('Fresh');
        }

        public function addUser()
        {
            $username = $this->input->post('username');
            $password = $this->input->post('password');
            $rpassword = $this->input->post('rpassword');

            $msg = $this->load->library('Message');
            $valid = $this->load->library('Validation');

            if ($password !== $rpassword)
            {
                $msg->error('Your retype password is not match with the password you entered.');
            }

            if (!$valid->username($username))
            {
                $msg->error('Your username is not valid, only use alphabet (a-z), number (0-9),'.
                    ' and symbol (_.-). The character length must between 5-16 characters');
            }

            if (!$valid->password($password))
            {
                $msg->error('Your password is not valid, only use alphabet (a-z), number (0-9),'.
                    ' and symbol (@#!$*&~;:,?_.-). The character length must between 5-16 characters');
            }

            $msg->success('Your account has been created successfully.'.
                ' Enter with your username and password', true);
            $this->router->redirect('user/signin');
        }
    }

?>
