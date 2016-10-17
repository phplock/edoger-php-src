<?php
/**
 *+------------------------------------------------------------------------------------------------+
 *| Edoger PHP Framework                                                                           |
 *+------------------------------------------------------------------------------------------------+
 *| A simple and efficient PHP framework.                                                          |
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
namespace Edoger\Log;

final class Logger
{
	private $_map = [
		EDOGER_LEVEL_DEBUG		=> 'DEBUG',
		EDOGER_LEVEL_INFO		=> 'INFO',
		EDOGER_LEVEL_NOTICE		=> 'NOTICE',
		EDOGER_LEVEL_WARNING	=> 'WARNING',
		EDOGER_LEVEL_ERROR		=> 'ERROR',
		EDOGER_LEVEL_CRITICAL	=> 'CRITICAL',
		EDOGER_LEVEL_ALERT		=> 'ALERT',
		EDOGER_LEVEL_EMERGENCY	=> 'EMERGENCY',
		EDOGER_LEVEL_EXCEPTION	=> 'EXCEPTION',
		EDOGER_LEVEL_UNKNOWN	=> 'UNKNOWN'
	];
	private $_logs		= [];
	private $_handler	= null;
	private $_level		= EDOGER_LEVEL_ERROR;

	public function setLevel(int $level)
	{
		if ($level !== EDOGER_LEVEL_UNKNOWN && isset($this->_map[$level])) {
			$this->_level = $level;
			return true;
		} else {
			return false;
		}
	}

	public function getLevel()
	{
		return $this->_level;
	}

	public function getLogs()
	{
		return $this->_logs;
	}

	public function setHandler(LoggerHandlerInterface $handler)
	{
		$this->_handler = $handler;
		if (!empty($this->_logs)) {
			foreach ($this->_logs as $log) {
				$handler->save($log[0], $log[1], $log[2], $log[3]);
			}
		}
	}

	public function log(int $level, string $message)
	{
		if (!isset($this->_map[$level])) {
			$level = EDOGER_LEVEL_UNKNOWN;
		}
		
		if ($level >= $this->_level) {
			$date = date('Y-m-d H:i:s');
			$name = $this->_map[$level];
			$this->_logs[] = [$level, $name, $date, $message];
			if ($this->_handler) {
				$this->_handler->save($level, $name, $date, $message);
			}
		}
	}

	public function debug(string $message)
	{
		$this->log(EDOGER_LEVEL_DEBUG, $message);
	}

	public function info(string $message)
	{
		$this->log(EDOGER_LEVEL_INFO, $message);
	}

	public function notice(string $message)
	{
		$this->log(EDOGER_LEVEL_NOTICE, $message);
	}

	public function warning(string $message)
	{
		$this->log(EDOGER_LEVEL_WARNING, $message);
	}

	public function error(string $message)
	{
		$this->log(EDOGER_LEVEL_ERROR, $message);
	}

	public function critical(string $message)
	{
		$this->log(EDOGER_LEVEL_CRITICAL, $message);
	}

	public function alert(string $message)
	{
		$this->log(EDOGER_LEVEL_ALERT, $message);
	}

	public function emergency(string $message)
	{
		$this->log(EDOGER_LEVEL_EMERGENCY, $message);
	}
}