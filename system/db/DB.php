<?php

    include (SYSTEM_PATH . 'db/IDriver.php');

    class DB
    {
        private $dbDriver;

        public function __construct()
        {
            $config =& loadClass('Config', 'Core');
            $cfg = $config->load('DB');

            if ($cfg !== false)
            {
                $this->driver($cfg['driver']);
                $this->connect($cfg['host'], $cfg['user'], $cfg['password']);
                $this->database($cfg['database'], false);
            }
        }

        public function driver($name)
        {
            $loader =& loadClass('Loader', 'Core');
            $this->dbDriver = $loader->dbDriver($name);
        }

        public function connect($host, $user, $password='')
        {
            $this->dbDriver->connect($host, $user, $password);
        }

        public function database($name, $create=false)
        {
            $this->dbDriver->database($name);
        }

        public function query($sql, $data=[])
        {

        }
    }

?>
