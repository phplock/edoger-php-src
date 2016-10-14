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
namespace Edoger\Debug;

use Error;
use Exception;
use ErrorException;
use Edoger\Log\Logger;
use Edoger\Core\Kernel;
use Edoger\Exception\EdogerException;

final class Debug
{
	private static $map = [
		E_ERROR             => EDOGER_LEVEL_CRITICAL,
		E_WARNING           => EDOGER_LEVEL_WARNING,
		E_PARSE             => EDOGER_LEVEL_ALERT,
		E_NOTICE            => EDOGER_LEVEL_NOTICE,
		E_CORE_ERROR        => EDOGER_LEVEL_CRITICAL,
		E_CORE_WARNING      => EDOGER_LEVEL_WARNING,
		E_COMPILE_ERROR     => EDOGER_LEVEL_ALERT,
		E_COMPILE_WARNING   => EDOGER_LEVEL_WARNING,
		E_USER_ERROR        => EDOGER_LEVEL_ERROR,
		E_USER_WARNING      => EDOGER_LEVEL_WARNING,
		E_USER_NOTICE       => EDOGER_LEVEL_NOTICE,
		E_STRICT            => EDOGER_LEVEL_NOTICE,
		E_RECOVERABLE_ERROR => EDOGER_LEVEL_ERROR,
		E_DEPRECATED        => EDOGER_LEVEL_NOTICE,
		E_USER_DEPRECATED   => EDOGER_LEVEL_NOTICE
	];

	public static function edogerErrorHandler(int $code, string $message, string $file = '', int $line = 0)
	{
		$level = self::$map[$code] ?? EDOGER_LEVEL_UNKNOWN;
		self::edogerExceptionHandler(new ErrorException($message, $level, $code, $file, $line));
	}

	public static function edogerExceptionHandler($e)
	{
		$log = $e->getMessage().' at '.$e->getFile().' line '.$e->getLine();
		$quit = true;
		if ($e instanceof ErrorException) {
			$severity = $e->getSeverity();
			if ($severity < EDOGER_LEVEL_ERROR) {
				$quit = false;
			}
			Logger::log($severity, $log);
		} else {
			Logger::log(EDOGER_LEVEL_EXCEPTION, $log);
		}

		if ($quit) {
			Kernel::singleton()->error($e)->termination();
		}
	}

	public static function edogerFatalErrorHandler()
	{
		$e = error_get_last();
		if ($e) {
			error_clear_last();
			$level = self::$map[$e['type']] ?? EDOGER_LEVEL_UNKNOWN;
			self::edogerExceptionHandler(
				new ErrorException($e['message'], $level, $e['type'], $e['file'], $e['line'])
				);
		} else {
			Kernel::singleton()->termination();
		}
	}
}