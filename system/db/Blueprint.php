<?php
    namespace DB;

    class Blueprint
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
            return $this;
        }

        public function increment($name)
        {
            $field = $this->schema->newField();
            $field->name = $name;
            $field->type = "INT";
            $field->autoinc = true;
            $this->primary();
            return $this;
        }

        public function string($name, $length=255)
        {
            $field = $this->schema->newField();
            $field->name = $name;
            $field->type = "VARCHAR($length)";
            return $this;
        }

        public function text($name)
        {
            $field = $this->schema->newField();
            $field->name = $name;
            $field->type = "TEXT";
            return $this;
        }

        public function date($name)
        {
            $field = $this->schema->newField();
            $field->name = $name;
            $field->type = "DATE";
            return $this;
        }

        public function datetime($name)
        {
            $field = $this->schema->newField();
            $field->name = $name;
            $field->type = "DATETIME";
            return $this;
        }

        public function nullable($field='')
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
                $this->schema->addConstraint(BlueprintConstraint::TYPE_PRIMARY,
                    $this->schema->lastField()->name);
            }
            else
            {
                $i = $this->schema->hasField($field);
                if ($i !== false)
                {
                    $f = $this->schema->getField($i);

                    $this->schema->addConstraint(BlueprintConstraint::TYPE_PRIMARY,
                        $f->name);
                }
            }
        }

        public function unqiue($field='')
        {
            if ($field === '')
            {
                $this->schema->addConstraint(BlueprintConstraint::TYPE_UNIQUE,
                    $this->schema->lastField()->name);
            }
            else
            {
                $i = $this->schema->hasField($field);
                if ($i !== false)
                {
                    $f = $this->schema->getField($i);

                    $this->schema->addConstraint(BlueprintConstraint::TYPE_UNIQUE,
                        $f->name);
                }
            }
        }

        public function index($field='')
        {
            if ($field === '')
            {
                $this->schema->addConstraint(BlueprintConstraint::TYPE_INDEX,
                    $this->schema->lastField()->name);
            }
            else
            {
                $i = $this->schema->hasField($field);
                if ($i !== false)
                {
                    $f = $this->schema->getField($i);

                    $this->schema->addConstraint(BlueprintConstraint::TYPE_INDEX,
                        $f->name);
                }
            }
        }
    }

    class BlueprintField
    {
        public $name = '';
        public $type = '';
        public $null = false;
        public $autoinc = false;
    }

    class BlueprintConstraint
    {
        const TYPE_PRIMARY = 'PRIMARY';
        const TYPE_UNIQUE = 'UNIQUE';
        const TYPE_INDEX = 'INDEX';

        public $type = '';
        public $name = '';
    }
?>
