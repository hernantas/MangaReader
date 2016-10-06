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
            if (!$this->isExists($name))
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
            if (!$this->isInfoExists($name))
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
         * Check if config exists or not
         *
         * @param  string $name Config name
         *
         * @return bool         True if config is exists, false otherwise.
         */
        public function isExists($name)
        {
            return file_exists(APP_PATH . 'config/'.$name.'.php');
        }

        /**
         * Check if config exists or not
         *
         * @param  string $name Config name
         *
         * @return bool         True if config is exists, false otherwise.
         */
        public function isInfoExists($name)
        {
            return file_exists(APP_PATH . 'info/'.$name.'.php');
        }

        /**
         * Remove configuration if exists. This can't be undone
         *
         * @param  string $name Config name
         */
        public function remove($name)
        {
            if ($this->isExists($name))
            {
                unlink(APP_PATH . 'config/'.$name.'.php');
            }
        }

        /**
         * Remove configuration info if exists. This can't be undone
         *
         * @param  string $name Config name
         */
        public function removeInfo($name)
        {
            if ($this->isInfoExists($name))
            {
                unlink(APP_PATH . 'info/'.$name.'.php');
            }
        }

        /**
         * Used for setting default value of configuration.
         *
         * @param  string $name   Configuration name
         * @param  array $config  Default configuration data
         */
        public function setDefault($name, $config)
        {
            $cfg = $this->load($name);
            $changed = false;
            foreach ($config as $key=>$val)
            {
                if (!isset($cfg[$key]))
                {
                    $cfg[$key]=$val;
                    $changed = true;
                }
            }
            if ($changed) $this->save($name, $cfg);
        }

        /**
         * Used for setting default value of configuration info.
         *
         * @param  string $name   Configuration info name
         * @param  array $config  Default configuration data
         */
        public function setDefaultInfo($name, $config)
        {
            $cfg = $this->loadInfo($name);
            $changed = false;
            foreach ($config as $key=>$val)
            {
                if (!isset($cfg[$key]))
                {
                    $cfg[$key]=$val;
                    $changed = true;
                }
            }
            if ($changed) $this->saveInfo($name, $cfg);
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
