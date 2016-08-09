<?php

    if (!function_exists('html_title'))
    {
        function html_title($name, $title='', $sep='-')
        {

            if ($title === '')
            {
                return '<title>' . $name . '</title>';
            }
            else
            {
                return '<title>' . $title . ' ' . $sep . ' ' . $name . '</title>';
            }
        }
    }

?>
