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
namespace Edoger\Http\Session\Handler;

use SessionHandlerInterface;

class Apcu implements SessionHandlerInterface
{
	private $_timeout;

	public function __construct(int $timeout)
	{
		$this->_timeout = $timeout;
	}

	public function close()
	{
		return true;
	}

	public function destroy(string $sid)
	{
		$key = 'session_'.$sid;
		if (apcu_exists($key)) {
			apcu_delete($key);
		}

		return true;
	}

	public function gc(int $maxlifetime)
	{
		return true;
	}

	public function open(string $path, string $name)
	{
		return true;
	}

	public function read(string $sid)
	{
		$key = 'session_'.$sid;
		if (apcu_exists($key)) {
			$data = apcu_fetch($key, $success);
			if ($success) {
				return $data;
			}
		}

		return '';
	}

	public function write(string $sid, string $data)
	{
		return apcu_store('session_'.$sid, $data, $this->_timeout);
	}
}