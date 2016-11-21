<?php
/**
 *+------------------------------------------------------------------------------------------------+
 *| Edoger PHP Framework                                                                           |
 *+------------------------------------------------------------------------------------------------+
 *| A simple and efficient PHP framework.                                                          |
 *+------------------------------------------------------------------------------------------------+
 *| @package   edoger-php-src                                                                      |
 *| @license   MIT                                                                                 |
 *| @link      https://www.edoger.com/                                                             |
 *| @copyright Copyright (c) 2014 - 2016, QingShan Luo                                             |
 *+------------------------------------------------------------------------------------------------+
 *| @author    Qingshan Luo <shanshan.lqs@gmail.com>                                               |
 *+------------------------------------------------------------------------------------------------+
 */

// Create an application instance.
// Initialize the system environment.
$app = require __DIR__ . '/../edoegr/launcher.php';

// Create this request object.
$request = $app->make(
    Edoger\Http\Request::class
);

// Capture response and output to the client.
$response = $app->capture($request);
$response->flush();

// Recycle resources and trigger follow-up tasks.
$app->end($request, $response);
