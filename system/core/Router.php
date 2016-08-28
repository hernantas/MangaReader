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

        public function __construct()
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
        public function routing()
        {
            $uri =& loadClass('Uri', 'Core');
            $url = $this->findRoute($uri->string());

            $urls = explode('/', trim($url, '/'));
            $loader =& loadClass('Loader', 'Core');
            $page = $loader->page($urls[0]);
            $method = isset($urls[1])?$urls[1]:'index';

            if ($page === false || !method_exists($page, $method))
            {
                $page = $loader->page('NotFound');

                if ($page === false || !method_exists($page, 'index'))
                {
                    notFound($urls[0]);
                }
            }

            $loader->autoload();
            $page->$method();
        }
    }

?>
