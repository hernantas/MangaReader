<?php

    if (!function_exists('html_title'))
    {
        function header_title($name, $title='', $sep='-')
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
