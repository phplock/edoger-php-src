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
 *| @version   1.0.0 Alpha                                                                         |
 *+------------------------------------------------------------------------------------------------+
 *| @author    Qingshan Luo <shanshan.lqs@gmail.com>                                               |
 *+------------------------------------------------------------------------------------------------+
 */

// Basic constants.
define('ROOT_PATH', dirname(__DIR__));
define('EDOGER_PATH', ROOT_PATH.'/edoger');
define('APP_PATH', ROOT_PATH.'/application');

// Load automatic loader.
require EDOGER_PATH.'/autoload.php';

// Load startup script.
// Create and get an instance of the core object of the framework.
$kernel = require EDOGER_PATH.'/launcher.php';

// Create an application instance and start the application instance immediately.
$kernel->app()->bootstrap()->run();

// End.
$kernel->termination();
