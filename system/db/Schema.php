<?php
    namespace DB;

    class Schema
    {
        const MODE_CREATE = 1;
        const MODE_MODIFY = 2;
        const MODE_DROP = 3;

        private $schemaDriver = null;

        private $mode = '';
        private $fields = array();
        private $fieldCount = 0;

        public function __construct($name, &$db)
        {
            $vendor =& loadClass('Vendor', 'Core');
            $list = $vendor->find('DB/Schema', $name);

            if (!is_array($list))
            {
                include ($list . '/DB/Schema/'.$name.'.php');
                $class = '\\DB\\Schema\\'.$name;

                if (class_exists($class))
                {
                    $this->schemaDriver = new $class();
                    $this->schemaDriver->db =& $db;
                }
            }
        }

        public function create($table, $func)
        {
            $this->reset();
            $func(new SchemaTable($this));

            foreach ($this->fields as $field)
            {
                $this->schemaDriver->addField($field);
            }

            $this->schemaDriver->create($table);

            exit();
        }

        public function reset()
        {
            $this->fields = array();
        }

        public function &newField()
        {
            $this->fields[] = new SchemaField();
            $this->fieldCount++;

            return $this->fields[$this->fieldCount-1];
        }

        public function &findField($name)
        {
            for ($i = 0; $i < $this->fieldCount; $i++)
            {
                return $this->fields[$i];
            }
            return $this->fields[0];
        }

        public function &lastField()
        {
            return $this->fields[$this->fieldCount-1];
        }
    }

    class SchemaTable
    {
        private $schema = null;

        public function __construct(&$schema)
        {
            $this->schema =& $schema;
        }

        public function int($name)
        {
            $field = $this->schema->newField();
            $field->name = $name;
            $field->type = "INT";
        }

        public function increment($name)
        {
            $field = $this->schema->newField();
            $field->name = $name;
            $field->type = "INT";
            $field->autoinc = true;
        }

        public function string($name, $length=255)
        {
            $field = $this->schema->newField();
            $field->name = $name;
            $field->type = "VARCHAR($length)";
        }

        public function text($name)
        {
            $field = $this->schema->newField();
            $field->name = $name;
            $field->type = "TEXT";
        }

        public function date($name)
        {
            $field = $this->schema->newField();
            $field->name = $name;
            $field->type = "DATE";
        }

        public function datetime($name)
        {
            $field = $this->schema->newField();
            $field->name = $name;
            $field->type = "DATETIME";
        }

        public function Nullable($field='')
        {
            if ($field === '')
            {
                $this->schema->lastField()->null = true;
            }
        }

        public function primary($field='')
        {

        }
    }

    class SchemaField
    {
        public $name = '';
        public $type = '';
        public $null = false;
        public $autoinc = false;
    }

?>
