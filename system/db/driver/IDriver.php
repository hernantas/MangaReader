<?php
    namespace DB\Driver;

    /**
     * Interface for all database driver. Any database driver must implement
     * this class.
     *
     * @package DB\Driver
     */
    interface IDriver
    {
        /**
         * Connect to selected database host
         *
         * @param  string $host     Database host name
         * @param  string $user     Username for database host
         * @param  string $password Password for database host
         */
        public function connect($host, $user, $password);

        /**
         * Connect to database in the host.
         *
         * @param  string $name         Database name
         * @param  bool   $forceCreate  If set to true, will create database if
         * 								database is not exists
         */
        public function database($name, $forceCreate=false);

        /**
         * Perform Query based on the driver.
         *
         * @param  string $sql SQL that need to be run
         *
         * @return \DB\Result  Database query result
         */
        public function query($sql);

        /**
         * Perform database query by binding the data to query syntax.
         *
         * @param  string $sql  SQL
         * @param  array  $data Data that will be binded to query
         *
         * @return \DB\Result   Database query result
         */
        public function bind($sql, $data=[]);

        public function escape($string);
    }

?>
