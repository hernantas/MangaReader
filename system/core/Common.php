<?php

    if (!function_exists('loadClass'))
    {
        function &loadClass($name, $package='library', $vendors=[APP_PATH, SYSTEM_PATH])
        {
            static $instance = array();

            if (!is_array($vendors))
            {
                $vendors = array($vendors);
            }

            $package = strtolower($package);
            $name = strtolower($name);
            $class = $package.'\\'.$name;

            if (isset($instance[$class]))
            {
                return $instance[$class];
            }

            $fileFound = false;
            foreach ($vendors as $vendor)
            {
                $vendor = rtrim(strtolower($vendor), '/') . '/';

                if (file_exists($vendor . $package . '/' . $name . '.php'))
                {
                    include ($vendor . $package . '/' . $name . '.php');
                    $fileFound = true;
                    break;
                }
            }

            $class = str_replace('/', '\\', $class);
            if ($fileFound === false || class_exists($class) === false)
            {
                echo ('Class "'.$name.'" is not found on "'.$package.'" package.');
                exit(-1);
            }

            $name = $class;
            $class = new $class();
            $instance[$name] = $class;
            return $class;
        }
    }

    if (!function_exists('isLoaded'))
    {
        function isLoaded($package='', $name='')
        {
            static $loaded = array();

            if ($package === '' && $name === '')
            {
                return $loaded;
            }

            $loaded[$package][$name] = strtolower($name);
        }
    }

    if (!function_exists('notFound'))
    {
        function notFound($page='')
        {
            echo "<div>";
                echo "<h1>404 Page not found</h1>";
                echo '<span>Your requested page ' . ($page==='' ? '' : '"'.$page.'"') .
                    ' can not be found on this server thus return 404 error response.</span>';
            echo "</div>";
            exit(0);
        }
    }
?>
