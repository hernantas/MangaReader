<?php
    namespace Library;

    /**
     * Provide easy to use installation or uninstallation order/proccess. Install
     * or uninstall order must be configured at config or this library will do
     * nothing at all.
     *
     * @package Library
     */
    class Installation
    {
        private $installOrder = array();
        private $uninstallOrder = array();

        public function __construct()
        {
            $config =& page()->config;
            $cfg = $config->load('Install');

            if ($cfg !== false)
            {
                $this->installOrder = $cfg['installOrder'];
                $this->uninstallOrder = $cfg['uninstallOrder'];
            }
            else
            {
                $array = [
                    'installOrder'=>[],
                    'uninstallOrder'=>[]
                ];
                $config->save('Install', $array);
            }
        }

        private function install()
        {

        }

        private function uninstall()
        {

        }
    }

?>
