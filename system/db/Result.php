<?php
    namespace DB;

    /**
     * Database result when Query is performed regardless query is successful or
     * failed.
     *
     * @package DB
     */
    class Result
    {
        /**
         * SQL Syntax
         *
         * @var string
         */
        private $sql;

        /**
         * Query result data
         *
         * @var array
         */
        private $data;

        /**
         * Query result data length
         *
         * @var int
         */
        private $dataLength = 0;

        /**
         * Query result error
         *
         * @var string
         */
        private $error = '';

        /**
         * Result pointer
         *
         * @var int
         */
        private $pos = 0;

        public function __construct($sql, $data, $error='')
        {
            $this->sql = $sql;
            $this->data = $data;
            $this->dataLength = count($data);
            $this->error = $error;
        }

        public function isError()
        {
            return ($this->error !== '');
        }

        /**
         * Get syntax used when performing the query
         *
         * @return string SQL Syntax
         */
        public function syntax()
        {
            return $this->sql;
        }

        /**
         * Change result pointer position
         *
         * @param  string $pos Pointer position
         */
        public function seek($pos)
        {
            $this->pos = ($pos > $this->dataLength) ? $this->dataLength-1: $pos;
        }

        /**
         * Reset result pointer to first row element.
         */
        public function reset()
        {
            $this->pos = 0;
        }

        /**
         * Get query result per row
         *
         * @return array Result row
         */
        public function row()
        {
            return $this->at($this->pos++);
        }

        /**
         * Get query result count
         *
         * @return int Query result count
         */
        public function count()
        {
            return $this->dataLength;
        }

        /**
         * Get first row from query result data
         *
         * @return array First row on Query result
         */
        public function first()
        {
            return $this->at(0);
        }

        /**
         * Get query result at specific row
         *
         * @param  int $pos Row number, start with 0
         *
         * @return array    Row on query result
         */
        public function at($pos)
        {
            return isset($this->data[$pos]) ? $this->data[$pos] : false;
        }

        /**
         * Get last row from query result
         *
         * @return array Last Row on query result
         */
        public function last()
        {
            return $this->at($this->dataLength-1);
        }
    }

?>
