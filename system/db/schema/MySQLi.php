<?php
    namespace DB\Schema;

    class MySQLi implements ISchema
    {
        private $fields = array();

        public function addField($field)
        {
            $this->fields[] = "`$field->name` $field->type".($field->null?'':' NOT NULL ').
                ($field->autoinc?' AUTO_INCREMENT ':'');
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

            $sql = "CREATE TABLE `$table` ($fs)";
            echo "$sql";
            //$this->db->query($sql);
        }
    }
?>
