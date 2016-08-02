<?php
/**
 * Edoger PHP Framework (EdogerPHP)
 * 
 * A simple and efficient PHP framework.
 *
 * By REENT (Qingshan Luo)
 * Version 1.0.0
 *
 * http://www.edoger.com/
 *
 * The MIT License (MIT)
 * Copyright (c) 2016 REENT (Qingshan Luo)
 * Permission is hereby granted, free of charge, to any person obtaining a 
 * copy of this software and associated documentation files (the “Software”), 
 * to deal in the Software without restriction, including without limitation 
 * the rights to use, copy, modify, merge, publish, distribute, sublicense, 
 * and/or sell copies of the Software, and to permit persons to whom the 
 * Software is furnished to do so, subject to the following conditions:
 * 
 * The above copyright notice and this permission notice shall be included in 
 * all copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED “AS IS”, WITHOUT WARRANTY OF ANY KIND, 
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF 
 * MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. 
 * IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, 
 * DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR 
 * OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE 
 * USE OR OTHER DEALINGS IN THE SOFTWARE.
 */
namespace Edoger\Core;

use Edoger\Core\Log\Logger;
use Edoger\Core\Http\Request;
use Edoger\Core\Http\Respond;
use Edoger\Exceptions\RuntimeException;

/**
 * ================================================================================
 * 框架的核心类，所有的组件都注册在这个类的实例中
 * ================================================================================
 */
final class Kernel
{
	/**
	 * ----------------------------------------------------------------------------
	 * 站点根目录的绝对路径
	 * ----------------------------------------------------------------------------
	 *
	 * @var string
	 */
	private static $root;

	/**
	 * ----------------------------------------------------------------------------
	 * 框架配置管理器实例
	 * ----------------------------------------------------------------------------
	 *
	 * @var Edoger\Core\Config
	 */
	private static $config;

	/**
	 * ----------------------------------------------------------------------------
	 * 应用程序实例
	 * ----------------------------------------------------------------------------
	 *
	 * @var Edoger\Core\Application
	 */
	private static $application;

	/**
	 * ----------------------------------------------------------------------------
	 * 系统错误调试管理器
	 * ----------------------------------------------------------------------------
	 *
	 * @var Edoger\Core\Debug
	 */
	private static $debug;

	/**
	 * ----------------------------------------------------------------------------
	 * 系统日志记录器
	 * ----------------------------------------------------------------------------
	 *
	 * @var Edoger\Core\Debug
	 */
	private static $logger;

	/**
	 * ----------------------------------------------------------------------------
	 * 系统日志记录器
	 * ----------------------------------------------------------------------------
	 *
	 * @var Edoger\Core\Debug
	 */
	private static $request;

	/**
	 * ----------------------------------------------------------------------------
	 * 系统日志记录器
	 * ----------------------------------------------------------------------------
	 *
	 * @var Edoger\Core\Debug
	 */
	private static $respond;

	/**
	 * ----------------------------------------------------------------------------
	 * 获取框架的版本字符串
	 * ----------------------------------------------------------------------------
	 * 
	 * @return string
	 */
	public static function version()
	{
		return '1.0.0';
	}

	/**
	 * ----------------------------------------------------------------------------
	 * 初始化核心对象，装载系统配置管理器
	 * ----------------------------------------------------------------------------
	 * 
	 * @return void
	 */
	private function __construct()
	{
		//	计算并绑定站点根目录
		self::$root = dirname(dirname(__DIR__));

		$file = self::$root . '/config/edoger.php';
		if (file_exists($file)) {
			$configuration = require $file;
		} else {
			$this -> triggerError(
				"The edoger configuration file {$file} does not exist", 5001
				);
		}
		
		//	绑定框架配置管理器实例
		self::$config = new Config($configuration);
	}

