<?php

    require (SYSTEM_PATH . 'core/Common.php');

    $config =& loadClass('Config', 'Core');
    $log =& loadClass('Log', 'Core');
    $vendor =& loadClass('Vendor', 'Core');

    $input =& loadClass('Input', 'Core');
    $uri =& loadClass('URI', 'Core');

    $hook =& loadClass('Hook', 'Core');
    $loader =& loadClass('Loader', 'Core');
    $router =& loadClass('Router', 'Core');

    $page =& loadClass('Page', 'Core');
    $curPage =& $page->getInstance();
    $method = $router->method;

    $db =& loadClass('DB', 'db');

    $loader->autoload();
    $curPage->$method();
?>
