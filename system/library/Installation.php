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
        private $config;
        private $installOrder = array();
        private $progressOrder = array();

        public function __construct()
        {
            $this->config =& page()->config;
            $cfg = $this->config->load('Install');

            if ($cfg !== false)
            {
                $info = $config->loadInfo('Install');
                if ($info !== false)
                {
                    $this->progressOrder = $info;
                }

                $this->installOrder = $cfg['installOrder'];
                $this->install();
            }
            else
            {
                // Generate empty config template
                $array = [
                    'installOrder'=>[]
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
