<?php
    namespace DB;

    include (SYSTEM_PATH.'db/Blueprint.php');

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
            $this->schemaDriver->reset();

            $func(new Blueprint($this));

            foreach ($this->fields as $field)
            {
                $this->schemaDriver->addField($field);
            }

            foreach ($this->constraint as $cons)
            {
                $this->schemaDriver->addConstraint($cons);
            }

            $this->schemaDriver->create($table);
        }

        public function drop($table)
        {
            $this->schemaDriver->drop($table);
        }

        public function reset()
        {
            $this->fields = array();
            $this->fieldCount = 0;
            $this->constraint = array();
        }

        public function &newField()
        {
            $this->fields[] = new BlueprintField();
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
            $constraint = new BlueprintConstraint();
            $constraint->type = $type;
            $constraint->name = $field;
            $this->constraint[] = $constraint;
        }
    }

?>
