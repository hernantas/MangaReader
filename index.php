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
     * Path to System directory where framework vendor is located.
     *
     * @var string
     */
    $systemPath = 'system';

    /**
     * Path to App directory where your application vendor is located.
     *
     * @var string
     */
    $appPath = 'app';

    /**
     * Path to Public directory where your public files is located (CSS, JS, Images, etc).
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
            error_reporting(-1);
            break;

        case 'testing':
        case 'release':
            error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_STRICT & ~E_USER_NOTICE & ~E_USER_DEPRECATED);
            break;

        default:
            header('HTTP/1.1 503 Service Unavailable.', TRUE, 503);
            echo 'The application environment isn\'t correctly configured.';
            exit(1); // EXIT_ERROR
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
     * Begin Bootstraping neccessary file.
     */
    require(SYSTEM_PATH . 'core/Core.php');
?>
