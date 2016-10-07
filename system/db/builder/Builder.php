<?php
    namespace DB\Builder;

    /**
     * Interface for all SQL Query Builder
     *
     * @package DB\Driver
     */
    abstract class Builder
    {
        protected $bindData = array();
        protected $bindCount = array();

        abstract public function reset();
        abstract public function table($tables);
        abstract public function join($table, $field1, $field2);
        abstract public function where($field, $vo1, $vo2='');
        abstract public function whereOr($field, $vo1, $vo2='');
        abstract public function get($field='*');

        protected function resetData()
        {
            $this->bindData = array();
            $this->bindCount = array();
        }

        protected function addData($key, $val)
        {
            if (!isset($this->bindCount[$key])) $this->bindCount[$key] = 0;

            $bindKey = $key.'_'.$this->bindCount[$key];
            $this->bindData[$bindKey] = $val;
            $this->bindCount[$key]++;
            return ":$bindKey";
        }

        protected function splitToArr($s, $delim=',')
        {
            if (is_string($s))
            {
                if (strpos($s, $delim) !== false)
                {
                    return explode($delim, $s);
                }
                else
                {
                    return [$s];
                }
            }
            elseif (is_array($s))
            {
                return $s;
            }

            return $s;
        }

        /**
         * Automatically quote table field
         *
         * @param  string $field Field name
         *
         * @return string        Quoted field
         */
        protected function fieldQuote($field)
        {
            if ($field === '*' ||
                strpos($field, '(') !== false ||
                strpos($field, ' as ') !== false)
            {
                return trim($field);
            }
            elseif (strpos($field, '.') !== false)
            {
                $ex = explode ('.', $field);
                $ex[0] = trim($ex[0]);
                $ex[1] = trim($ex[1]);
                if ($ex[1] === '*')
                {
                    return "`$ex[0]`.$ex[1]";
                }
                return "`$ex[0]`.`$ex[1]`";
            }
            $field = trim($field);
            return "`$field`";
        }

        protected function valueQuote($value)
        {
            $value = trim($value);
            return "'$value'";
        }

        protected function addCond(&$conds, $vo1, $op, $vo2, $cond='AND')
        {
            if ($conds === '')
            {
                $conds = "$vo1$op$vo2";
            }
            else
            {
                $conds .= " $cond $vo1$op$vo2";
            }
        }
    }

?>
