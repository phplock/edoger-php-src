<?php
/**
 *+------------------------------------------------------------------------------------------------+
 *| Edoger PHP Framework                                                                           |
 *+------------------------------------------------------------------------------------------------+
 *| A simple and efficient PHP framework.                                                          |
 *+------------------------------------------------------------------------------------------------+
 *| @license   MIT                                                                                 |
 *| @link      https://www.edoger.com/                                                             |
 *| @copyright Copyright (c) 2014 - 2016, QingShan Luo                                             |
 *+------------------------------------------------------------------------------------------------+
 *| @author    Qingshan Luo <shanshan.lqs@gmail.com>                                               |
 *+------------------------------------------------------------------------------------------------+
 */

// ----------------------------------------------
// The root directory of the project.
define('ROOT_DIR', realpath(__DIR__ . '/../'));

// ----------------------------------------------
// Create an application instance.
$app = new Edoger\Kernel\Application(

    // Configuration manager.
    // It can be completely customized.
    new Edoger\Config\Config(

        // Loading the application configuration file,
        // the configuration file must return an array.
        require (ROOT_DIR . '/config/application.config.php')
    )
);

// ----------------------------------------------
// Build request component.
$app->singleton(
    Edoger\Foundation\Http\Request::class,
    Edoger\Http\Request::class
);

// ----------------------------------------------
$app->helper('application');

// ----------------------------------------------
return $app;
