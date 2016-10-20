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
            $this->resetData();
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
            if (strcasecmp("=", $vo1) === 0 ||
                strcasecmp("!=", $vo1) === 0 ||
                strcasecmp(">", $vo1) === 0 ||
                strcasecmp(">=", $vo1) === 0 ||
                strcasecmp("<", $vo1) === 0 ||
                strcasecmp("<=", $vo1) === 0 ||
                strcasecmp("LIKE", $vo1) === 0)
            {
                if (strcasecmp("LIKE", $vo1)===0) $vo1 = " $vo1 ";
            }
            else
            {
                $vo2 = $vo1;
                $vo1 = '=';
            }

            $key = $this->addData('where', $vo2);
            $this->addCond($this->conds, $this->fieldQuote($field), $vo1, $key);
            return $this;
        }

        public function whereOr($field, $vo1, $vo2='')
        {
            if (strcasecmp("=", $vo1) === 0 ||
                strcasecmp("!=", $vo1) === 0 ||
                strcasecmp(">", $vo1) === 0 ||
                strcasecmp(">=", $vo1) === 0 ||
                strcasecmp("<", $vo1) === 0 ||
                strcasecmp("<=", $vo1) === 0 ||
                strcasecmp("LIKE", $vo1) === 0)
            {
                if (strcasecmp("LIKE", $vo1)===0) $vo1 = " $vo1 ";
            }
            else
            {
                $vo2 = $vo1;
                $vo1 = '=';
            }

            $key = $this->addData('where', $vo2);
            $this->addCond($this->conds, $this->fieldQuote($field), $vo1, $key, 'OR');

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

            if ($this->bindCount > 0)
            {
                return $this->db->bind("SELECT $fields FROM $tables" .
                    ($this->conds!==''?' WHERE '.$this->conds:'') .
                    ($this->grp!==''?' GROUP BY '.$this->grp:'') .
                    ($this->order!==''?' '.$this->order:'') .
                    ($this->lim!==''?' '.$this->lim:''), $this->bindData);
            }

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
                $key = $this->addData('insert', $val);
                if ($values === '')
                {
                    $values = "$key";
                }
                else
                {
                    $values .= ", $key";
                }
            }

            return $this->db->bind("INSERT INTO $this->tbl VALUES ($values)", $this->bindData);
        }

        public function update($array)
        {
            $pair = '';
            if (is_array($array))
            {
                foreach ($array as $key=>$val)
                {
                    $bKey = $this->addData($key, $val);

                    if ($pair === '')
                    {
                        $pair = $this->fieldQuote($key)."=$bKey";
                    }
                    else
                    {
                        $pair .= ", ".$this->fieldQuote($key)."=$bKey";
                    }
                }
            }
            else
            {
                $pair = $array;
            }

            return $this->db->bind("UPDATE $this->tbl SET $pair".
                ($this->conds!==''?' WHERE '.$this->conds:''), $this->bindData);
        }

        public function delete()
        {
            return $this->db->bind("DELETE FROM $this->tbl".
                ($this->conds!==''?' WHERE '.$this->conds:'') .
                ($this->order!==''?' '.$this->order:''), $this->bindData);
        }

        public function order($field, $asc=true)
        {
            $this->order = "ORDER BY ".$this->fieldQuote($field)." ".($asc || strcasecmp($asc, 'asc')===0?'ASC':'DESC');
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
