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

                $vend = $vendor->find('Page', $router->class);
                $classOrigin = $router->class;
                $class = '\\Page\\' . $router->class;
                $e404 = false;

                if ($vend !== false)
                {
                    include ($vend . '/Page/' . $router->class . '.php');

                    if (!class_exists($class) ||
                        !method_exists($class, $router->method))
                    {
                        if (!method_exists($class, 'route'))
                        {
                            $e404 = true;
                        }
                        else
                        {
                            $router->method = 'route';
                        }
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

                    $vendor = $vendor->find('Page', $router->class);

                    if ($vendor !== false)
                    {
                        include ($vendor . '/Page/' . $router->class . '.php');

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

                    $name = $class;

                    foreach ($list as $class=>$vp)
                    {
                        $vend = $vp[0];
                        $package = $vp[1];
                        static::$instance->$class =& loadClass($class, $package, $vend);
                    }

                    static::$instance->load =& static::$instance->loader;

                    logInfo("'".substr($name, 6)."' page is loaded.", 'Page');
                }
                else
                {
                    logWarning('No page is found and no handler for it either.');
                    notFound($classOrigin);
                }
            }

            return static::$instance;
        }
    }
?>
