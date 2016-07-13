<?php

    class Loader
    {
        private $instance = array();

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