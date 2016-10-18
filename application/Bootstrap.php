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
namespace App;

use Edoger\Core\Kernel;
use Edoger\Log\Logger;
use Edoger\Log\Handler\File as EdogerLoggerHandler;
use Edoger\Http\Session\Handler\File as EdogerSessionHandler;

// The bootstrap class for application.
// The initialization of the application will be done here.
// Here, you can't get the information about the route, because the route hasn't started yet.

class Bootstrap
{
	// Open global auto error capture.
	// If it is in the development environment, you can remove this function, 
	// while the error will be exported to the browser.
	public function initDebug(Kernel $kernel)
	{
		$kernel->debugger()->register();
	}

	// Set the log handler for the log module.
	// If you do not set, then the log will not be persistent, 
	// they will be cleared after the completion of the request.
	public function initLogger(Kernel $kernel)
	{
		$handler = new EdogerLoggerHandler([
			'dir'		=> ROOT_PATH.'/data/logs',
			'format'	=> 'Ymd',
			'ext'		=> 'log'
			]);
		$kernel->logger()->setLevel(EDOGER_LEVEL_DEBUG);
		$kernel->logger()->setHandler($handler);
	}

	// Set user session.
	// We get the session ID through cookie, you can change this behavior.
	public function initSession(Kernel $kernel)
	{
		$sid = $kernel->app()->request()->cookie()->get('EDOGER_SID', '');
		$handler = new EdogerSessionHandler([
			'timeout'	=> 86400,
			'dir'		=> ROOT_PATH.'/data/session'
			]);
		$session = $kernel->app()->request()->session();
		$session->start($sid, $handler);
		$kernel->app()->response()->cookie()->secure('EDOGER_SID', $session->sessionId());
	}

	
}
