<?php
    namespace Core;

    /**
     * Provide method to load specific class or file from recognized package like
     * library, page, etc.
     *
     * @package Core
     */
    class Loader
    {
        private $instance = array();

        public function page($name, $method='index')
        {
            if ($name==='')
            {
                $name = 'home';
            }

            $page = $this->loadClass($name, 'Page');

            if ($page === false || method_exists($page, $method) === false)
            {
                notFound($name . ($method!='index'?'/'.$method:''));
            }
            else
            {
                $page->$method();
            }
        }

        private function loadFile($name, $package='library', $data=array())
        {
            $vendors =& loadClass('Vendor', 'Core');
            $list = $vendors->lists();

            foreach ($list as $vendor)
            {
                if (file_exists($vendor . '/' . $package . '/' . $name . '.php'))
                {
                    include ($vendor . '/' . $package . '/' . $name . '.php');
                    return true;
                }
            }

            return false;
        }

        private function loadClass($name, $package='library')
        {
            $package = strtolower($package);
            $name = strtolower($name);

            if (isset($this->instance[$package]) && isset($this->instance[$package][$name]))
            {
                return $this->instance[$package][$name];
            }

            $hasFile = $this->loadFile($name, $package);

            if ($hasFile === false || class_exists($name) === false)
            {
                return false;
            }

            $class = new $name();
            $this->instance[$package][$name] = $class;
            return $class;
        }
    }

?>
