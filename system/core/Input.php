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
         * Check if input $_GET is exists
         *
         * @param  string $name Input name
         *
         * @return bool         True if input exists, false otherwise.
         */
        public function hasGet($name)
        {
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
         * Check if input $_POST is exists
         *
         * @param  string $name Input name
         *
         * @return bool         True if input exists, false otherwise.
         */
        public function hasPost($name)
        {
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
    }

?>
