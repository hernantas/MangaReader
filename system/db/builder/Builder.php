<?php
    namespace DB\Builder;

    /**
     * Interface for all SQL Query Builder
     *
     * @package DB\Driver
     */
    abstract class Builder
    {
        abstract public function clear();
        abstract public function table($tables);

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

        protected function fieldQuote($field)
        {
            if (strpos($field, '.') !== false)
            {
                $ex = explode ('.', $field);
                $ex[0] = trim($ex[0]);
                $ex[1] = trim($ex[1]);
                return "`$ex[0]`.`$ex[1]`";
            }
            else if ($field === '*')
            {
                return $field;
            }
            return "`$field`";
        }
    }

?>
