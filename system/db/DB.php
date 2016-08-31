<?php
    namespace DB;

    include (SYSTEM_PATH . 'db/Result.php');
    include (SYSTEM_PATH . 'db/builder/Builder.php');
    include (SYSTEM_PATH . 'db/driver/IDriver.php');
    include (SYSTEM_PATH . 'db/Schema.php');
    include (SYSTEM_PATH . 'db/schema/ISchema.php');

    class DB
    {
        private $driver;

        private $builder;

        public $schema;

        private $dbError = '';

        public function __construct()
        {
            $config =& loadClass('Config', 'Core');
            $cfg = $config->load('DB');

            if ($cfg !== false)
            {
                // Load driver
                $this->load('Driver', $cfg['driver']);

                // Load builder if available
                $this->load('Builder', $cfg['driver']);

                if ($this->builder !== null)
                {
                    $this->builder->db =& $this;
                }

                $this->schema = new \DB\Schema($cfg['driver'], $this);

                $this->connect($cfg['host'], $cfg['user'], $cfg['password']);
                $this->database($cfg['database']);
            }
        }

        /**
         * Load Sub DB class
         *
         * @param  string $type Sub DB Type
         * @param  string $name Sub DB Name
         */
        private function load($type, $name)
        {
            $vendor =& loadClass('Vendor', 'Core');
            $list = $vendor->find('DB/'.$type, $name);

            if (!is_array($list))
            {
                include ($list . '/DB/'.$type.'/'.$name.'.php');
                $class = '\\DB\\'.$type.'\\'.$name;

                if (class_exists($class))
                {
                    $type = strtolower($type);
                    $this->$type = new $class();
                }
            }
        }

        /**
         * Connect to database host
         *
         * @param  string $host     Database host name
         * @param  string $user     Username for database host
         * @param  string $password Password for database host
         */
        public function connect($host, $user, $password='')
        {
            $con = $this->driver->connect($host, $user, $password);

            if ($con !== true)
            {
                logError($con, 'DB');
            }
            else
            {
                logInfo("Successfully connect to database host '$host'", 'DB');

            }
        }

        /**
         * Select database inside the database host
         *
         * @param  string $name   Database name
         */
        public function database($name)
        {
            $db = $this->driver->database($name);

            if ($db !== true)
            {
                $this->dbError = $db;
                logError($db, 'DB');
            }
        }

        /**
         * Get database error when selecting a database
         *
         * @return string Database Select error
         */
        public function databaseError()
        {
            return $this->dbError;
        }

        /**
         * Perform query syntax. Support data binding and should be used if passing
         * user inputed data to database.
         *
         * @param  string $sql  SQL syntax
         * @param  array  $data Data that need to be binded to performed SQL
         *
         * @return \DB\Result     Database result
         */
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

        public function table($tables)
        {
            if ($this->builder === null)
            {
                return null;
            }

            $this->builder->reset();
            return $this->builder->table($tables);
        }
    }

?>
