<?php
/**
 *+----------------------------------------------------------------------------+
 *| Edoger PHP Framework (Edoger)                                              |
 *+----------------------------------------------------------------------------+
 *| Copyright (c) 2014 - 2016 QingShan Luo (Reent)                             |
 *+----------------------------------------------------------------------------+
 *| The MIT License (MIT)                                                      |
 *|                                                                            |
 *| Permission is hereby granted, free of charge, to any person obtaining a    |
 *| copy of this software and associated documentation files (the “Software”), |
 *| to deal in the Software without restriction, including without limitation  |
 *| the rights to use, copy, modify, merge, publish, distribute, sublicense,   |
 *| and/or sell copies of the Software, and to permit persons to whom the      |
 *| Software is furnished to do so, subject to the following conditions:       |
 *|                                                                            |
 *| The above copyright notice and this permission notice shall be included in |
 *| all copies or substantial portions of the Software.                        |
 *|                                                                            |
 *| THE SOFTWARE IS PROVIDED “AS IS”, WITHOUT WARRANTY OF ANY KIND,            |
 *| EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF         |
 *| MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.     |
 *| IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM,|
 *| DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR      |
 *| OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE  |
 *| USE OR OTHER DEALINGS IN THE SOFTWARE.                                     |
 *+----------------------------------------------------------------------------+
 *| License: MIT                                                               |
 *+----------------------------------------------------------------------------+
 *| Authors: QingShan Luo <shanshan.lqs@gmail.com>                             |
 *+----------------------------------------------------------------------------+
 *| Link: https://www.edoger.com/                                              |
 *+----------------------------------------------------------------------------+
 */
namespace Edoger\Core;

use Closure;
use Edoger\Core\Log\Logger;
use Edoger\Core\Http\Request;
use Edoger\Core\Http\Respond;
use Edoger\Core\Route\Routing

/**
 * ================================================================================
 * 框架的核心类，所有的组件都注册在这个类的实例中
 * ================================================================================
 */
final class Kernel
{
	private static $root;

	private static $config;
	private static $application;
	private static $debug;
	private static $logger;
	private static $request;
	private static $respond;
	private static $routing;

	private static $sendData = [];
	private static $sendOptions = [];

	private static $routeParams = [];
	private static $routeCommon = [];
	private static $routePath = '';



	/**
	 * ----------------------------------------------------------------------------
	 * 获取框架的版本字符串
	 * ----------------------------------------------------------------------------
	 * 
	 * @return string
	 */
	public static function version($getInteger = false)
	{
		return $getInteger ? EDOGER_VERSION_INTEGER : EDOGER_VERSION;
	}

	/**
	 * ----------------------------------------------------------------------------
	 * 初始化核心对象，装载系统配置管理器
	 * ----------------------------------------------------------------------------
	 * 
	 * @return void
	 */
	public function __construct(Config $config)
	{
		//	计算并绑定站点根目录
		self::$root 	= dirname(EDOGER_ROOT);
		self::$config 	= $config;

		Debug::

		set_error_handler([Debug::class, 'edogerErrorHandler']);
		set_exception_handler([Debug::class, 'edogerExceptionHandler']);
		register_shutdown_function([Debug::class, 'edogerFatalErrorHandler']);

		switch (strtolower($config -> get('log.level'))) {
			case 'debug':		Logger::setLevel(Logger::LEVEL_DEBUG);		break;
			case 'info':		Logger::setLevel(Logger::LEVEL_INFO);		break;
			case 'notice':		Logger::setLevel(Logger::LEVEL_NOTICE);		break;
			case 'warning':		Logger::setLevel(Logger::LEVEL_WARNING);	break;
			case 'error':		Logger::setLevel(Logger::LEVEL_ERROR);		break;
			case 'critical':	Logger::setLevel(Logger::LEVEL_CRITICAL);	break;
			case 'alert':		Logger::setLevel(Logger::LEVEL_ALERT);		break;
			case 'emergenc':	Logger::setLevel(Logger::LEVEL_EMERGENCY);	break;
		}
		
		$logHandlerName 	= strtolower($config -> get('log.handler', 'file'));
		$logHandlerConfig 	= $config -> get('log.handlers.' . $logHandlerName, []);

		Logger::useHandler($logHandlerName, $logHandlerConfig);




		//	创建请求组和响应组件实例
		self::$request = new Request();
		self::$respond = new Respond();

		

		//	设置触发日志记录的最低级别
		switch (strtolower(self::$config -> get('log_level'))) {

			case 'debug':		$level = Logger::LEVEL_DEBUG;		break;
			case 'info':		$level = Logger::LEVEL_INFO;		break;
			case 'notice':		$level = Logger::LEVEL_NOTICE;		break;
			case 'warning':		$level = Logger::LEVEL_WARNING;		break;
			case 'error':		$level = Logger::LEVEL_ERROR;		break;
			case 'critical':	$level = Logger::LEVEL_CRITICAL;	break;
			case 'alert':		$level = Logger::LEVEL_ALERT;		break;
			case 'emergenc':	$level = Logger::LEVEL_EMERGENCY;	break;
			
			default:

				//	系统默认最低记录级别为：错误
				$level = Logger::LEVEL_ERROR;
				break;
		}

		//	配置日志记录器的记录程序
		self::$logger -> useHandler(self::$config -> get('log_handler'), $level);

		$app -> make(self::$logger);

		//	创建系统的全局错误捕获与管理器
		self::$debug = new Debug(self::$logger);

		$app -> make(self::$debug);

		self::$routing = new Routing(self::$request);

		$app -> make(self::$routing);
	}

