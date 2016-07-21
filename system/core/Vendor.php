<?php

    /**
     * Vendor Class
     *
     * Manage vendor and can automatically detect vendor.
     *
     * @package Core
     */
    class Vendor
    {
        private $vendors = array();

        public function __construct()
        {
            if (ENVIRONMENT == 'testing' || ENVIRONMENT == 'development')
            {
                $dirs = scandir(BASE_PATH);
                $length = count($dirs);
                $arr = array();
                for ($i = 0 ; $i < count($dirs) ; $i++)
                {
                    if ($dirs[$i] != '.' && $dirs[$i] != '..' && $dirs[$i] != '.git' &&
                        is_dir($dirs[$i]))
                    {
                        $arr[] = $dirs[$i];
                    }
                }

                $config =& loadClass('Config', 'Core');
                $config->save('Vendor', $arr);
                $this->vendors = $arr;
            }
            else
            {
                $config =& loadClass('Config', 'Core');
                $arr = $config->load('Vendor');

                if ($arr !== false)
                {
                    $this->vendors = $arr;
                }
            }
        }

        /**
         * Get vendor list
         *
         * @return array Vendor list as an array
         */
        public function lists()
        {
            return $this->vendors;
        }
    }

?>
