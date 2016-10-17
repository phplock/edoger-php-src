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
namespace Edoger\Http\Session;

use Edoger\Core\Kernel;
use SessionHandlerInterface;

class Session
{
	private $_started = false;
	private $_sessionId;

	public function __construct()
	{
		$this->_sessionId = session_id();
		if ($this->_sessionId) {
			$this->_started = true;
		}
	}

	public function start(string $sid, SessionHandlerInterface $handler)
	{
		if ($this->_started) {
			return true;
		}

		if (!$sid) {
			$sid = md5(implode([
				mt_rand(),
				mt_rand(),
				mt_rand(),
				mt_rand(),
				mt_rand(),
				mt_rand(),
				mt_rand(),
				uniqid('', true)
				]));
		}

		session_id($sid);
		session_set_save_handler($handler);
		session_start(['use_cookies' => 0]);
		$this->_sessionId = session_id();
		$this->_started = true;
		return true;
	}

	public function sessionId()
	{
		return $this->_sessionId;
	}

	public function get(string $key, $def = null)
	{
		if ($this->_started) {
			return $_SESSION[$key] ?? $def;
		} else {
			return $def;
		}
	}

	public function set(string $key, $value)
	{
		if ($this->_started) {
			$_SESSION[$key] = $value;
			return true;
		} else {
			return false;
		}
	}

	public function exists(string $key)
	{
		if ($this->_started) {
			return array_key_exists($key, $_SESSION);
		} else {
			return false;
		}
	}

	public function delete(string $key)
	{
		if ($this->_started) {
			if (array_key_exists($key, $_SESSION)) {
				unset($_SESSION[$key]);
			}
			return true;
		} else {
			return false;
		}
	}

	public function clean()
	{
		if ($this->_started) {
			$_SESSION = [];
			return true;
		} else {
			return false;
		}
	}
}