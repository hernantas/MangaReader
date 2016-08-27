<?php

    if (!function_exists('headerTitle'))
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
        function headerTitle($name, $title='', $sep='-')
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

    if (!function_exists('doctype'))
    {
        /**
         * Get HTML Doctype
         *
         * @param  string $type Doctype type
         *
         * @return string       Doctype based on $type or empty string
         */
        function doctype($type='html5')
        {
            static $dt = array(
            	'xhtml11' => '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">',
            	'xhtml1-strict' => '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">',
            	'xhtml1-trans' => '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">',
            	'xhtml1-frame' => '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Frameset//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-frameset.dtd">',
            	'xhtml-basic11' => '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML Basic 1.1//EN" "http://www.w3.org/TR/xhtml-basic/xhtml-basic11.dtd">',
            	'html5' => '<!DOCTYPE html>',
            	'html4-strict' => '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">',
            	'html4-trans' => '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">',
            	'html4-frame' => '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN" "http://www.w3.org/TR/html4/frameset.dtd">',
            	'mathml1' => '<!DOCTYPE math SYSTEM "http://www.w3.org/Math/DTD/mathml1/mathml.dtd">',
            	'mathml2' => '<!DOCTYPE math PUBLIC "-//W3C//DTD MathML 2.0//EN" "http://www.w3.org/Math/DTD/mathml2/mathml2.dtd">',
            	'svg10' => '<!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.0//EN" "http://www.w3.org/TR/2001/REC-SVG-20010904/DTD/svg10.dtd">',
            	'svg11' => '<!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.1//EN" "http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd">',
            	'svg11-basic' => '<!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.1 Basic//EN" "http://www.w3.org/Graphics/SVG/1.1/DTD/svg11-basic.dtd">',
            	'svg11-tiny' => '<!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.1 Tiny//EN" "http://www.w3.org/Graphics/SVG/1.1/DTD/svg11-tiny.dtd">',
            	'xhtml-math-svg-xh' => '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1 plus MathML 2.0 plus SVG 1.1//EN" "http://www.w3.org/2002/04/xhtml-math-svg/xhtml-math-svg.dtd">',
            	'xhtml-math-svg-sh' => '<!DOCTYPE svg:svg PUBLIC "-//W3C//DTD XHTML 1.1 plus MathML 2.0 plus SVG 1.1//EN" "http://www.w3.org/2002/04/xhtml-math-svg/xhtml-math-svg.dtd">',
            	'xhtml-rdfa-1' => '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML+RDFa 1.0//EN" "http://www.w3.org/MarkUp/DTD/xhtml-rdfa-1.dtd">',
            	'xhtml-rdfa-2' => '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML+RDFa 1.1//EN" "http://www.w3.org/MarkUp/DTD/xhtml-rdfa-2.dtd">'
            );

            return isset($dt[$type]) ? $dt[$type] : '';
        }
    }

    if (!function_exists('css'))
    {
        /**
         * Get HTML tag to insert css to the header. CSS must be placed in public/css
         * directory.
         *
         * @param  string $name CSS file name
         *
         * @return string       HTML Tag
         */
        function css($name)
        {
            return '<link rel="stylesheet" type="text/css" href="'.baseUrl().'public/css/'.$name.'.css">';
        }
    }
    if (!function_exists('js'))
    {
        /**
         * Get HTML tag to insert javascript to the header. Javascript file must
         * be placed in public/js directory.
         *
         * @param  string $name Javascript file name
         *
         * @return string       HTML Tag
         */
        function js($name)
        {
            return '<script type="text/javascript" src="'.baseUrl().'public/js/'.$name.'.js"></script>';
        }
    }
?>
