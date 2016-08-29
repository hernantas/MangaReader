<?php
    namespace DB\Schema;

    interface ISchema
    {
        public function reset();
        public function addField($field);
        public function addConstraint($cons);

        public function hasTable($name);
        public function create($name);
        public function drop($name);
    }

?>
