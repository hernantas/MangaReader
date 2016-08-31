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
        private $mysqli;

        public function connect($host, $user, $password)
        {
            @$this->mysqli = new \mysqli($host, $user, $password);

            if (@$this->mysqli->errno > 0)
            {
                return ($this->mysqli->error);
            }
            elseif (!@$this->mysqli->ping())
            {
                return "Can't connect to database host '$host'";
            }

            return true;
        }

        public function database($name, $forceCreate=false)
        {
            $this->mysqli->select_db($name);

            if ($this->mysqli->errno > 0)
            {
                if ($forceCreate)
                {
                    $res = $this->mysqli->query("CREATE DATABASE $name");

                    if ($this->mysqli->errno > 0)
                    {
                        return ($this->mysqli->error);
                    }
                    $this->mysqli->select_db($name);
                }
                else
                {
                    return ($this->mysqli->error);
                }
            }
            return true;
        }

        public function query($sql)
        {
            $res = $this->mysqli->query($sql);
            $result = null;

            if ($this->mysqli->errno > 0)
            {
                $result = new \DB\Result($sql, array(), $this->mysqli->error);
            }
            else if ($res === true)
            {
                $result = new \DB\Result($sql, []);
            }
            else
            {
                $data = array();
                while ($row = $res->fetch_object())
                {
                    $data[] = $row;
                }
                $result = new \DB\Result($sql, $data);

                $res->free();
            }

            return $result;
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

            return $this->query($sql);
        }
    }

?>
