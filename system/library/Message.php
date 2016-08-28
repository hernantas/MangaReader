<?php
    namespace Library;

    /**
     * For storing messages to displaying to the user later. Use \Library\Session
     * for storing the message.
     *
     * @package Library
     *
     * @see \Library\Session
     */
    class Message
    {
        private $msg = array();
        private $msgCount = 0;

        private $flashCount = array();

        /**
         * Cache session instance
         *
         * @var object
         */
        private $session = null;

        public function __construct()
        {
            $this->session = page()->load->library('Session');
            $flashes = $this->session->getAllFlash();

            foreach ($flashes as $key=>$msg)
            {
                $keys = explode('_', $key);
                if ($keys[0] === 'msg')
                {
                    $this->write($keys[1], $keys[2]);
                }
            }
        }

        /**
         * Store success message to display later.
         *
         * @param  string $msg     Message to write
         * @param  bool   $persist Set if message will persist until the next page or
         *                         only exists on the current page.
         */
        public function success($msg, $persist=false)
        {
            $this->write('success', $msg, $persist);
        }

        /**
         * Store warning message to display later.
         *
         * @param  string $msg     Message to write
         * @param  bool   $persist Set if message will persist until the next page or
         *                         only exists on the current page.
         */
        public function warning($msg, $persist=false)
        {
            $this->write('warning', $msg, $persist);
        }

        /**
         * Store error message to display later.
         *
         * @param  string $msg     Message to write
         * @param  bool   $persist Set if message will persist until the next page or
         *                         only exists on the current page.
         */
        public function error($msg, $persist=false)
        {
            $this->write('error', $msg, $persist);
        }

        /**
         * Store info message to display later.
         *
         * @param  string $msg     Message to write
         * @param  bool   $persist Set if message will persist until the next page or
         *                         only exists on the current page.
         */
        public function info($msg, $persist=false)
        {
            $this->write('info', $msg, $persist);
        }

        /**
         * Actual method to store the message regardless of the type.
         *
         * @param  string $type    Message type
         * @param  string $msg     Message to write
         * @param  bool   $persist Set if message will persist until the next page or
         *                         only exists on the current page.
         */
        private function write($type, $msg, $persist=false)
        {
            $this->msg[$type][] = $msg;
            $this->msgCount++;

            if ($persist)
            {
                $this->session->setFlash('msg_'.$type.'_'.$this->count[$type], $msg);
                $this->flashCount[$type]++;
            }
        }

        /**
         * Get message count
         *
         * @return int Total message count
         */
        public function count()
        {
            return $this->msgCount;
        }

        /**
         * Get all message as an array.
         *
         * @return array Collection of the message
         */
        public function getAsArray()
        {
            return $this->msg;
        }
    }

?>
