<?php
/*
 +-----------------------------------------------------------------------------+
 | Edoger PHP Framework (EdogerPHP)                                            |
 +-----------------------------------------------------------------------------+
 | Copyright (c) 2014 - 2016 QingShan Luo                                      |
 +-----------------------------------------------------------------------------+
 | The MIT License (MIT)                                                       |
 |                                                                             |
 | Permission is hereby granted, free of charge, to any person obtaining a     |
 | copy of this software and associated documentation files (the “Software”),  |
 | to deal in the Software without restriction, including without limitation   |
 | the rights to use, copy, modify, merge, publish, distribute, sublicense,    |
 | and/or sell copies of the Software, and to permit persons to whom the       |
 | Software is furnished to do so, subject to the following conditions:        |
 |                                                                             |
 | The above copyright notice and this permission notice shall be included in  |
 | all copies or substantial portions of the Software.                         |
 |                                                                             |
 | THE SOFTWARE IS PROVIDED “AS IS”, WITHOUT WARRANTY OF ANY KIND,             |
 | EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF          |
 | MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.      |
 | IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, |
 | DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR       |
 | OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE   |
 | USE OR OTHER DEALINGS IN THE SOFTWARE.                                      |
 +-----------------------------------------------------------------------------+
 |  License: MIT                                                               |
 +-----------------------------------------------------------------------------+
 |  Authors: QingShan Luo <shanshan.lqs@gmail.com>                             |
 +-----------------------------------------------------------------------------+
 */
namespace Edoger\Core;

use ErrorException;
use Edoger\Core\Log\Logger;
use Edoger\Interfaces\EdogerExceptionInterface;


/**
 * =============================================================================
 * 系统错误和异常管理组件
 *
 * 这个组件用于捕获全局所有的错误和异常，同时提供友好的处理方式。这个组件依赖系
 * 统的日志记录器。
 * =============================================================================
 */
final class Debug
{
	/**
	 * -------------------------------------------------------------------------
	 * 核心对象的引用
	 * -------------------------------------------------------------------------
	 *
	 * @var Edoger\Core\Kernel
	 */
	private static $kernel;

	/**
	 * -------------------------------------------------------------------------
	 * 绑定的钩子程序，这些程序将在系统发生异常时被执行，通常用于处理默认的响应
	 * -------------------------------------------------------------------------
	 *
	 * @var array
	 */
	private static $hooks = [];

	/**
	 * -------------------------------------------------------------------------
	 * 绑定的日志记录器，如果没有绑定，捕获的错误将记录组件自身缓存
	 * -------------------------------------------------------------------------
	 *
	 * @var Edoger\Core\Log\Logger
	 */
	private static $logger = null;

	/**
	 * -------------------------------------------------------------------------
	 * 捕获的错误日志缓存，这在绑定日志记录器之后会自动写入日志
	 * -------------------------------------------------------------------------
	 *
	 * @var array
	 */
	private static $logCache = [];

	/**
	 * -------------------------------------------------------------------------
	 * PHP错误级别到日志级别的映射数组
	 * -------------------------------------------------------------------------
	 *
	 * @var array
	 */
	private static $map = [
		E_ERROR             => Logger::LEVEL_CRITICAL,
		E_WARNING           => Logger::LEVEL_WARNING,
		E_PARSE             => Logger::LEVEL_ALERT,
		E_NOTICE            => Logger::LEVEL_NOTICE,
		E_CORE_ERROR        => Logger::LEVEL_CRITICAL,
		E_CORE_WARNING      => Logger::LEVEL_WARNING,
		E_COMPILE_ERROR     => Logger::LEVEL_ALERT,
		E_COMPILE_WARNING   => Logger::LEVEL_WARNING,
		E_USER_ERROR        => Logger::LEVEL_ERROR,
		E_USER_WARNING      => Logger::LEVEL_WARNING,
		E_USER_NOTICE       => Logger::LEVEL_NOTICE,
		E_STRICT            => Logger::LEVEL_NOTICE,
		E_RECOVERABLE_ERROR => Logger::LEVEL_ERROR,
		E_DEPRECATED        => Logger::LEVEL_NOTICE,
		E_USER_DEPRECATED   => Logger::LEVEL_NOTICE
	];

	/**
	 * -------------------------------------------------------------------------
	 * [bindLogger description]
	 * -------------------------------------------------------------------------
	 * 
	 * @param  Logger $logger [description]
	 * @return [type]         [description]
	 */
	public static function bindLogger(Logger $logger)
	{
		self::$logger = $logger;
		
		if (!empty(self::$logCache)) {
			foreach (self::$logCache as $value) {
				$logger -> log($value[0], $value[1]);
			}
			self::$logCache = [];
		}
		return true;
	}

