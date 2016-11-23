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
// Load automatic loader.
// By default, the use of system loader.
// If need to use custom loader, please add 'Edoger' and 'App' namespace class loading rules.
// We use PSR-4 specification.
require __DIR__ . '/../bootstrap/autoload.php';

// ----------------------------------------------
// The startup script will create a basic application instance.
// Application program to expand its own through the bootstrap.
// The initial application instance only binds the request instance.
$app = require __DIR__ . '/../bootstrap/launcher.php';

// ----------------------------------------------
// Extended application instance.
// This is an optional call, if not done, the application appears to be very monotonous.
// It is worth noting that you should not handle any business logic in the bootstrap,
// and we should keep the code clean and tidy.
$app->bootstrap();

// ----------------------------------------------
// Handle the request, and create the response.
// Response will collect all the output data, and make the necessary adjustments.
$response = $app->capture(

    // Standard response class.
    // It's completely customizable.
    App\Http\Response::class,

    $app->request
);

// ----------------------------------------------
// Send all data to client.
$response->flush();

// ----------------------------------------------
// Perform some follow-up work.
$app->end($response);
