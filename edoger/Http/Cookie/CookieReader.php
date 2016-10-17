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

class CookieReader
{
	private $_cookie = [];

	public function __construct(string $secretKey = '')
	{
		if (!empty($_COOKIE)) {
			foreach ($_COOKIE as $key => $value) {
				if (substr($key, 0, 7) === 'edoger_') {
					$key	= substr($key, 7);
					$temp	= explode('|', $value);
					$text	= reset($temp);
					$sign	= end($temp);
					if (md5($text.$secretKey) === $sign) {
						$value = base64_decode($text);
						if ($value !== false) {
							$this->_cookie[$key] = $value;
						}
					}
				} else {
					$this->_cookie[$key] = $value;
				}
			}
		}
	}

	public function get(string $key, $def = null)
	{
		return $this->_cookie[$key] ?? $def;
	}

	public function exists(string $key)
	{
		return isset($this->_cookie[$key]);
	}
}