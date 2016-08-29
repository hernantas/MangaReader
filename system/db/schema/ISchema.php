<?php
    namespace DB\Schema;

    interface ISchema
    {
        public function addField($field);
        public function create($name);
    }

?>