	/**
	 * ----------------------------------------------------------------------------
	 * 创建并返回全局唯一的核心类的实例，核心对象只会创建一次
	 * ----------------------------------------------------------------------------
	 * 
	 * @return Edoger\Core\Kernel
	 */
	public static function core()
	{
		static $kernel = null;

		if (is_null($kernel)) {

			//	创建并绑定错误调试管理器
			self::$debug = new Debug();

			//	创建核心对象
			$kernel = new self();

			//	创建并绑定系统日志记录器
			//	系统日志通道名称为 "EDOGER"，请不要重复使用
			self::$logger = new Logger('EDOGER');

			$handler 	= ucfirst(strtolower(self::$config -> get('log_handler')));
			$class 		= "\\Edoger\\Core\\Log\\Handlers\\{$handler}Handler";

			if (!class_exists($class, true)) {
				$this -> triggerError(
					"The Logger Handler {$calss} is not found", 5001
					);
			}

			//	日志处理的级别映射表
			//	系统仅能识别和使用定义的8个日志级别
			//	默认情况下使用 "error" 级别
			$map = [
				'debug' 	=> Logger::LEVEL_DEBUG,
				'info' 		=> Logger::LEVEL_INFO,
				'notice' 	=> Logger::LEVEL_NOTICE,
				'warning' 	=> Logger::LEVEL_WARNING,
				'error' 	=> Logger::LEVEL_ERROR,
				'critical' 	=> Logger::LEVEL_CRITICAL,
				'alert' 	=> Logger::LEVEL_ALERT,
				'emergenc' 	=> Logger::LEVEL_EMERGENC
			];

			$level = strtolower(self::$config -> get('log_level'));

			//	添加日志处理程序
			//	只有在添加了日志处理程序之后，日志记录器才能发送日志
			self::$log -> setHandler(new $calss($map[$level] ?? Logger::LEVEL_ERROR));

			//	绑定日志记录器到错误调试管理器
			//	在此之前，如果发生错误，不会被捕获，因为只有绑定了日志记录器才会启动
			//	错误捕获
			self::$debug -> bindLogger(self::$log);
			
			//	创建基础的请求组件和响应组件
			self::$request = new Request($this);
			self::$respond = new Respond($this);

			//	绑定响应钩子程序到错误调试管理器
			//	一旦发生致命错误，可以保证有相应的数据输出
			self::$debug -> bindHook(self::$respond -> getHook());
		}
		return $kernel;
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
	public function triggerError(string $message, int $code = 5000)
	{
		throw new RuntimeException($message, $code);
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
			return self::$root . '/' . ltrim($uri, '/');
		} else {
			return self::$root;
		}
	}

	/**
	 * ----------------------------------------------------------------------------
	 * 创建应用程序实例
	 * ----------------------------------------------------------------------------
	 *
	 * @param  string 	$fileName 	应用程序配置文件的名称
	 * @return Edoger\Core\Kernel
	 */
	public function create(string $fileName = '')
	{
		//	默认情况下，使用 "application" 作为配置文件名称
		if (!$fileName) {
			$fileName = 'application';
		}

		$file = self::$root . '/config/' . $fileName . '.php';

		if (!file_exists($file)) {
			$this -> triggerError(
				"The application configuration file {$file} does not exist", 5001
				);
		}

		$configuration = require $file;

		//	创建应用程序
		//	同时创建应用程序配置管理器
		self::$application = new Application($this, new Config($configuration));

		return $this;
	}

	/**
	 * ----------------------------------------------------------------------------
	 * 获取应用程序实例，在没有创建应用程序之前，返回 NULL
	 * ----------------------------------------------------------------------------
	 *
	 * @return Edoger\Core\Application
	 */
	public function app()
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
	 * 获取框架提供的请求管理组件的实例
	 * ----------------------------------------------------------------------------
	 *
	 * @return Edoger\Core\Http\Request
	 */
	public function request()
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
	public function respond()
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
	public function library()
	{
		static $library = null;

		if (is_null($library)) {
			$library = new Library($this);
		}

		return $library;
	}
}
