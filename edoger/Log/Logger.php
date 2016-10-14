<?php
/**
 *+------------------------------------------------------------------------------------------------+
 *| Edoger PHP Framework                                                                           |
 *+------------------------------------------------------------------------------------------------+
 *| A simple route analysis and matching module.                                                   |
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

use Edoger\Core\Kernel;

final class Logger
{
	private static $map = [
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
	private static $logs	= [];
	private static $handler	= null;
	private static $level	= EDOGER_LEVEL_ERROR;

	public static function setLevel(int $level)
	{
		if ($level !== EDOGER_LEVEL_UNKNOWN && isset(self::$map[$level])) {
			self::$level = $level;
			return true;
		} else {
			return false;
		}
	}

	public static function getLevel()
	{
		return self::$level;
	}

	public static function getLogs()
	{
		return self::$logs;
	}

	public static function useHandler(string $name)
	{
		$name		= strtolower($name);
		$className	= '\\Edoger\\Log\\Handlers\\' . ucfirst($name) . 'Handler';
		if (class_exists($className, true)) {
			$config = Kernel::singleton()->config()->get('log.'.$name);
			self::$handler = new $className($config);
			if (!empty(self::$logs)) {
				foreach (self::$logs as $log) {
					self::$handler->save($log[0], $log[1], $log[2], $log[3]);
				}
			}
			return true;
		} else {
			return false;
		}
	}

	public static function log(int $level, string $message)
	{
		if (!isset(self::$map[$level])) {
			$level = EDOGER_LEVEL_UNKNOWN;
		}
		if ($level >= self::$level) {
			$date = date('Y-m-d H:i:s');
			$name = self::$map[$level];
			self::$logs[] = [$level, $name, $date, $message];
			if (self::$handler) {
				self::$handler->save($level, $name, $date, $message);
			}
		}
	}

	public static function debug(string $message)
	{
		self::log(EDOGER_LEVEL_DEBUG, $message);
	}

	public static function info(string $message)
	{
		self::log(EDOGER_LEVEL_INFO, $message);
	}

	public static function notice(string $message)
	{
		self::log(EDOGER_LEVEL_NOTICE, $message);
	}

	public static function warning(string $message)
	{
		self::log(EDOGER_LEVEL_WARNING, $message);
	}

	public static function error(string $message)
	{
		self::log(EDOGER_LEVEL_ERROR, $message);
	}

	public static function critical(string $message)
	{
		self::log(EDOGER_LEVEL_CRITICAL, $message);
	}

	public static function alert(string $message)
	{
		self::log(EDOGER_LEVEL_ALERT, $message);
	}

	public static function emergency(string $message)
	{
		self::log(EDOGER_LEVEL_EMERGENCY, $message);
	}
}