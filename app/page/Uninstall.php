<?php
    namespace Page;

    class Uninstall
    {
        public function index()
        {
            $this->load->storeView('Uninstall');

            $this->load->layout('Fresh', [
                'title'=>'Uninstall'
            ]);
        }

        public function warning()
        {
            if ($this->input->hasPost())
            {
                $this->auth->removeSession();
                $cfg = $this->config->loadInfo('DB');

                $this->db->query('DROP DATABASE '. $cfg['database']);
                $this->config->removeInfo('DB');
                $this->config->removeInfo('Manga');
                $this->config->removeInfo('Setup');

                $this->router->redirect();
            }

            $this->message->warning("Be careful since it can't be reverted");
            $this->load->storeView('Uninstall', [
                'alternative'=>true
            ]);
            $this->load->layout('Fresh', [
                'title'=>'Uninstall'
            ]);
        }
    }
?>
