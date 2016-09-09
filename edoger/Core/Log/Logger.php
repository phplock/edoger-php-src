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
namespace Edoger\Core\Log;

/**
 * =============================================================================
 * 日志记录器类
 * =============================================================================
 */
final class Logger
{
	const LEVEL_DEBUG 		= 1;
	const LEVEL_INFO 		= 2;
	const LEVEL_NOTICE 		= 4;
	const LEVEL_WARNING 	= 8;
	const LEVEL_ERROR 		= 16;
	const LEVEL_CRITICAL 	= 32;
	const LEVEL_ALERT 		= 64;
	const LEVEL_EMERGENCY 	= 128;

	/**
	 * -------------------------------------------------------------------------
	 * 全局所有日志记录器记录的日志
	 * -------------------------------------------------------------------------
	 * 
	 * @var array
	 */
	private static $logQueue = [];

	/**
	 * -------------------------------------------------------------------------
	 * 日志级别对应的文字描述
	 * -------------------------------------------------------------------------
	 *
	 * @var array
	 */
	private static $levelToStringMap = [

		self::LEVEL_DEBUG 		=> 'DEBUG',
		self::LEVEL_INFO 		=> 'INFO',
		self::LEVEL_NOTICE 		=> 'NOTICE',
		self::LEVEL_WARNING 	=> 'WARNING',
		self::LEVEL_ERROR 		=> 'ERROR',
		self::LEVEL_CRITICAL 	=> 'CRITICAL',
		self::LEVEL_ALERT 		=> 'ALERT',
		self::LEVEL_EMERGENCY 	=> 'EMERGENCY'

	];

	/**
	 * -------------------------------------------------------------------------
	 * 该日志记录器本身的记录缓存
	 * -------------------------------------------------------------------------
	 * 
	 * @var array
	 */
	private $logs = [];

	/**
	 * -------------------------------------------------------------------------
	 * 日志处理程序
	 * -------------------------------------------------------------------------
	 * 
	 * @var Edoger\Core\Log\LoggerHandlerInterface
	 */
	private $handler = null;

	/**
	 * -------------------------------------------------------------------------
	 * 日志记录器的通道名称，这个用于区分多个日志记录器记录的不同日志
	 * -------------------------------------------------------------------------
	 * 
	 * @var string
	 */
	private $passageway = '';

	/**
	 * -------------------------------------------------------------------------
	 * 创建一个日志记录器实例，并设定通道名称
	 * -------------------------------------------------------------------------
	 * 
	 * @param  string 	$passageway 	The passageway name.
	 * @return void
	 */
	public function __construct(string $passageway)
	{
		$this -> passageway = strtoupper($passageway);
	}

	/**
	 * -------------------------------------------------------------------------
	 * 获取当前日志记录器的通道名称
	 * -------------------------------------------------------------------------
	 * 
	 * @return string
	 */
	public function getPassagewayName()
	{
		return $this -> passageway;
	}

	/**
	 * -------------------------------------------------------------------------
	 * 获取全局所有日志记录器记录的日志
	 * -------------------------------------------------------------------------
	 * 
	 * @return array
	 */
	public static function getAllLogs()
	{
		return self::$logQueue;
	}

	/**
	 * -------------------------------------------------------------------------
	 * 获取该日志记录器本身记录的所有日志
	 * -------------------------------------------------------------------------
	 * 
	 * @return array
	 */
	public function getLogs()
	{
		return $this -> logs;
	}

	/**
	 * -------------------------------------------------------------------------
	 * 设置日志处理程序
	 * -------------------------------------------------------------------------
	 * 
	 * @param  Edoger\Core\Log\LoggerHandlerInterface $handler 日志处理程序对象
	 * @return Edoger\Core\Log\Logger
	 */
	public function useHandler(string $handler, int $level)
	{
		$handler 	= ucfirst(strtolower($handler));
		$className 	= '\\Edoger\\Core\\Log\\Handlers\\' . $handler . 'Handler';
		
		if (class_exists($className, true)) {
			$this -> handler = new $className($level);
		} else {
			
		}

		//	绑定日志处理程序后立即处理已有的日志记录
		if (!empty($this -> logs)) {
			foreach ($this -> logs as $log) {
				$this -> handler -> save($log[0], $log[1]);
			}
		}
		return $this;
	}

