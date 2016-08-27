<?php
    namespace Library;

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

        public function set($name, $value, $persist=false)
        {
            setcookie($name, $value, time()+($persist?$this->longExpire:$this->expire),
                page()->uri->subdir());
        }

        public function remove($name)
        {
            setcookie($name, '', -1, page()->uri->subdir());
        }
    }

?>
