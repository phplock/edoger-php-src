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
use Edoger\Interfaces\EdogerExceptionInterface;
use ErrorException;

/**
 * ================================================================================
 * 系统错误调试管理组件
 *
 * 这个组件用于捕获全局所有的错误和异常，同时支持系统退出时执行响应钩子和日志记录器
 * 记录日志（日志记录器使用核心对象提供的系统日志记录组件，与系统共享日志记录通道）
 * ================================================================================
 */
final class Debug
{
	/**
	 * ----------------------------------------------------------------------------
	 * 核心对象的引用
	 * ----------------------------------------------------------------------------
	 *
	 * @var Edoger\Core\Kernel
	 */
	private static $kernel;

	/**
	 * ----------------------------------------------------------------------------
	 * 绑定的响应钩子程序，如果没有设定，那么这个值是NULL
	 * ----------------------------------------------------------------------------
	 *
	 * @var Edoger\Core\Hook
	 */
	private static $hook = null;

	/**
	 * ----------------------------------------------------------------------------
	 * 绑定的日志记录器，如果没有绑定，捕获的错误将记录组件自身缓存
	 * ----------------------------------------------------------------------------
	 *
	 * @var Edoger\Core\Log\Logger
	 */
	private static $logger = null;

	/**
	 * ----------------------------------------------------------------------------
	 * 捕获的错误日志缓存，这在绑定日志记录器之后会自动写入日志
	 * ----------------------------------------------------------------------------
	 *
	 * @var array
	 */
	private static $logCache = [];

	/**
	 * ----------------------------------------------------------------------------
	 * PHP错误级别到日志级别的映射数组
	 * ----------------------------------------------------------------------------
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
	 * ----------------------------------------------------------------------------
	 * 构造函数，绑定核心对象到组件内部，同时自动注册错误捕获程序
	 * ----------------------------------------------------------------------------
	 *
	 * @param  Kernel 	$kernel 	核心对象的引用
	 * @return void
	 */
	public function __construct(Kernel &$kernel)
	{
		self::$kernel = &$kernel;
		$this -> register();
	}

	/**
	 * ----------------------------------------------------------------------------
	 * 注册错误捕获处理程序
	 * ----------------------------------------------------------------------------
	 *
	 * @return void
	 */
	private function register()
	{
		set_error_handler([$this, '_ErrorHandler']);
		set_exception_handler([$this, '_ExceptionHandler']);
		register_shutdown_function([$this, '_ShutdownHandler']);
	}

	/**
	 * ----------------------------------------------------------------------------
	 * 绑定日志记录器到组件内部
	 * ----------------------------------------------------------------------------
	 * 
	 * @param  Logger 	$logger 	日志记录器对象
	 * @return void
	 */
	public function bindLogger(Logger $logger)
	{
		self::$logger = $logger;

		//	如果在绑定日志记录器存在缓存的日志，那么将立即写入日志
		if (!empty(self::$logCache)) {
			foreach (self::$logCache as $log) {
				self::$logCache -> log($log[0], $log[1]);
			}
			self::$logCache = [];
		}
	}

	/**
	 * ----------------------------------------------------------------------------
	 * 绑定响应输出的钩子程序到组件内部，这个钩子程序将在系统发生 ERROR 及以上级别
	 * 的错误或异常时执行，执行完这个钩子程序，会立即完全退出系统。这个钩子程序必须
	 * 是只能执行一次，因为这个程序可能会被系统调用多次
	 * ----------------------------------------------------------------------------
	 * 
	 * @param  Hook 	$hook 	钩子处理程序对象
	 * @return void
	 */
	public function bindHook(Hook $hook)
	{
		self::$hook = $hook;
	}

	/**
	 * ----------------------------------------------------------------------------
	 * 处理系统捕获的错误，ERROR 及以上级别的错误都会转换成异常交由异常处理程序处理，
	 * 异常处理程序会在处理完成后立即进行系统退出程序，这标示 ERROR 及以上级别的错
	 * 误都会导致程序中断。
	 * ----------------------------------------------------------------------------
	 * 
	 * @param  string 	$message 	错误消息
	 * @param  integer 	$level   	错误日志级别
	 * @param  integer 	$code 		错误严重级别
	 * @param  string 	$file    	发生错误的文件
	 * @param  integer 	$line    	发生错误的行号
	 * @return void
	 */
	private function executeHandler(string $message, int $level, int $code, string $file, int $line)
	{
		if ($level <= Logger::LEVEL_ERROR) {
			$this -> _ExceptionHandler(
				new ErrorException($message, $level, $code, $file, $line)
				);
		} else {
			$message = "{$message} at {$file} line {$line}";
			if (self::$logger) {
				self::$logger -> log($level, $message);
			} else {
				self::$logCache[] = [$level, $message];
			}
		}
	}

	/**
	 * ----------------------------------------------------------------------------
	 * 错误处理程序，未知级别的错误将被识别成系统最高级别错误
	 * ----------------------------------------------------------------------------
	 * 
	 * @param  integer 	$code 		错误的严重级别
	 * @param  string 	$message 	错误描述
	 * @param  string 	$file    	发生错误的文件
	 * @param  integer 	$line    	发生错误的行号
	 * @return void
	 */
	public function _ErrorHandler(int $code, string $message, string $file = '', int $line = 0)
	{
		$this -> executeHandler(
			$message,
			self::$map[$code] ?? Logger::LEVEL_CRITICAL,
			$code,
			$file,
			$line
			);
	}

	/**
	 * ----------------------------------------------------------------------------
	 * 异常处理程序，异常的默认级别都是 ERROR，异常都会导致程序中断执行
	 * ----------------------------------------------------------------------------
	 * 
	 * @param  Exception | Error 	$e 	异常对象
	 * @return void
	 */
	public function _ExceptionHandler($e)
	{
		if ($e instanceof EdogerExceptionInterface) {
			$level 	= $e -> getLevel();
			$log 	= $e -> getLog();
		} else {
			$level 	= Logger::LEVEL_ERROR;
			$log 	= "{$e -> getMessage()} at {$e -> getFile()} line {$e -> getLine()}";
		}

		if (self::$logger) {
			self::$logger -> log($level, $log);
		} else {
			self::$logCache[] = [$level, $log];
		}

		if (self::$hook) {
			self::$hook -> call();
		}
	}

	/**
	 * ----------------------------------------------------------------------------
	 * 致命错误处理程序
	 * ----------------------------------------------------------------------------
	 *
	 * @return void
	 */
	public function _ShutdownHandler()
	{
		$error = error_get_last();
		if ($error) {
			
			//	清除最后发生的错误，这是为了防止其他程序在全局注册的处理程序被多次
			//	反复执行
			error_clear_last();

			$this -> executeHandler(
				$error['message'],
				self::$map[$error['type']] ?? Logger::LEVEL_CRITICAL,
				$error['type'],
				$error['file'],
				$error['line']
				);
		}

		//	针对非致命错误，系统自动完成代码的执行，为了保证响应数据的输出，这里会主
		//	动在满足条件的情况下，调用一次钩子程序（如果有），但是这极有可能是重复调
		//	用，但是在某些业务代码中主动触发的错误，或者没有执行应用程序就已经退出了
		//	系统
		if (self::$hook) {
			self::$hook -> call();
		}
	}
}