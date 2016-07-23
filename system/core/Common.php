<?php

    if (!function_exists('loadClass'))
    {
        /**
         * Find and load class if class and it's file is exists. Class file name
         * must be same with Class name and class must contain namespace with the
         * with the same package it located.
         *
         * @param  string $name    Class name to load
         * @param  string $package Package name where the class is
         * @param  array  $vendors Vendor list to search
         *
         * @return object          Instance of the class
         */
        function &loadClass($name, $package='library', $vendors=array(APP_PATH, SYSTEM_PATH))
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
        /**
         * Register class as loaded or get all loaded class.
         *
         * @param  string $package Package name where class is located
         * @param  string $name    Class name
         *
         * @return array           If using empty parameter, return all list of
         *                         loaded class. Nothing otherwise.
         */
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
        /**
         * Display 404 not found to the user browser and exiting.
         *
         * @param  string $page Page name to display in the message
         */
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

    if (!function_exists('printArray'))
    {
        /**
         * Utility function to print array with prefix and suffix <pre> so it can
         * look more friendly on user browser for debug purpose.
         *
         * @param  string $arr Array to print
         */
        function printArray($arr)
        {
            echo "<pre>";
            print_r($arr);
            echo "</pre>";
        }
    }
?>
