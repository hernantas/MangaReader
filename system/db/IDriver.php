<?php

    interface IDriver
    {
        public function connect($host, $user, $password);
        public function database($name);
        public function query($sql);
        public function bind($sql, $data=[]);
        public function lastError();
    }

?>
