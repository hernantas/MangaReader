<?php
    namespace Page;

    class SignOut
    {
        public function index()
        {
            $this->auth->removeSession();
            $this->router->redirect();
        }
    }

?>
