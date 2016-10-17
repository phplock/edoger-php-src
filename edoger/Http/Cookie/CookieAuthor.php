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

	public function __construct(string $secretKey = '', array $option = [])
	{
		$this->_secretKey = $secretKey;

		$this->_option['expire']	= $option['expire'] ?? 86400;
		$this->_option['path']		= $option['path'] ?? '/';
		$this->_option['domain']	= $option['domain'] ?? '';
		$this->_option['secure']	= $option['secure'] ?? false;
		$this->_option['httponly']	= $option['httponly'] ?? false;
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
}