<?php
    namespace Core;

    /**
     * Provide access routing from user requested page to the specific handler page.
     *
     * @package Core
     */
    class Router
    {
        /**
         * Route configuration.
         *
         * @var array
         */
        private $route = array();

        /**
         * Routed page class name to be called
         *
         * @var string
         */
        public $class = '';

        /**
         * Routed method class name to be called
         *
         * @var string
         */
        public $method = '';

        public function __construct()
        {
            $this->loadConfig();
            $this->routing();

            logInfo("Route to '$this->class' class with '$this->method' method");
        }

        /**
         * Load configuration
         */
        private function loadConfig()
        {
            $config =& loadClass('Config', 'Core');
            $cfg = $config->load('Routing');

            if ($cfg !== false)
            {
                foreach ($cfg as $key => $value)
                {
                    $this->addRoute($key, $value);
                }
            }
        }

        /**
         * Add route by using url rule
         *
         * @param string $rule    URL Rule
         * @param string $handler URL Handler
         */
        public function addRoute($rule, $handler)
        {
            $rule = trim(rtrim($rule, '/'), '/');
            $handler = trim(rtrim($handler, '/'), '/');
            $this->route[$rule] = $handler;
        }

        /**
         * Find route based on rule.
         *
         * @param  string $url URL to match the rule
         *
         * @return string      Route based on available rule if available
         */
        private function findRoute($url)
        {
            foreach ($this->route as $key=>$val)
            {
                $pos = strpos($url, $key);
                if ($pos === 0 || $pos === 1)
                {
                    return $val;
                }
            }

            return $url;
        }

        /**
         * Start routing proccess
         */
        private function routing()
        {
            $uri =& loadClass('Uri', 'Core');
            $url = $this->findRoute($uri->string());
            $urls = explode('/', trim($url, '/'));

            $this->class = $urls[0]!==''?$urls[0]:'home';
            $this->method = isset($urls[1])?$urls[1]:'index';
        }

        /**
         * Redirect user browser to the relative path of the host
         *
         * @param  string $page Page direction
         */
        public function redirect($page='')
        {
            $uri =& loadClass('Uri', 'Core');
            $url = $uri->baseUrl().strtolower($page);
            header('location: '.$url);
            logInfo("Router: Redirect to $url");
            exit();
        }

        /**
         * Redirect user browser to the relative path of the host with delay.
         * Require to not display anything to browser before.
         *
         * @param  string $page  Page direction
         * @param  int    $delay Delay in second
         */
        public function delayedRedirect($page='', $delay=2)
        {
            $uri =& loadClass('Uri', 'Core');
            $url = $uri->baseUrl().strtolower($page);
            header("refresh:$delay; url=$url");
            logInfo("Router: Redirect to $url with $delay second delay");
        }
    }

?>
