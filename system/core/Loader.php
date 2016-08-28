<?php
    namespace Core;

    /**
     * Provide method to load specific class or file from recognized package like
     * library, etc.
     *
     * @package Core
     */
    class Loader
    {
        /**
         * List stored view
         *
         * @var array
         */
        private $storageView = array();

        public function autoload()
        {
            $config =& loadClass('Config', 'Core');
            $cfg = $config->load('Autoload');

            if ($cfg !== false)
            {
                $this->loadConfig($cfg);
            }
        }

        /**
         * Load configuration file
         *
         * @param  string $config Config name
         */
        private function loadConfig($config)
        {
            foreach ($config as $key=>$arr)
            {
                if (is_array($arr))
                {
                    foreach ($arr as $load)
                    {
                        $this->$key($load);
                    }
                }
                else
                {
                    $this->$key($arr);
                }
            }
        }

        /**
         * Load a view. View contain html element and will be displayed to user
         * browser. If template is used, will
         *
         * @param  string $name View name
         * @param  array  $data Data to pass to view
         *
         * @return bool         True if file is exists, false otherwise.
         */
        public function view($name, $data=array())
        {
            return $this->loadFile($name, 'View', $data);
        }

        /**
         * Store view to be used later.
         *
         * @param  string $name     View name
         * @param  array  $data     Data to be passed to view
         * @param  string $storage  Storage name
         */
        public function storeView($name, $data=array(), $storage='content')
        {
            $this->storageView[$storage][] = [$name=>$data];
        }

        /**
         * Get all stored view and send to user browser.
         *
         * @param  string $storage Storage name
         */
        public function fetchView($storage='content')
        {
            if (isset($this->storageView[$storage]))
            {
                foreach ($this->storageView[$storage] as $viewData)
                {
                    foreach ($viewData as $view => $data)
                    {
                        $this->view($view, $data);
                    }
                }
            }
        }

        public function library($name)
        {
            return $this->loadClass($name, 'Library');
        }

        /**
         * Load HTML layout. Layout is used as where to place view.
         *
         * @param  string $name Template name to load
         * @param  array  $data Data to pass to template
         *
         * @return bool         True if file is exists, false otherwise.
         */
        public function layout($name, $data=array())
        {
            return $this->loadFile($name, 'Layout', $data);
        }

        {
        }

        /**
         * Load html helper
         *
         * @param  string $name Helper name
         *
         * @return bool         True if file is exists, false otherwise.
         */
        public function helper($name)
        {
            return $this->loadFile($name, 'Helper');
        }

        /**
         * Find and load file if file is exists.
         *
         * @param  string $name    File name
         * @param  string $package Package where file is placed
         * @param  array  $data    Data that need to be passed to the file
         *
         * @return bool            True if file is exists and loaded, false otherwise
         */
        private function loadFile($name, $package='library', $data=array())
        {
            $data = $this->hook($name, $package, $data);

            $vendors =& loadClass('Vendor', 'Core');
            $list = $vendors->findVendor($package, $name);

            if (!is_array($list))
            {
                $list = array($list);
            }

            foreach ($list as $vendor)
            {
                if (file_exists($vendor . '/' . $package . '/' . $name . '.php'))
                {
                    extract($data);
                    include ($vendor . '/' . $package . '/' . $name . '.php');
                    return true;
                }
            }

            return false;
        }

        /**
         * Load class and it's file if file
         *
         * @param  string $name    Class name
         * @param  string $package Package name where file class is located
         *
         * @return object          Instance of the class
         */
        private function &loadClass($name, $package='library')
        {
            $vendors =& loadClass('Vendor', 'Core');
            $vendor = $vendors->findVendor($package, $name);
            return loadClass($name, $package, $vendor);
        }

        private function mergeClass(&$instance)
        {
            $vendors =& loadClass('Vendor', 'Core');
            $list = isLoaded();
            foreach ($list as $class=>$package)
            {
                if (!property_exists($instance, $class))
                {
                    $vendor = $vendors->findVendor($package, $class);
                    $instance->$class =& loadClass($class, $package, $vendor);
                }
            }

            $instance->load =& $instance->loader;

            return $instance;
        }
    }

?>
