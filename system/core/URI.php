<?php
    namespace Core;

    /**
     * Change user requested URL to be more easy to use.
     *
     * @package Core
     */
    class URI
    {
        /**
         * Directory name which this project is located. Relative from host url.
         *
         * @var string
         */
        private $dirname = '';

        /**
         * URL string
         *
         * @var string
         */
        private $urlString = '';

        /**
         * URL splitted into segment
         *
         * @var array
         */
        private $urlSegment = array();

        /**
         * URL splitted into segment but paired as key value pair
         *
         * @var array
         */
        private $urlSegmentPair = array();

        /**
         * Cache url origin
         *
         * @var string
         */
        private $urlOrigin = '';

        /**
         * Cache url base
         *
         * @var string
         */
        private $urlBase = '';

        /**
         * Cache full url
         *
         * @var string
         */
        private $urlFull = '';

        public function __construct()
        {
            $this->parse();

            logInfo("User request page '$this->urlString'");
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

        /**
         * Get Subdirectory where web is placed.
         *
         * @return string Subdir name
         */
        public function subdir()
        {
            return $this->dirname;
        }

        /**
         * Get origin URL (Only protocol and host name)
         *
         * @param  bool $use_forwarded_host Use forwarded host or use default host
         *
         * @return string                   Origin url
         */
        public function originUrl($use_forwarded_host = false)
        {
            if ($this->urlOrigin === '')
            {
                $ssl      = ( ! empty( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] == 'on' );
                $sp       = strtolower( $_SERVER['SERVER_PROTOCOL'] );
                $protocol = substr( $sp, 0, strpos( $sp, '/' ) ) . ( ( $ssl ) ? 's' : '' );
                $port     = $_SERVER['SERVER_PORT'];
                $port     = ( ( ! $ssl && $port=='80' ) || ( $ssl && $port=='443' ) ) ? '' : ':'.$port;
                $host     = ( $use_forwarded_host && isset( $_SERVER['HTTP_X_FORWARDED_HOST'] ) ) ? $_SERVER['HTTP_X_FORWARDED_HOST'] : ( isset( $_SERVER['HTTP_HOST'] ) ? $_SERVER['HTTP_HOST'] : null );
                $host     = isset( $host ) ? $host : $_SERVER['SERVER_NAME'] . $port;
                $this->urlOrigin = $protocol . '://' . $host;
            }
            return $this->urlOrigin;
        }

        /**
         * Get base URL
         *
         * @param  bool $use_forwarded_host Use forwarded host or use default host
         *
         * @return string                   Base url
         */
        public function baseUrl($use_forwarded_host = false)
        {
            if ($this->urlBase === '')
            {
                $this->urlBase = $this->originUrl($use_forwarded_host) . $this->dirname . '/';
            }
            return $this->urlBase;
        }

        /**
         * Get Full url (Base + Query String)
         *
         * @param  bool $use_forwarded_host Use forwarded host or use default host
         *
         * @return string                   Full url
         */
        public function fullUrl($use_forwarded_host = false)
        {
            if ($this->urlFull === '')
            {
                $this->urlFull = $this->originUrl($use_forwarded_host) . $_SERVER['REQUEST_URI'];
            }
            return $this->urlFull;
        }
    }

?>
