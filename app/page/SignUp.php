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

            $this->load->library('Validation');

            if ($password !== $rpassword)
            {
                $this->message->error('Your retype password is not match with the password you entered.');
            }

            if (!$this->validation->username($username))
            {
                $this->message->error('Your username is not valid, only use alphabet (a-z), number (0-9),'.
                    ' and symbol (_.-). The character length must between 5-16 characters');
            }

            if (!$this->validation->password($password))
            {
                $this->message->error('Your password is not valid, only use alphabet (a-z), number (0-9),'.
                    ' and symbol (@#!$*&~;:,?_.-). The character length must between 5-16 characters');
            }

            if ($this->message->count('error') > 0)
            {
                return;
            }

            $create = $this->auth->addUser($username, $password);

            if ($create === true)
            {
                $this->message->success('Your account has been created successfully.'.
                ' Enter with your username and password', true);
                $this->router->redirect('user/signin');
            }
            else
            {
                $this->message->error($create);
            }

        }
    }

?>
