<?php
    namespace Core;

    /**
     * Provide method to read, write and cache configuration file.
     *
     * @package Core
     */
    class Config
    {
        /**
         * Cache loaded configuration
         *
         * @var array
         */
        private $cache = array();

        /**
         * Cache config info
         *
         * @var array
         */
        private $info = array();

        /**
         * Load Configuration and return it's configuration as an array.
         *
         * @param  string $name Config name
         *
         * @return bool|array   FALSE if failed to load configuration or configuration
         *                      as array.
         */
        public function load($name)
        {
            if (!file_exists(APP_PATH . 'config/'.$name.'.php'))
            {
                return false;
            }

            $config = include (APP_PATH . 'config/'.$name.'.php');
            $this->cache[$name] = $config;
            return $config;
        }

        /**
         * Safe Array Configuration to a config file.
         *
         * @param  string $name   Config name
         * @param  array  $config Config array
         */
        public function save($name, $config)
        {
            $this->write($name, $config);
        }

        /**
         * Load configuration info. Configuration info is config that shouldn't
         * be modified by user and only be used for code information
         *
         * @param  string $name Config info name
         *
         * @return bool|array   FALSE if failed to load configuration or configuration
         *                      as array.
         */
        public function loadInfo($name)
        {
            if (!file_exists(APP_PATH . 'info/'.$name.'.php'))
            {
                return false;
            }

            $config = include (APP_PATH . 'info/'.$name.'.php');
            $this->info[$name] = $config;
            return $config;
        }

        /**
         * Safe Array Configuration info to a config file.
         *
         * @param  string $name   Config name
         * @param  array  $config Config array
         */
        public function saveInfo($name, $config)
        {
            $this->write($name, $config, true);
        }

        /**
         * Actual method to write config to the file
         *
         * @param  string $name   Config file name
         * @param  string $config Config array
         * @param  bool   $info   Write config as info or normal config
         */
        private function write($name, $config, $info=false)
        {
            $fp = null;

            if ($info)
            {
                $fp = fopen(APP_PATH . 'info/'.$name.'.php', "w");
                $this->info[$name] = $config;
            }
            else
            {
                $fp = fopen(APP_PATH . 'config/'.$name.'.php', "w");
                $this->cache[$name] = $config;
            }

            fwrite($fp, '<?php' . PHP_EOL);
            if ($info)
            {
                fwrite($fp, "\t// This file or directory should be ignored if using version control." . PHP_EOL);
                fwrite($fp, "\t// Config Info Generated At: " . date('d-M-Y H:i:s') . PHP_EOL . PHP_EOL);
            }

            fwrite($fp, "\treturn ");
            $this->writeArray($fp, $config);
            fwrite($fp, PHP_EOL . '?>');
            fclose($fp);
        }

        /**
         * Write Array to the config file.
         *
         * @param  object $handler File Handler
         * @param  array  $arr     Array to be written to config file
         * @param  int    $offset  Tab offset
         */
        private function writeArray($handler, $arr, $offset=1)
        {
            fwrite($handler, '[');
            $first = true;
            $prevNumber = -1;
            foreach ($arr as $key=>$val)
            {
                fwrite($handler, ($first === false?',':'') . PHP_EOL .
                    str_repeat("\t", $offset+1));

                if (is_numeric($key) && $prevNumber === $key-1)
                {
                    $prevNumber = $key;
                }
                else
                {
                    fwrite($handler, '"' . $key . '" => ');
                }

                if (is_array($val))
                {
                    $this->writeArray($handler, $val, $offset+1);
                }
                else
                {
                    if (is_bool($val))
                    {
                        fwrite($handler, $val?'true':'false');
                    }
                    else
                    {
                        fwrite($handler, '"' . $val . '"');
                    }
                }
                $first = false;
            }
            fwrite($handler, PHP_EOL . str_repeat("\t", $offset) .
                ']' . ($offset==1?';':''));
        }

        /**
         * Get configuration by key in config file.
         *
         * @param  string $name    Config file name
         * @param  string $key     Config Key name
         * @param  string $default Default Value
         *
         * @return string          Config Value
         */
        public function get($name, $key, $default=false)
        {
            $config = $this->load($name);

            if ($config === false)
            {
                return $default;
            }

            return isset($config[$key]) ? $config[$key] : $default;
        }
    }

?>
