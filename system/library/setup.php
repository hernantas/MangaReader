<?php
    namespace Library;

    /**
     * Provide easy to use installation or uninstallation order/proccess. Install
     * or uninstall order must be configured at config or this library will do
     * nothing at all.
     *
     * @package Library
     */
    class Setup
    {
        private $config;
        private $installOrder = array();
        private $progressOrder = array();
        private $isInstallPage = false;

        public function __construct()
        {
            $this->config =& page()->config;
            $cfg = $this->config->load('Setup');

            if ($cfg !== false)
            {
                $info = $this->config->loadInfo('Setup');

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
                $this->config->save('Setup', $array);
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
                else
                {
                    $this->isInstallPage = true;
                }
            }
        }

        /**
         * Prevent current page to be accessed outside install procedure.
         *
         * @param  string $redirect Redirect address if page is accessed. Will
         *                          redirect to home by default
         */
        public function installOnly($redirect='')
        {
            if ($this->isInstallPage !== true)
            {
                page()->router->redirect($redirect);
            }
        }

        /**
         * Finish current install progress.
         */
        public function finish()
        {
            $state = array();
            $done = true;

            foreach ($this->installOrder as $order)
            {
                if (isset($this->progressOrder[$order]) &&
                    $this->progressOrder[$order] == 'done')
                {
                    $state[$order] = 'done';
                }
                else
                {
                    if ($done)
                    {
                        $done = false;
                        $state[$order] = 'done';
                    }
                    else
                    {
                        $state[$order] = '';
                    }
                }
            }

            // Resave config info incase progress order is empty
            $this->config->saveInfo('Setup', $state);
        }
    }

?>
