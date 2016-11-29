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
// Using the singleton method to register a shared component,
// the component constructor accepts an application instance as the only parameter.
$app->singleton(

    // This abstract class implements only the basic method.
    // Any request class should extends this abstract class.
    Edoger\Http\Request::class,

    // Alias name.
    // Use $app->request can get the request instance in any components.
    'request'
);

// ----------------------------------------------
// Load application helper functions.
// Use app() can get the application instance in any where.
$app->helper('application');

// ----------------------------------------------
// Return application instance.
return $app;
