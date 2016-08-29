<?php
    namespace Db\Builder;

    class MySQLi extends Builder
    {
        private $tbl = '';
        private $conds = '';

        public function reset()
        {
            $this->tbl = '';
            $this->conds = '';
        }

        public function table($table)
        {
            if ($this->tbl === '')
            {
                $this->tbl = $this->fieldQuote($table);
            }
            else
            {
                $this->tbl .= ', ' . $this->fieldQuote($table);
            }

            return $this;
        }

        public function join($table, $field1, $field2)
        {
            $this->addCond($this->fieldQuote($field1), '=', $this->fieldQuote($field2));
            return $this->table($table);
        }

        public function where($field, $vo1, $vo2='')
        {
            switch ($vo1)
            {
                case '=':
                case '>':
                case '>=':
                case '<':
                case '<=':
                case 'LIKE':
                    $vo2 = "'$vo2'";
                    break;
                default:
                    $vo2 = "'$vo1'";
                    $vo1 = '=';
                    break;
            }

            $this->addCond($this->conds, $this->fieldQuote($field), $vo1, $vo2);

            return $this;
        }

        public function whereOr($field, $vo1, $vo2='')
        {
            switch ($vo1)
            {
                case '=':
                case '>':
                case '>=':
                case '<':
                case '<=':
                case 'LIKE':
                    $vo2 = "'$vo2'";
                    break;
                default:
                    $vo2 = "'$vo1'";
                    $vo1 = '=';
                    break;
            }

            $this->addCond($this->conds, $this->fieldQuote($field), $vo1, $vo2, 'OR');

            return $this;
        }

        public function get($field='*')
        {
            $field = $this->splitToArr($field);

            $fields = '';
            foreach ($field as $f)
            {
                $f = $this->fieldQuote($f);

                if ($fields === '')
                {
                    $fields = $f;
                }
                else
                {
                    $fields .= ', ' . $f;
                }
            }

            $tables = $this->tbl;

            return $this->db->query("SELECT $fields FROM $tables".($this->conds!==''?' WHERE '.$this->conds:''));
        }
    }

?>
