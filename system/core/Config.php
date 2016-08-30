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
            $fp = fopen(APP_PATH . 'config/'.$name.'.php', "w");
            fwrite($fp, '<?php' . PHP_EOL);
            fwrite($fp, "\t// Config Generated At: " . date('d-M-Y H:i:s') . PHP_EOL . PHP_EOL);
            fwrite($fp, "\treturn ");
            $this->writeToConfig($fp, $config);
            fwrite($fp, PHP_EOL . '?>');
            fclose($fp);

            $this->cache[$name] = $config;
        }

        /**
         * Write Array to the config file.
         *
         * @param  object $handler File Handler
         * @param  array  $arr     Array to be written to config file
         * @param  int    $offset  Tab offset
         */
        private function writeToConfig($handler, $arr, $offset=1)
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
                    $this->writeToConfig($handler, $val, $offset+1);
                }
                else
                {
                    fwrite($handler, '"' . $val . '"');
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
