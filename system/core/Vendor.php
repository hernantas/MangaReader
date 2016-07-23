<?php
    namespace Core;

    /**
     * Manage vendor and can automatically detect vendor.
     *
     * @package Core
     */
    class Vendor
    {
        /**
         * Cache File package vendor list
         *
         * @var array
         */
        private $packfile = array();

        /**
         * List of name that will be ignored when generating vendor configuration.
         *
         * @var array
         */
        private $ignoreName = array('.', '..', '.git');

        public function __construct()
        {
            if (ENVIRONMENT == 'development')
            {
                $this->generateConfig();
            }
            else
            {
                $this->loadConfig();
            }
        }

        /**
         * Generate Config that contain vendor list
         */
        private function generateConfig()
        {
            $list = $this->listFilePackage();
            $this->packfile = $list;

            $config =& loadClass('Config', 'Core');
            $config->save('Vendor', $list);
        }

        /**
         * List file and it's package paired with vendor name it's placed
         *
         * @param  string $path Relative path to list
         *
         * @return array       Package File list with vendor as it value
         */
        private function listFilePackage($path='')
        {
            $list = $this->listDirFile(BASE_PATH.$path);

            $ret = array();
            foreach ($list['directories'] as $dir)
            {
                $r = $this->listFilePackage($path.$dir.'/');
                $ret = array_merge($ret, $r);
            }

            foreach ($list['files'] as $file)
            {
                $info = pathinfo($path . $file);

                $pos = strpos($path, '/');
                $package = substr($path, $pos+1);
                $vendor = substr($path, 0, $pos);

                // $file = $info['filename'];

                if ($file !== '')
                {
                    $ret[strtolower($package . $file)] = strtolower($vendor);
                }
            }

            return $ret;
        }

        /**
         * Get all file and directory that is valid and not ignored.
         *
         * @param  string $path Path of directory
         *
         * @return array        List of directory and files
         */
        private function listDirFile($path)
        {
            $lists = scandir($path);
            $dirs = array();
            $files = array();

            foreach ($lists as $list)
            {
                if (!$this->checkIgnoredName($list))
                {
                    if (is_dir($path.$list))
                    {
                        $dirs[] = $list;
                    }
                    else
                    {
                        $files[] = $list;
                    }
                }
            }

            return [
                'directories' => $dirs,
                'files' => $files
            ];
        }

        /**
         * Check if name is in the ignore list or not. Not case sensitive.
         *
         * @param  string $name Name to check
         *
         * @return bool         True if in ignore list, false otherwise.
         */
        private function checkIgnoredName($name)
        {
            foreach ($this->ignoreName as $ignore)
            {
                if (strtolower($ignore) == strtolower($name))
                {
                    return true;
                }
            }
            return false;
        }

        /**
         * Load vendor list from config
         */
        private function loadConfig()
        {
            $config =& loadClass('Config', 'Core');
            $arr = $config->load('Vendor');

            if ($arr !== false)
            {
                $this->packfile = $arr;
            }
        }

        /**
         * Find vendor for specific file name and it's package
         *
         * @param  string $package   Package name
         * @param  string $name      File name (name only without extension)
         * @param  string $extension File extension (default: php)
         *
         * @return string|bool       Vendor name if File and package exists or false otherwise.
         */
        public function findVendor($package, $name, $extension='php')
        {
            $package = strtolower($package);
            $name = strtolower($name);

            if (isset($this->packfile[$package . '/' . $name . '.' . $extension]))
            {
                return $this->packfile[$package . '/' . $name . '.' . $extension];
            }

            return false;
        }
    }

?>
