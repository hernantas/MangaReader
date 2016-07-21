<?php

    class DB_MYSQLI implements IDriver
    {
        private $mysqli;

        public function connect($host, $user, $password)
        {
            $this->mysqli = new mysqli($host, $user, $password);
            return !((bool)$this->mysqli->connect_error);
        }

        public function database($name)
        {
            $this->mysqli->select_db($name);
            return !((bool)$this->mysqli->connect_error);
        }

        public function query($sql)
        {

        }

        public function bind($sql, $data=[])
        {
            $length = count($data);
            for ($i=0;$i<$length;$i++)
            {
                $data[$i] = mysqli_escape_string($data[$i]);
            }

            $subs = 0;
            while (($pos = strpos($sql, '?')) !== false)
            {
                $sql = substr_replace($sql, $data[$subs], $pos);
                $subs++;
            }

            return $sql;
        }

        public function lastError()
        {
            return '';
        }
    }

?>