	/**
	 * -------------------------------------------------------------------------
	 * 记录一条指定级别的日志，如果指定的级别不能识别，将转换成未知日志
	 * -------------------------------------------------------------------------
	 * 
	 * @param  int    	$level   	The log level.
	 * @param  string 	$message 	The log content.
	 * @return Edoger\Core\Log\Logger
	 */
	public function log(int $level, string $message)
	{
		$date 	= date('Y-m-d H:i:s');
		$name 	= self::$levelToStringMap[$level] ?? 'UNKNOWN';

		$log 	= "[{$date}][{$name}][{$this -> passageway}]{$message}";

		self::$logQueue[] 	= [$level, $log];
		$this -> logs[] 	= [$level, $log];

		if ($this -> handler) {
			$this -> handler -> save($level, $log);
		}

		return $this;
	}

	/**
	 * -------------------------------------------------------------------------
	 * 记录一条 DEBUG 级别的日志
	 * -------------------------------------------------------------------------
	 * 
	 * @return Edoger\Core\Log\Logger
	 */
	public function debug(string $message)
	{
		$this -> log(self::LEVEL_DEBUG, $message);
		return $this;
	}


	/**
	 * -------------------------------------------------------------------------
	 * 记录一条 INFO 级别的日志
	 * -------------------------------------------------------------------------
	 * 
	 * @return Edoger\Core\Log\Logger
	 */
	public function info(string $message)
	{
		$this -> log(self::LEVEL_INFO, $message);
		return $this;
	}


	/**
	 * -------------------------------------------------------------------------
	 * 记录一条 NOTICE 级别的日志
	 * -------------------------------------------------------------------------
	 * 
	 * @return Edoger\Core\Log\Logger
	 */
	public function notice(string $message)
	{
		$this -> log(self::LEVEL_NOTICE, $message);
		return $this;
	}


	/**
	 * -------------------------------------------------------------------------
	 * 记录一条 WARNING 级别的日志
	 * -------------------------------------------------------------------------
	 * 
	 * @return Edoger\Core\Log\Logger
	 */
	public function warning(string $message)
	{
		$this -> log(self::LEVEL_WARNING, $message);
		return $this;
	}


	/**
	 * -------------------------------------------------------------------------
	 * 记录一条 ERROR 级别的日志
	 * -------------------------------------------------------------------------
	 * 
	 * @return Edoger\Core\Log\Logger
	 */
	public function error(string $message)
	{
		$this -> log(self::LEVEL_ERROR, $message);
		return $this;
	}


	/**
	 * -------------------------------------------------------------------------
	 * 记录一条 CRITICAL 级别的日志
	 * -------------------------------------------------------------------------
	 * 
	 * @return Edoger\Core\Log\Logger
	 */
	public function critical(string $message)
	{
		$this -> log(self::LEVEL_CRITICAL, $message);
		return $this;
	}


	/**
	 * -------------------------------------------------------------------------
	 * 记录一条 ALERT 级别的日志
	 * -------------------------------------------------------------------------
	 * 
	 * @return Edoger\Core\Log\Logger
	 */
	public function alert(string $message)
	{
		$this -> log(self::LEVEL_ALERT, $message);
		return $this;
	}


	/**
	 * -------------------------------------------------------------------------
	 * 记录一条 EMERGENCY 级别的日志
	 * -------------------------------------------------------------------------
	 * 
	 * @return Edoger\Core\Log\Logger
	 */
	public function emergency(string $message)
	{
		$this -> log(self::LEVEL_EMERGENCY, $message);
		return $this;
	}
}