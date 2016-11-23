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
namespace Edoger\Core;

use Edoger\Log\Logger;
use Edoger\Debug\Debugger;
use Edoger\Route\Router;

// Framework core class, all of the available components are registered in this class.
// In fact, you rarely have access to this core class directly.
final class Kernel
{
	private static $_instance		= null;
	private static $_application	= null;
	private static $_config			= null;
	private static $_logger			= null;
	private static $_debugger		= null;
	private static $_router			= null;
	private static $_extend			= null;
	private static $_terminated		= false;
	
	private function __construct()
	{
		self::$_config		= new Config();
		self::$_logger		= new Logger();
		self::$_debugger	= new Debugger();
	}

	public static function singleton()
	{
		if (!self::$_instance) {
			self::$_instance = new self();
		}
		
		return self::$_instance;
	}

	public function app()
	{
		if (!self::$_application) {
			self::$_application = new Application();
		}

		return self::$_application;
	}

	public function config()
	{
		return self::$_config;
	}

	public function extend()
	{
		if (!self::$_extend) {
			self::$_extend = new Extend();
		}

		return self::$_extend;
	}

	public function debugger()
	{
		return self::$_debugger;
	}

	public function logger()
	{
		return self::$_logger;
	}

	public function router()
	{
		if (!self::$_router) {
			self::$_router = new Router();
		}
		return self::$_router;
	}

	public function error($exception)
	{
		if (self::$_application) {
			self::$_application->error($exception);
		}
		return $this;
	}

	public function termination()
	{
		if (!self::$_terminated) {
			self::$_terminated = true;

			echo implode($this->app()->response()->getOutput());
		}
	}
}
