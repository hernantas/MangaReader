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
        function &loadClass($name, $package='library', $vendor=SYSTEM_PATH)
        {
            static $instance = array();

            $package = str_replace('/', '\\', strtolower($package));
            $name = strtolower($name);
            $class = $package.'\\'.$name;

            if (isset($instance[$class]))
            {
                return $instance[$class];
            }

            $fileFound = false;
            $vendor = rtrim(strtolower($vendor), '/') . '/';

            if (file_exists($vendor . $package . '/' . $name . '.php'))
            {
                include ($vendor . $package . '/' . $name . '.php');
                $fileFound = true;
            }

            $class = str_replace('/', '\\', $class);
            if ($fileFound === false || class_exists($class) === false)
            {
                echo ('Can\'t find class "'.$name.'" at "'.$package.'" package.');
                exit(-1);
            }

            isLoaded($vendor, $package, $name);
            $name = $class;
            $instance[$name] = new $class();
            return $instance[$name];
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
        function isLoaded($vendor='', $package='', $name='')
        {
            static $loaded = array();

            if ($package === '' || $name === '')
            {
                return $loaded;
            }

            $loaded[$name] = [$vendor, $package];
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

    if (!function_exists('page'))
    {
        /**
         * Get Current page
         *
         * @return object Loaded page
         */
        function &page()
        {
            return \Core\Page::getInstance();
        }
    }

    if (!function_exists('baseUrl'))
    {
        /**
         * Get base URL
         *
         * @return string Base URL
         */
        function baseUrl()
        {
            return page()->uri->baseUrl();
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

    if (!function_exists('logInfo'))
    {
        function logInfo($message)
        {
            static $log = null;

            if ($log === null)
            {
                $log =& loadClass('Log', 'Core');
            }

            $log->info($message,1,2);
        }
    }

    if (!function_exists('logWarning'))
    {
        function logWarning($message)
        {
            static $log = null;

            if ($log === null)
            {
                $log =& loadClass('Log', 'Core');
            }

            $log->warning($message,1,2);
        }
    }

    if (!function_exists('logError'))
    {
        function logError($message)
        {
            static $log = null;

            if ($log === null)
            {
                $log =& loadClass('Log', 'Core');
            }

            $log->error($message,1,2);
        }
    }
?>
