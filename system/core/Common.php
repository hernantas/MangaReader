<?php

    if (!function_exists('loadClass'))
    {
        function &loadClass($className, $package='library', $vendors=[APP_PATH, SYSTEM_PATH])
        {
            static $instance = array();

            if (!is_array($vendors))
            {
                $vendors = array($vendors);
            }

            $className = strtolower($className);
            $class = false;

            if (isset($instance[$package]) && isset($instance[$package][$className]))
            {
                return $instance[$package][$className];
            }

            foreach ($vendors as $vendor)
            {
                $vendor = rtrim(strtolower($vendor), '/') . '/';

                if (file_exists($vendor . $package . '/' . $className . '.php'))
                {
                    include ($vendor . $package . '/' . $className . '.php');
                    $class = $className;
                    break;
                }
            }

            if ($class === false)
            {
                exit('Class "'.$className.'" is not found on "'.$package.'" package.');
            }

            $class = new $class();
            $instance[$package][$className] = $class;
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
