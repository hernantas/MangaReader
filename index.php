<?php

    /**
     * PHP Code Enhancement Framework
     *
     * PHP Code Enhancement or PCE in short is small project (part of Code Enhancement
     * project) created to help making web application using PHP easier and more
     * efficient by writing less. PCE purpose is to provide class and method so
     * developer will write less code, manage their code better and can share code
     * with other easily. PCE will be modular as much as possible by splitting project
     * or package into it own directory and can be integrated into website application
     * easily.
     *
     * @copyright Hernantas 2016
     * @license MIT
     * @link http://www.hernantas.com
     */

    /**
     * Core Configuration
     *
     * BECAREFUL WHEN MODIFYING BELLOW THIS LINE.
     *
     * This is core configuration to determine basic behaviour such as environment
     * settings, framework location and default app location. Invalid configuration
     * will break the project.
     */

    /**
     * Project environment settings.
     *
     * @var string
     */
    $environment = 'development';

    /**
     * Path to System directory where framework is located.
     *
     * @var string
     */
    $systemPath = 'system';

    /**
     * Path to App directory where your application is located.
     *
     * @var string
     */
    $appPath = 'app';

    /**
     * Path to Public directory where your public file is located (CSS, JS, Images, etc).
     *
     * @var string
     */
    $publicPath = 'public';

    /**
     * STOP, DO NOT MODIFY BELLOW THIS LINE.
     *
     * This is the end of core configuration.
     */

    /**
     * Set PHP error reporting based on environment settings.
     */
    switch ($environment)
    {
        case 'development':
        case 'testing':
            error_reporting(E_ALL);
            break;

        case 'release':
        default:
            error_reporting(0);
            break;
    }

    /**
     * Clean and fix path variable.
     */
    $systemPath = rtrim(str_replace('\\', '/', $systemPath),'/') . '/';
    $appPath = rtrim(str_replace('\\', '/', $appPath),'/') . '/';
    $publicPath = rtrim(str_replace('\\', '/', $publicPath),'/') . '/';

    /**
     * GLOBAL CONSTANT
     *
     * Define global constant variable for later use.
     */
    define('ENVIRONMENT', $environment);
    define('BASE_PATH', rtrim(str_replace('\\', '/', dirname(__FILE__)),'/') . '/');
    define('SYSTEM_PATH', $systemPath);
    define('APP_PATH', $appPath);
    define('PUBLIC_PATH', $publicPath);

    /**
     * Begin Bootstraping necessary file.
     */
    include(SYSTEM_PATH . 'core/Core.php');
?>
