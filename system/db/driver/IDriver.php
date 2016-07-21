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
         * @param  string $name Database name
         */
        public function database($name);

        /**
         * Perform Query based on the driver.
         *
         * @param  [type] $sql [description]
         *
         * @return [type]      [description]
         */
        public function query($sql);
        public function bind($sql, $data=[]);
        public function lastError();
    }

?>
