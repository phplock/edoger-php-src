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
	private $_map = [
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

	private $_registered = false;

	public function register()
	{
		if (!$this->_registered) {
			$this->_registered = true;

			set_error_handler([$this, '_edogerErrorHandler']);
			set_exception_handler([$this, '_edogerExceptionHandler']);
			register_shutdown_function([$this, '_edogerFatalErrorHandler']);
		}

		return true;
	}

	public function _edogerErrorHandler(int $code, string $message, string $file = '', int $line = 0)
	{
		$level = $this->_map[$code] ?? EDOGER_LEVEL_UNKNOWN;
		$this->_edogerExceptionHandler(new ErrorException($message, $level, $code, $file, $line));
	}

	public function _edogerExceptionHandler($exception)
	{
		$log	= $exception->getMessage().' at '.$exception->getFile().' line '.$exception->getLine();
		$quit	= true;
		if ($exception instanceof ErrorException) {
			$severity = $exception->getSeverity();
			if ($severity < EDOGER_LEVEL_ERROR) {
				$quit = false;
			}
			Kernel::singleton()->logger()->log($severity, $log);
		} else {
			Kernel::singleton()->logger()->log(EDOGER_LEVEL_EXCEPTION, $log);
		}

		if ($quit) {
			Kernel::singleton()->error($exception)->termination();
		}
	}

	public function _edogerFatalErrorHandler()
	{
		$error = error_get_last();
		if ($error) {
			error_clear_last();
			$level = $this->_map[$error['type']] ?? EDOGER_LEVEL_UNKNOWN;
			$this->_edogerExceptionHandler(
				new ErrorException($error['message'], $level, $error['type'], $error['file'], $error['line'])
				);
		} else {
			Kernel::singleton()->termination();
		}
	}
}