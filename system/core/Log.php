<?php
    namespace Core;

    class Log
    {
        private $canWrite = array();
        private $canDisplay = array();

        private $fileHandler = null;

        public function __construct()
        {
            $config =& loadClass('Config', 'Core');
            $cfg = $config->load('Log');

            if ($cfg === false)
            {
                // Generate template config
                $cfg = [
                    'info' => true,
                    'warning' => true,
                    'error'=>true,
                    'infoDisplay' => false,
                    'warningDisplay' => true,
                    'errorDisplay'=>true
                ];
                $config->save('Log', $cfg);
            }

            $this->canWrite['info'] = isset($cfg['info']) ? $cfg['info'] : true;
            $this->canWrite['warning'] = isset($cfg['warning']) ? $cfg['warning'] : true;
            $this->canWrite['error'] = isset($cfg['error']) ? $cfg['error'] : true;

            $this->canDisplay['info'] = isset($cfg['infoDisplay']) ? $cfg['infoDisplay'] : false;
            $this->canDisplay['warning'] = isset($cfg['warningDisplay']) ? $cfg['warningDisplay'] : false;
            $this->canDisplay['error'] = isset($cfg['errorDisplay']) ? $cfg['errorDisplay'] : false;

            $this->fileHandler = fopen(APP_PATH.'log/'.date('y-m-d').'.log', "a+");
            $this->write('------------------------- [New User Request] -------------------------');
        }

        private function write($message)
        {
            fwrite($this->fileHandler, '['.date('H:i:s').'] '.$message.PHP_EOL);
        }

        public function display($message, $prefix='')
        {
            echo ($prefix!==''?"<b>$prefix: </b>":'') . $message;
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
