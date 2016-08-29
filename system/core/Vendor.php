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
         * Cache vendor list
         *
         * @var array
         */
        private $vendor = array();

        /**
         * Cache File package vendor list
         *
         * @var array
         */
        private $vendorFiles = array();

        /**
         * Cache File package list.
         *
         * @var array
         */
        private $packFiles = array();

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
            $this->vendorFiles = $list['vendor'];
            $this->packFiles = $list['package'];

            $vendors = $this->listDirFile(BASE_PATH)['directories'];
            $this->vendors = $vendors;

            $config =& loadClass('Config', 'Core');
            $config->save('Vendor', [
                'vendors'=>$vendors,
                'vendorfile'=>$list['vendor'],
                'packagefile'=>$list['package']
            ]);
        }

        /**
         * Get list file and package for easy file search. Work recursively.
         *
         * @param  string $path Relative path to directory
         *
         * @return array        Package File list with vendor as it value
         */
        private function listFilePackage($path='')
        {
            $list = $this->listDirFile(BASE_PATH.$path);

            $ret = array('vendor'=>[], 'package'=>[]);
            foreach ($list['directories'] as $dir)
            {
                $r = $this->listFilePackage($path.$dir.'/');
                $ret['vendor'] = array_merge($ret['vendor'], $r['vendor']);
                $ret['package'] = array_merge($ret['package'], $r['package']);
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
                    $ret['vendor'][strtolower($package . $file)] = strtolower($vendor);
                    $ret['package'][$package][] = strtolower($file);
                }
            }

            return $ret;
        }

        /**
         * Get all file and directory that is valid and is not ignored.
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
                $this->vendor = $arr['vendor'];
                $this->vendorFiles = $arr['vendorfile'];
                $this->packFiles = $arr['packagefile'];
            }
        }

        /**
         * Find vendor for specific file name and it's package
         *
         * @param  string $package   Package name
         * @param  string $name      File name (name only without extension)
         * @param  string $extension File extension (default: php)
         *
         * @return string|array      Vendor name if File and package exists or
         *                           return all vendor available otherwise.
         */
        public function find($package, $name, $extension='php')
        {
            $package = strtolower($package);
            $name = strtolower($name);

            if (isset($this->vendorFiles[$package . '/' . $name . '.' . $extension]))
            {
                return $this->vendorFiles[$package . '/' . $name . '.' . $extension];
            }

            return $this->vendors;
        }

        /**
         * Get current vendor list
         *
         * @return array Vendor List
         */
        public function getList()
        {
            return $this->vendor;
        }

        /**
         * Get all files within package (Ignoring vendor)
         *
         * @param  string $package Package name
         *
         * @return array           Files in the package
         */
        public function getPackageFiles($package)
        {
            return isset($this->packFiles[$package]) ? $this->packFiles[$package] : false;
        }
    }

?>