	/**
	 * ----------------------------------------------------------------------------
	 * 创建并返回全局唯一的核心类的实例，核心对象只会创建一次
	 * ----------------------------------------------------------------------------
	 * 
	 * @return Edoger\Core\Kernel
	 */
	public static function flush()
	{
		static $sent = false;

		if ($sent) {
			return;
		}

		$sent = true;

	}

	/**
	 * ----------------------------------------------------------------------------
	 * 触发系统错误，立即结束程序的运行
	 * ----------------------------------------------------------------------------
	 * 
	 * @param  string  	$message 	错误消息
	 * @param  integer 	$code    	错误代码
	 * @return void
	 */
	public static function quit(int $status = 0, bool $clean = false)
	{
		if ($clean) {
			self::$respond -> clean();
		}

		if ($status > 0) {
			self::$respond -> status($status);
		}
		
		self::send();
		exit(0);
	}

	/**
	 * ----------------------------------------------------------------------------
	 * 获取站点的根目录，或者创建基于根目录的绝对路径
	 * ----------------------------------------------------------------------------
	 *
	 * @param  string 	$uri 	需要拼接到根目录的子路径
	 * @return void
	 */
	public function root(string $uri = '')
	{
		if ($uri) {
			return self::$root . '/' . trim($uri, '/');
		} else {
			return self::$root;
		}
	}


	/**
	 * ----------------------------------------------------------------------------
	 * 获取应用程序实例，在没有创建应用程序之前，返回 NULL
	 * ----------------------------------------------------------------------------
	 *
	 * @return Edoger\Core\Application
	 */
	public static function application()
	{
		return self::$application;
	}

	/**
	 * ----------------------------------------------------------------------------
	 * 获取框架的配置管理器，这个方法一般是由内部组件调用
	 * ----------------------------------------------------------------------------
	 *
	 * @return Edoger\Core\Config
	 */
	public function config()
	{
		return self::$config;
	}

	/**
	 * ----------------------------------------------------------------------------
	 * 获取错误调试管理器
	 * ----------------------------------------------------------------------------
	 * 
	 * @return Edoger\Core\Debug
	 */
	public function debug()
	{
		return self::$debug;
	}

	/**
	 * ----------------------------------------------------------------------------
	 * 获取框架提供的请求管理组件的实例
	 * ----------------------------------------------------------------------------
	 *
	 * @return Edoger\Core\Http\Request
	 */
	public static function request()
	{
		return self::$request;
	}

	/**
	 * ----------------------------------------------------------------------------
	 * 获取框架提供的响应管理组件的实例
	 * ----------------------------------------------------------------------------
	 *
	 * @return Edoger\Core\Http\Respond
	 */
	public static function respond()
	{
		return self::$respond;
	}

	/**
	 * ----------------------------------------------------------------------------
	 * 创建并返回系统的扩展库管理器实例
	 * ----------------------------------------------------------------------------
	 * 
	 * @return Edoger\Core\Library
	 */
	public static function library()
	{
		static $library = null;

		if (is_null($library)) {
			$library = new Library($this);
		}

		return $library;
	}
}
