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
namespace Edoger\Core\Log;

/**
 * ================================================================================
 * Log Recorder.
 * ================================================================================
 */
final class Logger
{
	const LEVEL_DEBUG 		= 128;
	const LEVEL_INFO 		= 64;
	const LEVEL_NOTICE 		= 32;
	const LEVEL_WARNING 	= 16;
	const LEVEL_ERROR 		= 8;
	const LEVEL_CRITICAL 	= 4;
	const LEVEL_ALERT 		= 2;
	const LEVEL_EMERGENCY 	= 1;
	const LEVEL_ALL 		= 255;

	/**
	 * ----------------------------------------------------------------------------
	 * All logs captured by the system.
	 * ----------------------------------------------------------------------------
	 *
	 * Each element inside contains a two element array that contains the log level 
	 * and log content.
	 * 
	 * @var array
	 */
	private static $logQueue = [];

	/**
	 * ----------------------------------------------------------------------------
	 * Description of all log levels.
	 * ----------------------------------------------------------------------------
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
	 * ----------------------------------------------------------------------------
	 * Log handler list.
	 * ----------------------------------------------------------------------------
	 *
	 * In the queue of any a log processing procedures, if returns true, then a 
	 * handler for the back of the queue will not be executed, because the said log 
	 * has been completed, no longer need to continue processing.
	 * 
	 * @var type
	 */
	private $handlers = [];

	/**
	 * ----------------------------------------------------------------------------
	 * The passageway name of the log recorder.
	 * ----------------------------------------------------------------------------
	 *
	 * The passageway names can be used to distinguish logs from different components 
	 * and modules.
	 * 
	 * @var type
	 */
	private $passageway = '';

	/**
	 * ----------------------------------------------------------------------------
	 * Gets all the logs captured by the system.
	 * ----------------------------------------------------------------------------
	 * 
	 * @return array
	 */
	public static function getAllLogs()
	{
		return self::$logQueue;
	}

	/**
	 * ----------------------------------------------------------------------------
	 * Bind passageway name to this logger.
	 * ----------------------------------------------------------------------------
	 * 
	 * @param  string 	$passageway 	The passageway name.
	 * @return void
	 */
	public function __construct(string $passageway)
	{
		$this -> passageway = $passageway;
	}

	/**
	 * ----------------------------------------------------------------------------
	 * Gets the passageway name for this logger.
	 * ----------------------------------------------------------------------------
	 * 
	 * @return string
	 */
	public function getPassageway()
	{
		return $this -> passageway;
	}

	/**
	 * ----------------------------------------------------------------------------
	 * Add a log handler object to the current logger.
	 * ----------------------------------------------------------------------------
	 *
	 * Each log handler must be an instance of a class that implements the "Logger-
	 * HandlerInterface" interface.
	 * 
	 * @param  object 	$handler 	The log handler object.
	 * @return Edoger\Core\Logger\Logger
	 */
	public function appendHandler(LoggerHandlerInterface $handler)
	{
		$this -> handlers[] = $handler;
		return $this;
	}

	/**
	 * ----------------------------------------------------------------------------
	 * Record a custom level log.
	 * ----------------------------------------------------------------------------
	 * 
	 * @param  int    	$level   	The log level.
	 * @param  string 	$message 	The log content.
	 * @return Edoger\Core\Logger\Logger
	 */
	public function log(int $level, string $message)
	{
		$date 	= date('Y-m-d H:i:s');
		$name 	= self::$levelToStringMap[$level] ?? 'UNKNOWN';

		$log 	= "[{$date}][{$name}][{$this -> passageway}]{$message}";

		self::$logQueue[] = [$level, $log];

		if (!empty($this -> handlers)) {
			foreach ($this -> handlers as $handler) {
				if ($handler -> save($level, $log)) {
					break;
				}
			}
		}

		return $this;
	}

	/**
	 * ----------------------------------------------------------------------------
	 * Record a "DEBUG" level log.
	 * ----------------------------------------------------------------------------
	 * 
	 * @return Edoger\Core\Logger\Logger
	 */
	public function debug(string $message)
	{
		$this -> log(self::LEVEL_DEBUG, $message);
		return $this;
	}


	/**
	 * ----------------------------------------------------------------------------
	 * Record a "INFO" level log.
	 * ----------------------------------------------------------------------------
	 * 
	 * @return Edoger\Core\Logger\Logger
	 */
	public function info(string $message)
	{
		$this -> log(self::LEVEL_INFO, $message);
		return $this;
	}


	/**
	 * ----------------------------------------------------------------------------
	 * Record a "NOTICE" level log.
	 * ----------------------------------------------------------------------------
	 * 
	 * @return Edoger\Core\Logger\Logger
	 */
	public function notice(string $message)
	{
		$this -> log(self::LEVEL_NOTICE, $message);
		return $this;
	}


	/**
	 * ----------------------------------------------------------------------------
	 * Record a "WARNING" level log.
	 * ----------------------------------------------------------------------------
	 * 
	 * @return Edoger\Core\Logger\Logger
	 */
	public function warning(string $message)
	{
		$this -> log(self::LEVEL_WARNING, $message);
		return $this;
	}


	/**
	 * ----------------------------------------------------------------------------
	 * Record a "ERROR" level log.
	 * ----------------------------------------------------------------------------
	 * 
	 * @return Edoger\Core\Logger\Logger
	 */
	public function error(string $message)
	{
		$this -> log(self::LEVEL_ERROR, $message);
		return $this;
	}


	/**
	 * ----------------------------------------------------------------------------
	 * Record a "CRITICAL" level log.
	 * ----------------------------------------------------------------------------
	 * 
	 * @return Edoger\Core\Logger\Logger
	 */
	public function critical(string $message)
	{
		$this -> log(self::LEVEL_CRITICAL, $message);
		return $this;
	}


	/**
	 * ----------------------------------------------------------------------------
	 * Record a "ALERT" level log.
	 * ----------------------------------------------------------------------------
	 * 
	 * @return Edoger\Core\Logger\Logger
	 */
	public function alert(string $message)
	{
		$this -> log(self::LEVEL_ALERT, $message);
		return $this;
	}


	/**
	 * ----------------------------------------------------------------------------
	 * Record a "EMERGENCY" level log.
	 * ----------------------------------------------------------------------------
	 * 
	 * @return Edoger\Core\Logger\Logger
	 */
	public function emergency(string $message)
	{
		$this -> log(self::LEVEL_EMERGENCY, $message);
		return $this;
	}
}