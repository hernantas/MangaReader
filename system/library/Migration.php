<?php
    namespace Library;

    /**
     * Provide migration system (initalize, upgrade, downgrade) for
     * the web application
     */
    class Migration
    {
        public function __construct()
        {
            $config =& page()->config;
            $cfg = $config->load('Migration');

            $collections = $this->getAllMigratePath();

            $newCfg = array();
            $shutdown = false;

            // Call migration init
            foreach ($collections as $vendor=>$files)
            {
                foreach ($files as $file)
                {
                    $migrate =& loadClass($file, 'Migrate', $vendor);

                    if ($cfg === false)
                    {
                        if ($migrate->init())
                        {
                            $newCfg[$file] = "done";
                        }
                        else
                        {
                            $newCfg[$file] = "fail";
                        }
                    }
                    else if (isset($cfg['settings']['beginShutdown']) &&
                        $cfg['settings']['beginShutdown'] == "true")
                    {
                        $shutdown = true;
                        if ($migrate->shutdown())
                        {
                            $newCfg[$file] = "done";
                        }
                        else
                        {
                            $newCfg[$file] = "fail";
                        }
                    }
                    else
                    {
                        if (isset($cfg['info'][$file]) && $cfg['info'][$file] == 'done')
                        {
                            $newCfg[$file] = "done";
                        }
                        else
                        {
                            $migrate =& loadClass($file, 'Migrate', $vendor);
                            if ($migrate->init())
                            {
                                $newCfg[$file] = "done";
                            }
                            else
                            {
                                $newCfg[$file] = "fail";
                            }
                        }
                    }
                }
            }

            // Save migration process
            $config->save('Migration', [
                'settings'=>[
                    'beginShutdown'=>$shutdown=='true'?'true':'false'
                ],
                'info'=>$newCfg
            ]);

            exit();
        }

        private function getAllMigratePath()
        {
            $vnd =& page()->vendor;
            $vendors = $vnd->getList();

            $arr = array();
            foreach ($vendors as $vendor)
            {
                $newArray = $vnd->getAllFiles($vendor, 'Migrate');

                if (count($newArray) > 0)
                {
                    $arr[$vendor] = $newArray;
                }
            }
            return $arr;
        }
    }

?>