	/**
	 * -------------------------------------------------------------------------
	 * [registerErrorHandler description]
	 * -------------------------------------------------------------------------
	 * 
	 * @return [type] [description]
	 */
	public static function registerErrorHandler()
	{
		static $registered = false;
		if (!$registered) {
			$registered = true;
			set_error_handler([__CLASS__, '_ErrorHandler']);
		}
		return $registered;
	}

	/**
	 * -------------------------------------------------------------------------
	 * [registerExceptionHandler description]
	 * -------------------------------------------------------------------------
	 * 
	 * @return [type] [description]
	 */
	public static function registerExceptionHandler()
	{
		static $registered = false;
		if (!$registered) {
			$registered = true;
			set_exception_handler([__CLASS__, '_ExceptionHandler']);
		}
		return $registered;
	}

	/**
	 * -------------------------------------------------------------------------
	 * [registerShutdownHandler description]
	 * -------------------------------------------------------------------------
	 * 
	 * @return [type] [description]
	 */
	public static function registerShutdownHandler()
	{
		static $registered = false;
		if (!$registered) {
			$registered = true;
			register_shutdown_function([__CLASS__, '_ShutdownHandler']);
		}
		return $registered;
	}

	/**
	 * -------------------------------------------------------------------------
	 * [callHook description]
	 * -------------------------------------------------------------------------
	 * 
	 * @return [type] [description]
	 */
	private static function callHook()
	{
		static $called = false;

		if (!$called) {
			$called = true;

			if (!Hook::call('shutdown')) {
				self::writeLog(
					self::parseExceptionLevel(Hook::getLastErrorCode()),
					Hook::getLastErrorMessage()
					);
			}
		}
	}

	/**
	 * -------------------------------------------------------------------------
	 * [writeLog description]
	 * -------------------------------------------------------------------------
	 * 
	 * @param  int    $level   [description]
	 * @param  string $message [description]
	 * @return [type]          [description]
	 */
	private static function writeLog(int $level, string $message)
	{
		if (self::$logger) {
			self::$logger -> log($level, $message);
		} else {
			self::$logCache[] = [$level, $message];
		}
	}

	/**
	 * -------------------------------------------------------------------------
	 * [parseExceptionLevel description]
	 * -------------------------------------------------------------------------
	 * 
	 * @param  [type] $code [description]
	 * @return [type]       [description]
	 */
	private static function parseExceptionLevel(int $code)
	{
		if ($code < 5000000) {
			return Logger::LEVEL_ERROR;
		} elseif ($code < 6000000) {
			return Logger::LEVEL_WARNING;
		} elseif ($code < 7000000) {
			return Logger::LEVEL_NOTICE;
		} elseif ($code < 8000000) {
			return Logger::LEVEL_INFO;
		} elseif ($code < 9000000) {
			return Logger::LEVEL_DEBUG;
		} else {
			return Logger::LEVEL_EMERGENCY;
		}
	}


	/**
	 * -------------------------------------------------------------------------
	 * 错误处理程序，未知级别的错误将被识别成系统最高级别错误
	 * -------------------------------------------------------------------------
	 * 
	 * @param  integer 	$code 		错误的严重级别
	 * @param  string 	$message 	错误描述
	 * @param  string 	$file    	发生错误的文件
	 * @param  integer 	$line    	发生错误的行号
	 * @return void
	 */
	public static function _ErrorHandler(int $code, string $message, string $file = '', int $line = 0)
	{
		$level 	= self::$map[$code] ?? Logger::LEVEL_CRITICAL;
		$log 	= $message . ' at ' . $file . ' line ' . $line;

		self::writeLog($level, $log);

		if ($level >= Logger::LEVEL_ERROR) {
			self::callHook();
			exit(0);
		}
	}

	/**
	 * -------------------------------------------------------------------------
	 * 异常处理程序，异常的默认级别都是 ERROR，异常都会导致程序中断执行
	 * -------------------------------------------------------------------------
	 * 
	 * @param  Exception | Error 	$e 	异常对象
	 * @return void
	 */
	public static function _ExceptionHandler($e)
	{
		if ($e instanceof EdogerExceptionInterface) {
			$level = self::parseExceptionLevel($e -> getCode());
		} else {
			$level = Logger::LEVEL_ERROR;
		}
		
		$log = $e -> getMessage() . ' at ' . $e -> getFile() . ' line ' . $e -> getLine();

		self::writeLog($level, $log);

		if ($level >= Logger::LEVEL_ERROR) {
			self::callHook();
			exit(0);
		}
	}

	/**
	 * -------------------------------------------------------------------------
	 * 致命错误处理程序
	 * -------------------------------------------------------------------------
	 *
	 * @return void
	 */
	public static function _ShutdownHandler()
	{
		$error = error_get_last();

		if ($error) {
			error_clear_last();

			$level 	= self::$map[$error['type']] ?? Logger::LEVEL_CRITICAL;
			$log 	= $error['message'] . ' at ' . $error['file'] . ' line ' . $error['line'];
			
			self::writeLog($level, $log);
		}

		self::callHook();
	}
}