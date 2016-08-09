<?php

    if (!function_exists('html_title'))
    {
        /**
         * Generate Header Title
         *
         * @param  string $name  Website name
         * @param  string $title Page Title
         * @param  string $sep   Separator for separating website name and page
         *                       title
         *
         * @return string        Generated html
         */
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
