<?php

    if (!function_exists('inputHidden'))
    {
        function inputHidden($name, $value)
        {
            return input('hidden', $name, $value);
        }
    }

    if (!function_exists('inputText'))
    {
        function inputText($name, $placeholder='', $value='', $readOnly=false)
        {
            return input('text', $name, $value, $placeholder, $readOnly);
        }
    }

    if (!function_exists('inputSearch'))
    {
        function inputSearch($name, $placeholder='', $value='')
        {
            return input('search', $name, $value, $placeholder);
        }
    }

    if (!function_exists('inputPassword'))
    {
        function inputPassword($name, $placeholder='')
        {
            return input('password', $name, '', $placeholder);
        }
    }

    if (!function_exists('inputCheckbox'))
    {
        function inputCheckbox($name, $label, $value='')
        {
            return '<label>' . input('checkbox', $name, $value) . $label . '</label>';
        }
    }

    if (!function_exists('inputSubmit'))
    {
        function inputSubmit($value)
        {
            return input('submit', '', $value, '');
        }
    }

    if (!function_exists('input'))
    {
        function input($type='', $name, $value='', $placeholder='', $readOnly=false)
        {
            return '<input type="'.$type.'" name="'.$name.'" value="' .
                ($value === '' && $type != 'password' ? (page()->input->hasRequest($name) ? page()->input->request($name) : '') : $value) .
                '" placeholder="'.$placeholder.'" '.($readOnly?'readonly="true"':'').' />';
        }
    }

    if (!function_exists('formOpen'))
    {
        function formOpen($action, $method='post', $data=false)
        {
            return '<form action="'.baseUrl().$action.'" method="'.$method.'">';
        }
    }

    if (!function_exists('formClose'))
    {
        function formClose()
        {
            return '</form>';
        }
    }

?>
