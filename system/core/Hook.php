<?php
    namespace Core;

    class Hook
    {
        private $hook = array();

        public function data($name, $type, $data)
        {
            $vendors =& loadClass('Vendor', 'Core');
            $vendor = $vendors->find('Hook', $name);

            if ($vendor === false)
            {
                return $data;
            }

            $this->hook[$name] =& loadClass($name, 'Hook', $vendor);

            if (!method_exists($this->hook[$name], $type))
            {
                return $data;
            }

            $newData = $this->hook[$name]->$type($data);
            return is_array($newData) ? array_merge($data, $newData) : $data;
        }
    }

?>
