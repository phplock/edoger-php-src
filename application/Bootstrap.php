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

use Edoger\Common\BootstrapInterface;
use Edoger\Core\Kernel;
use Edoger\Log\Logger;
use Edoger\Log\Handler\File as EdogerLoggerHandler;
use Edoger\Http\Session\Handler\File as EdogerSessionHandler;

// The bootstrap class for application.
// The initialization of the application will be done here.

class Bootstrap implements BootstrapInterface
{
	public function initDebug(Kernel $kernel)
	{
		$kernel->debugger()->register();
	}

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

	public function initSession(Kernel $kernel)
	{
		$sid = $kernel->app()->request()->cookie()->get('EDOGER_SID', '');
		$handler = new EdogerSessionHandler([
			'timeout' => 86400,
			'dir' => ROOT_PATH.'/data/session'
			]);
		$session = $kernel->app()->request()->session();
		$session->start($sid, $handler);
		$kernel->app()->response()->cookie()->secure('EDOGER_SID', $session->sessionId());
	}
}
