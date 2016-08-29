<?php
    namespace DB\Schema;

    class MySQLi implements ISchema
    {
        private $fields = array();
        private $cons = array();

        public function reset()
        {
            $this->fields = array();
            $this->cons = array();
        }

        public function addField($field)
        {
            $this->fields[] = "`$field->name` $field->type".($field->null?'':' NOT NULL ').
                ($field->autoinc?' AUTO_INCREMENT ':'');
        }

        public function addConstraint($cons)
        {
            $k = '';
            switch ($cons->type) {
                case \DB\SchemaConstraint::TYPE_PRIMARY:
                    $k = 'PRIMARY KEY';
                    break;
                case \DB\SchemaConstraint::TYPE_UNIQUE:
                    $k = 'UNIQUE KEY';
                    break;
                case \DB\SchemaConstraint::TYPE_INDEX:
                    $k = 'INDEX KEY';
                    break;
            }

            $this->cons[] = "$k (`$cons->name`)";
        }

        public function hasTable($name)
        {
            $result = $this->db->query("SHOW TABLES LIKE '$name'");
            return (!$result->isError() && $result->count() > 0);
        }

        public function create($table)
        {
            $fs = '';

            foreach ($this->fields as $field)
            {
                if ($fs === '')
                {
                    $fs = $field;
                }
                else
                {
                    $fs .= ', '. $field;
                }
            }

            foreach ($this->cons as $cons)
            {
                $fs .= ', '. $cons;
            }

            $sql = "CREATE TABLE `$table` ($fs)";
            $result = $this->db->query($sql);

            return !($result->isError());
        }

        public function drop($name)
        {
            $result = $this->db->query("DROP TABLE `$name`");
            return !($result->isError());
        }
    }

?>
