<?php

    if (!function_exists('inputText'))
    {
        function inputText($name, $placeholder='', $value='')
        {
            return input('text', $name, $value, $placeholder);
        }
    }

    if (!function_exists('inputSearch'))
    {
        function inputSearch($name, $placeholder='', $value='')
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
