<?php
    namespace Core;

    class Page
    {
        private static $instance = null;

        public static function &getInstance()
        {
            if (static::$instance === null)
            {
                $router =& loadClass('Router', 'Core');
                $vendor =& loadClass('Vendor', 'Core');

                $vendorList = $vendor->find('Page', $router->class);
                $classOrigin = $router->class;
                $class = '\\Page\\' . $router->class;
                $e404 = false;

                if (!is_array($vendorList))
                {
                    include ($vendorList . '/Page/' . $router->class . '.php');

                    if (!class_exists($class) ||
                        !method_exists($class, $router->method))
                    {
                        $e404 = true;
                    }
                }
                else
                {
                    $e404 = true;
                }

                if ($e404 === true)
                {
                    $router->class = 'NotFound';
                    $router->method = 'index';
                    $class = '\\Page\\' . $router->class;

                    $vendorList = $vendor->find('Page', $router->class);

                    if (!is_array($vendorList))
                    {
                        include ($vendorList . '/Page/' . $router->class . '.php');

                        if (class_exists($class) && method_exists($class, $router->method))
                        {
                            $e404 = false;
                        }
                    }
                }

                if ($e404 === false)
                {
                    static::$instance = new $class();
                    $list = isLoaded();

                    foreach ($list as $class=>$vp)
                    {
                        $vend = $vp[0];
                        $package = $vp[1];
                        static::$instance->$class =& loadClass($class, $package, $vend);
                    }

                    static::$instance->load =& static::$instance->loader;
                }
                else
                {
                    notFound($classOrigin);
                }
            }

            return static::$instance;
        }
    }
?>
