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
        function inputText($name, $placeholder='', $value='', $readOnly=false, $cssClass='')
        {
            return input('text', $name, $value, $placeholder, $readOnly, $cssClass);
        }
    }

    if (!function_exists('inputSearch'))
    {
        function inputSearch($name, $placeholder='', $value='', $cssClass='')
        {
            return input('search', $name, $value, $placeholder, false, $cssClass);
        }
    }

    if (!function_exists('inputPassword'))
    {
        function inputPassword($name, $placeholder='', $cssClass='')
        {
            return input('password', $name, '', $placeholder, false, $cssClass);
        }
    }

    if (!function_exists('inputCheckbox'))
    {
        function inputCheckbox($name, $label, $value='', $cssClass='')
        {
            return '<label>' . input('checkbox', $name, $value===''?$name:$value, '', false, $cssClass) . $label . '</label>';
        }
    }

    if (!function_exists('inputRadio'))
    {
        function inputRadio($name, $value, $label, $default=false, $cssClass='')
        {
            return '<label>' . input('radio', $name, $value, '', false, $cssClass) . $label . '</label>';
        }
    }

    if (!function_exists('inputSubmit'))
    {
        function inputSubmit($value, $cssClass='')
        {
            return input('submit', '', $value, '', false, $cssClass);
        }
    }

    if (!function_exists('inputButton'))
    {
        function inputButton($value, $cssClass='')
        {
            return input('button', '', $value, '', false, $cssClass);
        }
    }

    if (!function_exists('input'))
    {
        function input($type, $name, $value='', $placeholder='', $readOnly=false, $cssClass='')
        {
            return '<input type="'.$type.'" name="'.$name.'" value="' .
                ($value === '' && $type != 'password' ? (page()->input->hasRequest($name) ? page()->input->request($name) : '') : $value) .
                '" placeholder="'.$placeholder.'" '.($readOnly?'readonly="true"':'') .
                ($cssClass!==''?'class="'.$cssClass.'"':'').' />';
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
