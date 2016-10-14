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
namespace Edoger\Core;

use Edoger\Debug\Debug;
use Edoger\Log\Logger;
use Edoger\Exception\EdogerException;

final class Kernel
{
	private static $_instance = null;
	private static $_application = null;
	private static $_config = null;
	private static $_terminated = false;
	
	private function __construct()
	{

		$conf = require ROOT_PATH.'/config/edoger.config.php';
		self::$_config = new Config($conf);
	}

	public static function singleton()
	{
		if (!self::$_instance) {
			self::$_instance = new self();
			if (self::$_config->get('debug')) {
				set_error_handler([Debug::class, 'edogerErrorHandler']);
				set_exception_handler([Debug::class, 'edogerExceptionHandler']);
				register_shutdown_function([Debug::class, 'edogerFatalErrorHandler']);
			}
			Logger::setLevel(self::$_config->get('log.level'));
			Logger::useHandler(self::$_config->get('log.handler'));
		}
		
		return self::$_instance;
	}

	public function app()
	{
		if (!self::$_application) {
			self::$_application = new Application($this);
		}

		return self::$_application;
	}

	public function config()
	{
		return self::$_config;
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


			echo $this->app()->request()->userAgent().'<br/>';
			$agent = $this->app()->request()->agent();
			echo $agent->os().'<br/>';
			echo $agent->isBrowser().'<br/>';
			echo $agent->browserName().'<br/>';
			echo $agent->browserVersion().'<br/>';
			echo $agent->isMobile().'<br/>';
			echo $agent->mobileName().'<br/>';
			echo $agent->isRobot().'<br/>';
			echo $agent->robotName().'<br/>';

			echo $this->app()->request()->referrer().'<br/>';
			echo $this->app()->request()->port().'<br/>';
			echo $this->app()->request()->method().'<br/>';
			echo $this->app()->request()->scheme().'<br/>';
			echo $this->app()->request()->clientIp().'<br/>';
			echo $this->app()->request()->host().'<br/>';
			echo $this->app()->request()->isXhr().'<br/>';
		}
	}
}
