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

// The bootstrap script for application.
// You can start the session or connect to the database and other initialization action.
// But you shouldn't add business logic here.

$kernel = Edoger\Core\Kernel::singleton();

// Set log handler.
// System has achieved several basic log processing program, you can directly use, 
// please refer to the configuration file before use.
// If you need to implement your own log handler, refer to the relevant documentation for help.
$loggerHandler = new Edoger\Log\Handler\File();
$loggerHandler->init([
	'dir'		=> ROOT_PATH.'/data/logs',
	'format'	=> 'Ymd',
	'ext'		=> 'log'
	]);

Edoger\Log\Logger::useHandler($loggerHandler);


$sessionId = $kernel->app()->request()->cookie()->get('EDOGER_SID', '');
$sessionHandler = new Edoger\Http\Session\Handler\Apcu($kernel->config()->get('session_timeout'));

$kernel->app()->request()->session()->start($sessionId, $sessionHandler);