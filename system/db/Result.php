<?php
    namespace DB;

    class Result
    {
        private $sql;
        private $data;

        private $pos = 0;

        public function __construct($sql, $data)
        {
            $this->sql = $sql;
            $this->data = $data;
        }

        public function syntax()
        {
            return $this->sql;
        }

        public function row()
        {
            return isset($this->data[$this->pos]) ? $this->data[$this->pos++] : false;
        }
    }

?>
