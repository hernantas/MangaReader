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
        private $err = '';

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
            $this->err = $error;

            if ($error === '')
            {
                logInfo('Execute Query: "'.$sql.'" with success, returning '.$this->dataLength.' results.');
            }
            else
            {
                logWarning('Execute Query: "'.$sql.'" with error.');
                logWarning('Query Error: "'.$error.'"');
            }
        }

        public function isError()
        {
            return ($this->err !== '');
        }

        public function error()
        {
            return $this->err;
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
         * Check if result data is empty or not
         *
         * @return bool True if data is empty, false otherwise
         */
        public function isEmpty()
        {
            return $this->dataLength === 0;
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

        public function position()
        {
            return $this->pos;
        }

        /**
         * Get query result per row, or get single string at current row if
         * column is used
         *
         * @param  string       $column     Column name
         * @param  string       $default    Default value if column is not exists
         * @return array|string             Result row or single string if column is used
         */
        public function row($column='', $default='')
        {
            return $this->item($this->pos++, $column='', $default='');
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
         * Get first row from query result data. If column is used, will return
         * single string instead
         *
         * @param  string       $column     Column name
         * @param  string       $default    Default value if column is not exists
         * @return array|string             First row on Query result or single string if
         *                                  column is used
         */
        public function first($column='', $default='')
        {
            return $this->item(0, $column, $default);
        }

        /**
         * Get query result at specific row
         *
         * @param  int    $pos      Row number, start with 0
         * @param  string $column   Column name
         * @param  string $default  Default value if column is not exists
         *
         * @return array            Row on query result
         */
        public function item($pos, $column='', $default='')
        {
            $row = isset($this->data[$pos]) ? $this->data[$pos] : false;
            if ($column === '')
            {
                return $row;
            }
            return property_exists($row, $column) ? $row->$column : $default;
        }

        /**
         * Get last row from query result
         *
         * @param  int    $pos      Row number, start with 0
         * @param  string $column   Column name
         *
         * @return array|string     Last row on Query result or single string if
         *                          column is used
         */
        public function last($column='', $default='')
        {
            return $this->item($this->dataLength-1, $column, $default);
        }
    }

?>
