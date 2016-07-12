<?php

    /**
     * Config Class
     *
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
            $fp = false;

            if (!file_exists(APP_PATH . 'config/'.$name.'.php'))
            {
                return false;
            }

            $config = include (APP_PATH . 'config/'.$name.'.php');
            $this->cache[$name] = $config;
            return $config;
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
