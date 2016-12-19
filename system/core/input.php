<?php
    namespace Core;

    /**
     * Input class for getting user input safest way.
     *
     * @package Core;
     */
    class Input
    {
        /**
         * Check if input $_GET is exists. If $name is empty, check if user send
         * $_GET input instead
         *
         * @param  string $name Input name
         *
         * @return bool         True if input exists, false otherwise.
         */
        public function hasGet($name='')
        {
            if ($name === '')
            {
                return !empty($_GET);
            }
            return isset($_GET[$name]);
        }

        /**
         * Get input that normally use $_GET
         *
         * @param  string $name    Input name
         * @param  string $default Default value if not exists
         *
         * @return string          Input value
         */
        public function get($name, $default='')
        {
            return $this->hasGet($name) ? $_GET[$name] : $default;
        }

        /**
         * Check if input $_POST is exists. If $name is empty, check if user send
         * $_POST input instead
         *
         * @param  string $name Input name
         *
         * @return bool         True if input exists, false otherwise.
         */
        public function hasPost($name='')
        {
            if ($name === '')
            {
                return !empty($_POST);
            }

            return isset($_POST[$name]);
        }

        /**
         * Get input that normally use $_POST
         *
         * @param  string $name    Input name
         * @param  string $default Default value if not exists
         *
         * @return string          Input value
         */
        public function post($name, $default='')
        {
            return $this->hasPost($name) ? $_POST[$name] : $default;
        }

        /**
         * Check if input $_REQUEST is exists. If $name is empty, check if user send
         * $_REQUEST input instead
         *
         * @param  string $name Input name
         *
         * @return bool         True if input exists, false otherwise.
         */
        public function hasRequest($name='')
        {
            if ($name === '')
            {
                return !empty($_REQUEST);
            }
            return isset($_REQUEST[$name]);
        }

        /**
         * Get input that normally use $_REQUEST
         *
         * @param  string $name    Input name
         * @param  string $default Default value if not exists
         *
         * @return string          Input value
         */
        public function request($name, $default='')
        {
            return $this->hasRequest($name) ? $_REQUEST[$name] : $default;
        }
    }

?>
