<?php

    if (!function_exists('inputHidden'))
    {
        function inputHidden($name, $value)
        {
            return input([
                'type'=>'hidden',
                'name'=>$name,
                'value'=>$value
            ]);
        }
    }

    if (!function_exists('inputText'))
    {
        function inputText($name, $placeholder='', $value='', $readOnly=false, $css='')
        {
            return input([
                'type'=>'search',
                'name'=>$name,
                'placeholder'=>$placeholder,
                'value'=>$value,
                'readonly'=>$readOnly,
                'css'=>$css
            ]);
        }
    }

    if (!function_exists('inputSearch'))
    {
        function inputSearch($name, $placeholder='', $css='')
        {
            return input([
                'type'=>'search',
                'name'=>$name,
                'placeholder'=>$placeholder,
                'css'=>$css
            ]);
        }
    }

    if (!function_exists('inputPassword'))
    {
        function inputPassword($name, $placeholder='', $css='')
        {
            return input([
                'type'=>'password',
                'name'=>$name,
                'placeholder'=>$placeholder,
                'css'=>$css
            ]);
        }
    }

    if (!function_exists('inputCheckbox'))
    {
        function inputCheckbox($name, $label, $checked=false, $value='', $css='')
        {
            return '<label>' . input([
                'type'=>'checkbox',
                'name'=>$name,
                'value'=>$value,
                'checked'=>$checked,
                'css'=>$css
            ]) . $label . '</label>';
        }
    }

    if (!function_exists('inputRadio'))
    {
        function inputRadio($name, $value, $label, $checked=false, $css='')
        {
            return '<label>' . input([
                'type'=>'radio',
                'name'=>$name,
                'value'=>$value,
                'checked'=>$checked,
                'css'=>$css
            ]) . $label . '</label>';
        }
    }

    if (!function_exists('inputSubmit'))
    {
        function inputSubmit($value, $css='')
        {
            return input([
                'type'=>'submit',
                'value'=>$value,
                'css'=>$css
            ]);
        }
    }

    if (!function_exists('inputButton'))
    {
        function inputButton($value, $css='')
        {
            return input([
                'type'=>'button',
                'value'=>$value,
                'css'=>$css
            ]);
        }
    }

    if (!function_exists('input'))
    {
        function input($option=array())
        {
            if (!isset($option['type']))
            {
                logError('No type input', 'Helper Form');
                return '';
            }

            return '<input type="' . $option['type'] . '"' .
                (isset($option['name']) ? ' name="'.$option['name'].'"' : '') .
                (isset($option['value']) && $option['value'] !== '' ? ' value="'.$option['value'].'"' : '') .
                (isset($option['placeholder']) ? ' placeholder="'.$option['placeholder'].'"' : '') .
                (isset($option['checked']) && $option['checked'] ? ' checked="checked"' : '') .
                (isset($option['readonly']) && $option['readonly'] ? ' readonly="readonly"' : '') .
                (isset($option['css']) && $option['css'] !== '' ? ' class="'.$option['css'].'"' : '') .
                ' />';
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
