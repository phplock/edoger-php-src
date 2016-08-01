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
 * 
 * ================================================================================
 */
final class Debug
{
	/**
	 * ----------------------------------------------------------------------------
	 * What is it ?
	 * ----------------------------------------------------------------------------
	 *
	 * @var type
	 */
	private static $kernel;

	/**
	 * ----------------------------------------------------------------------------
	 * What is it ?
	 * ----------------------------------------------------------------------------
	 *
	 * @var type
	 */
	private static $hook = null;

	/**
	 * ----------------------------------------------------------------------------
	 * What is it ?
	 * ----------------------------------------------------------------------------
	 *
	 * @var type
	 */
	private static $logger = null;

	private static $logCache = [];

	/**
	 * ----------------------------------------------------------------------------
	 * What is it ?
	 * ----------------------------------------------------------------------------
	 *
	 * @var type
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
	 * What is it ?
	 * ----------------------------------------------------------------------------
	 *
	 * @return type
	 */
	public function __construct(Kernel &$kernel)
	{
		self::$kernel = &$kernel;
	}

	/**
	 * ----------------------------------------------------------------------------
	 * What is it ?
	 * ----------------------------------------------------------------------------
	 *
	 * @return type
	 */
	public function register()
	{
		static $registered = false;

		if (!$registered) {
			$registered = true;

			set_error_handler([$this, '_ErrorHandler']);
			set_exception_handler([$this, '_ExceptionHandler']);
			register_shutdown_function([$this, '_ShutdownHandler']);
		}

		return $registered;
	}

	public function bindLogger(Logger $logger)
	{
		self::$logger = $logger;
		if (!empty(self::$logCache)) {
			foreach (self::$logCache as $log) {
				self::$logCache -> log($log[0], $log[1]);
			}
			self::$logCache = [];
		}
	}

	public function bindHook($hook)
	{
		self::$hook = $hook;
	}

	private function executeHandler(string $message, int $level, int $code,, string $file, int $line)
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
	 * What is it ?
	 * ----------------------------------------------------------------------------
	 *
	 * @return type
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
	 * What is it ?
	 * ----------------------------------------------------------------------------
	 *
	 * @return type
	 */
	public function _ExceptionHandler($e)
	{
		if ($e instanceof EdogerExceptionInterface) {
			$level 	= $e -> getLevel();
			$log 	= $e -> getLog();
		} else {
			$level 	= self::$map[$e -> getCode()] ?? Logger::ERROR;
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
	 * What is it ?
	 * ----------------------------------------------------------------------------
	 *
	 * @return type
	 */
	public function _ShutdownHandler()
	{
		$error = error_get_last();
		if ($error) {
			
			//	Clear the last error.
			error_clear_last();

			$this -> executeHandler(
				$error['message'],
				self::$map[$error['type']] ?? Logger::CRITICAL,
				$error['type'],
				$error['file'],
				$error['line']
				);
		}
	}
}