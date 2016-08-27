<?php

    if (!function_exists('input_text'))
    {
        function input_text($name, $placeholder='', $value='')
        {
            return input('text', $name, $value, $placeholder);
        }
    }

    if (!function_exists('input_search'))
    {
        function input_search($name, $placeholder='', $value='')
        {
            return input('search', $name, $value, $placeholder);
        }
    }

    if (!function_exists('input'))
    {
        function input($type='', $name, $value='', $placeholder='')
        {
            return '<input type="'.$type.'" name="'.$name.'" value="'.$value .
                '" placeholder="'.$placeholder.'" />';
        }
    }

?>
