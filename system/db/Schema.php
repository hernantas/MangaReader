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
        private $constraint = array();

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

        public function hasTable($table)
        {
            return $this->schemaDriver->hasTable($table);
        }

        public function create($table, $func)
        {
            $this->reset();
            $func(new SchemaTable($this));

            foreach ($this->fields as $field)
            {
                $this->schemaDriver->addField($field);
            }

            foreach ($this->constraint as $cons)
            {
                $this->schemaDriver->addConstraint($cons);
            }

            $this->schemaDriver->create($table);

            exit();
        }

        public function reset()
        {
            $this->fields = array();
            $this->constraint = array();
        }

        public function &newField()
        {
            $this->fields[] = new SchemaField();
            $this->fieldCount++;

            return $this->fields[$this->fieldCount-1];
        }

        public function hasField($name)
        {
            for ($i = 0; $i < $this->fieldCount; $i++)
            {
                return $i;
            }

            return false;
        }

        public function &getField($i)
        {
            return $this->fields[$i];
        }

        public function &lastField()
        {
            return $this->fields[$this->fieldCount-1];
        }

        public function addConstraint($type, $field)
        {
            $constraint = new SchemaConstraint();
            $constraint->type = $type;
            $constraint->name = $field;
            $this->constraint[] = $constraint;
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

            $this->primary();
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
            else
            {
                $i = $this->schema->hasField($field);
                if ($i !== false)
                {
                    $f = $this->schema->getField($i);
                    $f->null = true;
                }
            }
        }

        public function primary($field='')
        {
            if ($field === '')
            {
                $this->schema->addConstraint(SchemaConstraint::TYPE_PRIMARY,
                    $this->schema->lastField()->name);
            }
            else
            {
                $i = $this->schema->hasField($field);
                if ($i !== false)
                {
                    $f = $this->schema->getField($i);

                    $this->schema->addConstraint(SchemaConstraint::TYPE_PRIMARY,
                        $f->name);
                }
            }
        }
    }

    class SchemaField
    {
        public $name = '';
        public $type = '';
        public $null = false;
        public $autoinc = false;
    }

    class SchemaConstraint
    {
        const TYPE_PRIMARY = 'PRIMARY';
        const TYPE_UNIQUE = 'UNIQUE';
        const TYPE_INDEX = 'INDEX';

        public $type = '';
        public $name = '';
    }

?>
