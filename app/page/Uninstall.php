<?php
    namespace Page;

    class Uninstall
    {
        public function index()
        {
            $cfg = $this->config->loadInfo('DB');

            $this->db->query('DROP DATABASE '. $cfg['database']);
            $this->config->removeInfo('DB');
            $this->config->removeInfo('Manga');
            $this->config->removeInfo('Setup');


            $this->router->redirect();
        }
    }
?>
