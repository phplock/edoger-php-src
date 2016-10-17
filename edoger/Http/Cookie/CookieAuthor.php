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
namespace Edoger\Http\Cookie;

class CookieAuthor
{
	private $_secretKey;
	private $_option = [];
	private $_names = [];

	public function __construct(string $secretKey = '', array $option = [])
	{
		$this->_secretKey = $secretKey;

		$this->_option['expire']	= $option['expire'] ?? 86400;
		$this->_option['path']		= $option['path'] ?? '/';
		$this->_option['domain']	= $option['domain'] ?? '';
		$this->_option['secure']	= $option['secure'] ?? false;
		$this->_option['httponly']	= $option['httponly'] ?? false;

		if (!empty($_COOKIE)) {
			foreach (array_keys($_COOKIE) as $name) {
				$this->_names[$name] = true;
			}
		}
	}

	public function send(string $key, string $value, array $option = [])
	{
		$option = array_merge($this->_option, $option);
		
		if ($option['expire'] < 0) {
			$option['expire'] = 1;
		} elseif ($option['expire'] > 0) {
			$option['expire'] = time() + $option['expire'];
		} else {
			$option['expire'] = 0;
		}

		$this->_names[$key] = true;

		return setcookie(
			$key,
			$value,
			$option['expire'],
			$option['path'],
			$option['domain'],
			$option['secure'],
			$option['httponly']
			);
	}

	public function forever(string $key, string $value, array $option = [])
	{
		$option['expire'] = 157680000;
		return $this->send($key, $value, $option);
	}

	public function interim(string $key, string $value, array $option = [])
	{
		$option['expire'] = 0;
		return $this -> send($key, $value, $option);
	}

	public function secure(string $key, string $value, array $option = [])
	{
		$option['httponly'] = true;
		$temp = base64_encode($value);
		return $this -> send('edoger_'.$key, $temp.'|'.md5($temp.$this->_secretKey), $option);
	}

	public function secureInterim(string $key, string $value, array $option = [])
	{
		$option['expire'] = 0;
		return $this -> secure($key, $value, $option);
	}

	public function secureForever(string $key, string $value, array $option = [])
	{
		$option['expire'] = 157680000;
		return $this -> secure($key, $value, $option);
	}

	public function forget(string $key)
	{
		foreach ([$key, 'edoger_'.$key] as $value) {
			if (isset($this->_names[$value]) ) {
				$this->send($value, '', ['expire' => -1]);
				unset($this->_names[$value]);
			}
		}

		return true;
	}
}