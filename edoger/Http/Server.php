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
namespace Edoger\Http;

final class Server
{
	public function get(string $key, string $def = '')
	{
		return $_SERVER[$key] ?? $def;
	}

	public function search(array $keys, string $def = '')
	{
		foreach ($keys as $key) {
			if (isset($_SERVER[$key])) {
				return $_SERVER[$key];
			}
		}

		return $def;
	}

	public function exists(string $key)
	{
		return isset($_SERVER[$key]);
	}
}