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
                $info = $this->config->loadInfo('Install');

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
            $state = array();
            $notDone = array();
            foreach ($this->installOrder as $order)
            {
                if (isset($this->progressOrder[$order]) &&
                    $this->progressOrder[$order] == 'done')
                {
                    $state[$order] = 'done';
                }
                else
                {
                    $notDone[] = $order;
                    $state[$order] = '';
                }
            }

            // Resave config info incase progress order is empty
            $this->config->saveInfo('Install', $state);

            if (isset($notDone[0]))
            {
                $ex = explode('/', $notDone[0]);
                $class = $ex[0]!==''?strtolower($ex[0]):'home';
                $method = isset($ex[1])?strtolower($ex[1]):'index';

                $router =& page()->router;
                if ($class != $router->class || $method != $router->method)
                {
                    $router->redirect($notDone[0]);
                }
            }
        }

        private function uninstall()
        {

        }
    }

?>
