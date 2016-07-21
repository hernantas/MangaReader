<?php
    namespace DB;

    include (SYSTEM_PATH . 'db/IDriver.php');

    class DB
    {
        private $driver;

        public function __construct()
        {
            $config =& loadClass('Config', 'Core');
            $cfg = $config->load('DB');

            if ($cfg !== false)
            {
                $this->selectDriver($cfg['driver']);
                $this->connect($cfg['host'], $cfg['user'], $cfg['password']);
                $this->database($cfg['database'], false);
            }
        }

        public function selectDriver($name)
        {
            $loader =& loadClass('Loader', 'Core');
            $this->driver = $loader->dbDriver($name);
        }

        public function connect($host, $user, $password='')
        {
            $this->driver->connect($host, $user, $password);
        }

        public function database($name)
        {
            $this->driver->database($name);
        }

        public function query($sql, $data=[])
        {
            if (count($data) > 0)
            {
                return $this->driver->bind($sql, $data);
            }
            else
            {
                return $this->driver->query($sql);
            }
        }
    }

?>
