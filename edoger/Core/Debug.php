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

use Error;
use Exception;
use Edoger\Core\Log\Logger;
use Edoger\Exceptions\EdogerException;


/**
 * =============================================================================
 * 系统错误和异常管理组件
 * =============================================================================
 */
final class Debug
{
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
	 * 系统当前的调试开启状态。
	 * -------------------------------------------------------------------------
	 * 
	 * @var boolean
	 */
	private static $debug = false;

	/**
	 * -------------------------------------------------------------------------
	 * 设置系统调试开启状态。
	 * -------------------------------------------------------------------------
	 * 
	 * @param  boolean 	$status 	调试开启状态
	 * @return boolean
	 */
	public static function setDebugStatus(bool $status)
	{
		self::$debug = $status;
		return self::$debug;
	}

	/**
	 * -------------------------------------------------------------------------
	 * 解析异常对象所包含的信息。
	 * -------------------------------------------------------------------------
	 * 
	 * @param  Exception|Error 	$e 	异常对象
	 * @return array
	 */
	public static function parseException($e)
	{
		$outcome = [];
		if (is_object($e) && ($e instanceof Exception || $e instanceof Error)) {
			if ($e instanceof EdogerException) {
				$code = $e -> getCode();
				if ($code <= 50000000) {
					$outcome[] = Logger::LEVEL_ERROR;
				} elseif ($code <= 60000000) {
					$outcome[] = Logger::LEVEL_WARNING;
				} elseif ($code <= 70000000) {
					$outcome[] = Logger::LEVEL_NOTICE;
				} elseif ($code <= 80000000) {
					$outcome[] = Logger::LEVEL_INFO;
				} elseif ($code <= 90000000) {
					$outcome[] = Logger::LEVEL_DEBUG;
				} else {
					$outcome[] = Logger::LEVEL_CRITICAL;
				}
			} else {
				$outcome[] = Logger::LEVEL_ERROR;
			}
			if (self::$debug) {
				$outcome[] = $e -> __toString();
			} else {
				$outcome[] = $e -> getMessage() . ' at ' . $e -> getFile() . ' line ' . $e -> getLine();
			}
		} else {
			$outcome[] = Logger::LEVEL_CRITICAL;
			$outcome[] = 'Unknown exception object';
		}
		return $outcome;
	}


	/**
	 * -------------------------------------------------------------------------
	 * 系统错误处理程序
	 * -------------------------------------------------------------------------
	 * 
	 * @param  integer 	$code 		错误的严重级别
	 * @param  string 	$message 	错误描述
	 * @param  string 	$file    	发生错误的文件
	 * @param  integer 	$line    	发生错误的行号
	 * @return void
	 */
	public static function edogerErrorHandler(int $code, string $message, string $file = '', int $line = 0)
	{
		$level = self::$map[$code] ?? Logger::LEVEL_CRITICAL;
		Logger::log($level, $message . ' at ' . $file . ' line ' . $line);
		if ($level >= Logger::LEVEL_ERROR) {
			Kernel::error(self::$debug);
		}
	}

	/**
	 * -------------------------------------------------------------------------
	 * 系统异常处理程序，异常的默认级别都是 ERROR，异常都会导致程序中断执行
	 * -------------------------------------------------------------------------
	 * 
	 * @param  Exception|Error 	$e 	异常对象
	 * @return void
	 */
	public static function edogerExceptionHandler($e)
	{
		$exceptionInfo = self::parseException($e);
		Logger::log($exceptionInfo[0], $exceptionInfo[1]);
		Kernel::error(self::$debug);
	}

	/**
	 * -------------------------------------------------------------------------
	 * 致命错误处理程序
	 * -------------------------------------------------------------------------
	 *
	 * @return void
	 */
	public static function edogerFatalErrorHandler()
	{
		$e = error_get_last();
		if ($e) {
			error_clear_last();
			$level = self::$map[$e['type']] ?? Logger::LEVEL_CRITICAL;
			Logger::log($level, $e['message'] . ' at ' . $e['file'] . ' line ' . $e['line']);
			if ($level >= Logger::LEVEL_ERROR) {
				Kernel::error(self::$debug);
			} else {
				Kernel::flush();
			}
		} else {
			Kernel::flush();
		}
	}
}