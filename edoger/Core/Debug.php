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

/**
 * 
 */
final class Debug
{
	private static $map = [
		E_ERROR             => Logger::CRITICAL,
		E_WARNING           => Logger::WARNING,
		E_PARSE             => Logger::ALERT,
		E_NOTICE            => Logger::NOTICE,
		E_CORE_ERROR        => Logger::CRITICAL,
		E_CORE_WARNING      => Logger::WARNING,
		E_COMPILE_ERROR     => Logger::ALERT,
		E_COMPILE_WARNING   => Logger::WARNING,
		E_USER_ERROR        => Logger::ERROR,
		E_USER_WARNING      => Logger::WARNING,
		E_USER_NOTICE       => Logger::NOTICE,
		E_STRICT            => Logger::NOTICE,
		E_RECOVERABLE_ERROR => Logger::ERROR,
		E_DEPRECATED        => Logger::NOTICE,
		E_USER_DEPRECATED   => Logger::NOTICE
	];

	private static $registered = false;
	
	public function __construct()
	{
		if (!self::$registered) {
			self::$registered = true;

			set_error_handler([$this, 'ErrorHandler']);
			set_exception_handler([$this, 'ExceptionHandler']);
		}
	}

	public function stop()
	{

	}

	public function ErrorHandler(int $code, string $message, string $file = '', int $line = 0, array $context = [])
	{

	}

	public function ExceptionHandler($exception)
	{

	}
}