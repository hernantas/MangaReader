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
            }
        }
    }

?>
