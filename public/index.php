<?php
/**
 *+------------------------------------------------------------------------------------------------+
 *| Edoger PHP Framework                                                                           |
 *+------------------------------------------------------------------------------------------------+
 *| A simple route analysis and matching module.                                                   |
 *+------------------------------------------------------------------------------------------------+
 *| @package   edoger-php-src                                                                      |
 *| @license   MIT                                                                                 |
 *| @link      https://www.edoger.com/                                                             |
 *| @copyright Copyright (c) 2014 - 2016, QingShan Luo                                             |
 *| @version   1.0.0 Alpha                                                                         |
 *+------------------------------------------------------------------------------------------------+
 *| @author    Qingshan Luo <shanshan.lqs@gmail.com>                                               |
 *+------------------------------------------------------------------------------------------------+
 */
define('ROOT_PATH', dirname(__DIR__));
define('EDOGER_PATH', ROOT_PATH.'/edoger');
define('APP_PATH', ROOT_PATH.'/application');

// Load automatic loader.
require EDOGER_PATH.'/autoload.php';

// Load startup script.
$kernel = require EDOGER_PATH.'/launcher.php';

// Create an application instance.
$app = $kernel->app();

// Start and run the application.
$app->bootstrap()->run();

$kernel->termination();
