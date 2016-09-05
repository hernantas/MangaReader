<?php
    namespace Db\Builder;

    class MySQLi extends Builder
    {
        private $tbl = '';
        private $conds = '';
        private $order = '';
        private $lim = '';
        private $grp = '';

        public function reset()
        {
            $this->tbl = '';
            $this->conds = '';
            $this->order = '';
            $this->lim = '';
            $this->grp = '';
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
            $this->table($table)->addCond($this->conds, $this->fieldQuote($field1), '=', $this->fieldQuote($field2));
            return $this;
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
                    $vo2 = "'".$this->db->escape($vo2)."'";
                    break;
                default:
                    $vo2 = "'".$this->db->escape($vo1)."'";
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
                    $vo2 = "'".$this->db->escape($vo2)."'";
                    break;
                default:
                    $vo2 = "'".$this->db->escape($vo1)."'";
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

            return $this->db->query("SELECT $fields FROM $tables" .
                ($this->conds!==''?' WHERE '.$this->conds:'') .
                ($this->grp!==''?' GROUP BY '.$this->grp:'') .
                ($this->order!==''?' '.$this->order:'') .
                ($this->lim!==''?' '.$this->lim:''));
        }

        public function insert($array)
        {
            $values = '';
            foreach ($array as $val)
            {
                $val = $this->db->escape($val);
                if ($values === '')
                {
                    $values = "'$val'";
                }
                else
                {
                    $values .= ", '$val'";
                }
            }

            return $this->db->query("INSERT INTO $this->tbl VALUES ($values)");
        }

        public function update($array)
        {
            $pair = '';
            if (is_array($array))
            {
                foreach ($array as $key=>$val)
                {
                    $val = $this->db->escape($val);
                    if ($pair === '')
                    {
                        $pair = $this->fieldQuote($key)."='$val'";
                    }
                    else
                    {
                        $pair .= ", ".$this->fieldQuote($key)."='$val'";
                    }
                }
            }
            else
            {
                $pair = $array;
            }

            return $this->db->query("UPDATE $this->tbl SET $pair".
                ($this->conds!==''?' WHERE '.$this->conds:''));
        }

        public function delete()
        {
            return $this->db->query("DELETE FROM $this->tbl".
                ($this->conds!==''?' WHERE '.$this->conds:'') .
                ($this->order!==''?' '.$this->order:''));
        }

        public function order($field, $asc=true)
        {
            $this->order = "ORDER BY ".$this->fieldQuote($field)." ".($asc?'ASC':'DESC');
            return $this;
        }

        public function limit($page, $limit)
        {
            $this->lim = "LIMIT $page, $limit";
            return $this;
        }

        public function group($field)
        {
            if ($this->grp === '')
            {
                $this->grp = $this->fieldQuote($field);
            }
            else
            {
                $this->grp .= ', '.$this->fieldQuote($field);
            }
            return $this;
        }
    }

?>
