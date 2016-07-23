<?php

    require (SYSTEM_PATH . 'core/Common.php');

    $config =& loadClass('Config', 'Core');
    $vendor =& loadClass('Vendor', 'Core');
    $uri =& loadClass('URI', 'Core');
    $router =& loadClass('Router', 'Core');

    $db =& loadClass('DB', 'db');

    $router->routing();
    
?>
