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

        private $result = array();

        public function __construct()
        {
            $config =& loadClass('Config', 'Core');
            $cfg = $config->load('DB');

            if ($cfg === false)
            {
                $cfg = $config->loadInfo('DB');
            }

            if ($cfg !== false)
            {
                if (isset($cfg['driver'])) $this->selectDriver($cfg['driver']);
                $this->connect($cfg['host'], $cfg['username'], $cfg['password']);
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
            $vend = $vendor->find('DB/'.$type, $name);

            if ($vend !== false)
            {
                include ($vend . '/DB/'.$type.'/'.$name.'.php');
                $class = '\\DB\\'.$type.'\\'.$name;

                if (class_exists($class))
                {
                    $type = strtolower($type);
                    $this->$type = new $class();
                }
            }
        }

        public function selectDriver($name)
        {
            // Load driver
            $this->load('Driver', $name);

            // Load builder if available
            $this->load('Builder', $name);

            if ($this->builder !== null)
            {
                $this->builder->db =& $this;
            }

            $this->schema = new \DB\Schema($name, $this);
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
            if ($this->driver === null)
            {
                logError('No driver is used at the moment.');
                return false;
            }

            $con = $this->driver->connect($host, $user, $password);

            if ($con !== true)
            {
                logError($con);
                return false;
            }
            else
            {
                logInfo("Successfully connect to database host '$host'");
                return true;
            }
        }

        /**
         * Select database inside the database host
         *
         * @param  string $name         Database name
         * @param  bool   $forceCreate  If set to true, will create database if
         * 								database is not exists
         */
        public function database($name, $forceCreate=false)
        {
            if ($this->driver === null)
            {
                logError('No driver is used at the moment.');
                return false;
            }
            
            $db = $this->driver->database($name, $forceCreate);

            if ($db !== true)
            {
                $this->dbError = $db;
                logError($db);
                return false;
            }

            return true;
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
                $this->result[$sql] = $this->driver->bind($sql, $data);
            }
            else
            {
                $this->result[$sql] = $this->driver->query($sql);
            }

            if ($this->result[$sql]->isError())
            {
                logError("Execute query '".$this->result[$sql]->error()."' with error");
            }
            else
            {
                logInfo("Execute query '$this->result[$sql]->error()' successfully");
            }

            return $this->result[$sql];
        }

        public function escape($string)
        {
            return $this->driver->escape($string);
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
