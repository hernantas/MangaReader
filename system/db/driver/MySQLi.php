<?php
    namespace DB\Driver;

    /**
     * Database driver for MYSQL using MYSQLI rather than default and deprecated
     * MYSQL which is recommended by most PHP developer.
     *
     * @package DB\Driver
     */
    class MySQLi implements IDriver
    {
        private $pdo = null;

        public function connect($host, $username, $password)
        {
            try
            {
                $this->pdo = new \PDO("mysql:host=$host;", $username, $password, [ \PDO::ATTR_ERRMODE => \PDO::ERRMODE_SILENT]);
            }
            catch (\PDOException $e)
            {
                logError($e->getMessage());
                return false;
            }

            logInfo("Successfully connect to database host '$host'");
            return true;
        }

        public function database($name, $forceCreate=false)
        {
            if ($forceCreate)
            {
                $this->pdo->query("CREATE DATABASE IF NOT EXISTS $name");
            }

            $affected = $this->pdo->exec("USE $name");
            if ($affected === false)
            {
                $err = $this->pdo->errorInfo();

                if ($err[0] !== '00000' || $err[0] !== '01000')
                {
                    logError("Unknown database '$name'");
                    return false;
                }
            }
            return true;
        }

        public function query($sql)
        {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            $result = new \DB\Result($sql, $this->fetchAll($stmt), $stmt->errorInfo()[2]);
            return $result;
        }

        public function bind($sql, $data=[])
        {
            $stmt = $this->pdo->prepare($sql);
            foreach ($data as $key=>$val)
            {
                $dataType = $this->getValueType($val);

                if ($dataType == \PDO::PARAM_STR)
                {
                    $stmt->bindParam(":$key", $val, $dataType, strlen($val));
                }
                else
                {
                    $stmt->bindParam(":$key", $val, $dataType);
                }

            }
            $stmt->execute();

            $result = new \DB\Result($sql, $this->fetchAll($stmt), $stmt->errorInfo()[2]);
            return $result;
        }

        public function bindAuto($sql, $data=array())
        {
            $stmt = $this->pdo->prepare($sql);
            $count = 1;
            foreach ($data as $key=>$val)
            {
                $dataType = $this->getValueType($val);

                if ($dataType == \PDO::PARAM_STR)
                {
                    $stmt->bindParam($count, $val, $dataType, strlen($val));
                }
                else
                {
                    $stmt->bindParam($count, $val, $dataType);
                }

                $count++;

            }
            $stmt->execute();

            $result = new \DB\Result($sql, $this->fetchAll($stmt), $stmt->errorInfo()[2]);
            return $result;
        }

        private function fetchAll($stmt)
        {
            $data = array();
            while ($row = $stmt->fetch(\PDO::FETCH_OBJ))
            {
                $data[] = $row;
            }
            return $data;
        }

        private function getValueType($val)
        {
            if (is_bool($val)) return \PDO::PARAM_BOOL;
            if (is_null($val)) return \PDO::PARAM_NULL;
            if (is_int($val)) return \PDO::PARAM_INT;
            return \PDO::PARAM_STR;
        }
    }

?>
