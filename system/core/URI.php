<?php
    namespace Core;
    
    /**
     * URI Class
     *
     * Change user requested URL to be more easy to use.
     *
     * @package Core
     */
    class URI
    {
        private $dirname = '';

        private $urlString = '';
        private $urlSegment = array();
        private $urlSegmentPair = array();

        public function __construct()
        {
            $this->parse();
        }

        /**
         * Parse url to more usable form.
         */
        private function parse()
        {
            $url = 'http://dummy' . $_SERVER['REQUEST_URI'];
            $url = parse_url($url);
            $query = @$url['query'];
            $url = $url['path'];
            $url = rtrim($url, '/');

            $dirname = '/' . basename(BASE_PATH);

            if (strpos($url, $dirname) === 0)
            {
                $this->dirname = $dirname;
                $url = substr($url, strlen($dirname));
            }

            if ($url === '' || $url === false) $url = '/';

            $this->urlString = $url;
            $ex = explode('/', $url);
            $this->urlSegment = $ex;
        }

        /**
         * Get URL Segment
         *
         * @param  string $index   Segment index (1-x)
         * @param  string $default Default value (false by default)
         *
         * @return string          URL Segment at index or default value if not exists.
         */
        public function segment($index, $default=false)
        {
            return isset($this->urlSegment[$index]) ? $this->urlSegment[$index] : $default;
        }

        /**
         * Get URL Segment as Key Value Pair. URL like user/admin/page/profile will
         * be converted to user=admin, page=profile.
         *
         * @param  string $key     URL Segment key
         * @param  string $default Default value (false by default)
         * @param  int    $offset  Segment pairing offset
         *
         * @return string          URL Segment value by key or default value if not exists.
         */
        public function pair($key, $default=false, $offset=1)
        {
            if (!isset($this->urlSegmentPair[$offset]))
            {
                $prevKey = '';
                $pair = array();
                $segment = $this->urlSegment;
                unset($segment[0]);

                foreach ($segment as $val)
                {
                    if (isset($pair[$prevKey]))
                    {
                        $pair[$prevKey] = $val;
                        $prevKey = '';
                    }
                    else
                    {
                        $pair[$val] = '';
                        $prevKey = $val;
                    }
                }

                $this->urlSegmentPair[$offset] = $pair;
            }

            return isset($this->urlSegmentPair[$offset][$key]) ?
                $this->urlSegmentPair[$offset][$key] : $default;
        }

        /**
         * Get Requested URL as string
         *
         * @return string URL
         */
        public function string()
        {
            return $this->urlString;
        }
    }

?>
