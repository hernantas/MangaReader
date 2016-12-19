<?php
    namespace Library;

    /**
     * Provide easy to use session mechanism.
     *
     * @package Library
     */
    class Session
    {
        /**
         * Default session expire is 1 day
         *
         * @var int
         */
        private $expire = 86400;

        /**
         * Session that have long Expiration
         *
         * @var int
         */
        private $longExpire = 31536000;

        /**
         * Cache flash session.
         *
         * @var array
         */
        private $flash = array();

        public function __construct()
        {
            foreach ($_COOKIE as $key=>$val)
            {
                if (strpos($key, 'flash_') === 0)
                {
                    $name = substr($key, 6);
                    $this->flash[$name] = $val;
                    $this->remove($key);
                }
            }
        }

        /**
         * Set session
         *
         * @param string $name    Session name
         * @param string $value   Session value
         * @param bool   $persist Set if session is persistent (have long expire time), or not.
         */
        public function set($name, $value, $persist=false)
        {
            setcookie($name, $value, time()+($persist?$this->longExpire:$this->expire),
                page()->uri->subdir());
        }

        /**
         * Get session
         *
         * @param  string $name    Session name
         * @param  string $default Default value if session don't exists
         *
         * @return string          Session value
         */
        public function get($name, $default='')
        {
            return isset($_COOKIE[$name]) ? $_COOKIE[$name] : $default;
        }

        /**
         * Remove session
         *
         * @param  string $name Session name
         */
        public function remove($name)
        {
            setcookie($name, '', -1, page()->uri->subdir());
        }

        /**
         * Set flash session. Flash session is session that exists only until the
         * next page is loaded. Session class must be loaded on each page for
         * this to work.
         *
         * @param string $name  Flash session name
         * @param string $value Flash session value
         */
        public function setFlash($name, $value)
        {
            $this->set('flash_'.$name, $value);
            $this->flash[$name] = $value;
        }

        /**
         * Get Flash session.
         *
         * @param  string $name    Flash session name
         * @param  string $default Default value
         *
         * @return string          Flash session value
         *
         * @see \Library\Session::setFlash
         */
        public function getFlash($name, $default='')
        {
            return isset($this->flash[$name]) ? $this->flash[$name] : $default;
        }

        /**
         * Get all flash session
         *
         * @return array All flash session
         */
        public function getAllFlash()
        {
            return $this->flash;
        }
    }

?>
