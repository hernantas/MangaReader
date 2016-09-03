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
            $this->fixBindData($data);

            $prep = $this->mysqli->prepare($sql);
            $ref = new \ReflectionClass('mysqli_stmt');
            $method = $ref->getMethod("bind_param");
            $method->invokeArgs($prep,$data);
            $prep->execute();

            $newData = array();
            if (($metadata = $prep->result_metadata()) !== null)
            {
                // Have data
                $fields = array();
                $values = array();

                while ($field = mysqli_fetch_field($metadata))
                {
                    $fields[] = $field->name;
                    $values[] = null;
                }

                $fieldCount = count($fields);
                $method = $ref->getMethod('bind_result');
                $method->invokeArgs($prep, $values);

                while ($prep->fetch())
                {
                    $row = new \stdClass();
                    for ($i = 0; $i < $fieldCount; $i++)
                    {
                        $row->$fields[$i] = $values[$i];
                    }
                    $newData[] = $row;
                }
            }

            $prep->close();
            return new \DB\Result($sql, $newData);
        }

        private function fixBindData(&$data)
        {
            $type = '';
            $count = count($data);
            for($i = 0; $i < $count; $i++)
            {
                if (is_int($data[$i])) $type .= 'i';
                elseif (is_double($data[$i]) || is_float($data[$i])) $type .= 'd';
                else $type .= 's';

                $data[$i] = & $data[$i];
            }
            array_unshift($data, $type);
        }

        public function escape($string)
        {
            return $this->mysqli->escape_string($string);
        }
    }

?>
