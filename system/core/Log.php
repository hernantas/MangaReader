<?php
    namespace Core;

    class Log
    {
        /**
         * Config to determine if log type should be writen to the file.
         *
         * @var array
         */
        private $canWrite = array();

        /**
         * Config to determine if log type should be display to the user browser or not.
         *
         * @var array
         */
        private $canDisplay = array();

        /**
         * Resource for Log File handler
         *
         * @var Resource
         */
        private $fileHandler = null;

        /**
         * Load configuration and open log file
         */
        public function __construct()
        {
            $this->loadConfig();
            $this->open();
        }

        /**
         * Load log configuration
         */
        private function loadConfig()
        {
            $config =& loadClass('Config', 'Core');
            $cfg = $config->setDefault("Log", [
                'info' => true,
                'warning' => true,
                'error'=>true,
                'infoDisplay' => false,
                'warningDisplay' => true,
                'errorDisplay'=>true
            ]);

            $this->canWrite['info'] = $cfg['info'];
            $this->canWrite['warning'] = $cfg['warning'];
            $this->canWrite['error'] = $cfg['error'];

            $this->canDisplay['info'] = $cfg['infoDisplay'];
            $this->canDisplay['warning'] = $cfg['warningDisplay'];
            $this->canDisplay['error'] = $cfg['errorDisplay'];
        }

        /**
         * Open log file. Log name is based on y-m-d date format with extension of log.
         * Will create if not exists.
         */
        private function open()
        {
            $this->fileHandler = fopen(APP_PATH.'log/'.date('y-m-d').'.log', "a+");
            $this->write('------------------------- [New User Request] -------------------------');
        }

        private function write($message)
        {
            fwrite($this->fileHandler, '['.date('H:i:s').'] '.$message.PHP_EOL);
        }

        public function display($message, $prefix='')
        {
            echo ($prefix!==''?"<b>$prefix: </b>":'') . $message . '<br />';
        }

        public function info($message, $source='')
        {
            if ($this->canWrite['info'])
            {
                $this->write(($source!==''?$source.': ':'').$message);
            }
            if ($this->canDisplay['info'])
            {
                $this->display($message, 'Info');
            }
        }

        public function warning($message, $source='')
        {
            if ($this->canWrite['warning'])
            {
                $this->write('Warning'.($source!==''?' at '.$source:'').': '.$message);
            }
            if ($this->canDisplay['warning'])
            {
                $this->display($message, 'Warning');
            }
        }

        public function error($message, $source='')
        {
            if ($this->canWrite['error'])
            {
                $this->write('Error'.($source!==''?' at '.$source:'').': '.$message);
            }
            if ($this->canDisplay['error'])
            {
                $this->display($message, 'Error');
            }
        }
    }
?>
