<?php
    namespace DB;

    class Result
    {
        private $sql;
        private $data;

        public function __construct($sql, $data)
        {
            $this->sql = $sql;
            $this->data = $data;
        }

        public function syntax()
        {
            return $this->sql;
        }
    }

?>
