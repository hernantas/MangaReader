<?php

    if (!function_exists('paging'))
    {
        function paging($cur, $max, $leftLimit=4, $rightLimit=4)
        {
            $max = ceil($max);
            $ret = array();

            if ($cur > 2) $ret[] = ["First", 1];
            if ($cur > 1) $ret[] = ["Previous", $cur-1];

            if (($cur+$rightLimit) < $max)
            {
                $rightLimit += $leftLimit;
            }
            else
            {
                $r = $leftLimit;
                $leftLimit += ($cur+$rightLimit)-$max;
                $rightLimit += $r;
            }

            if ($cur-$leftLimit >= 1) $ret[] = ['...', '...'];
            for($i = max($cur-$leftLimit+1, 1); $i<=$max; $i++, $rightLimit--)
            {
                if ($rightLimit==0)
                {
                    $ret[] = ['...', '...'];
                    break;
                }
                $ret[] = [$i, $i];
            }
            if ($cur < $max) $ret[] = ["Next", $cur+1];
            if ($cur < $max-1) $ret[] = ["Last", $max];
            return $ret;
        }
    }
?>
