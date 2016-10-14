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
namespace Edoger\Core;

// Configuration management component.
final class Config
{
	private $_config = [];

	public function __construct(array $config)
	{
		$this->_config = $config;
	}

	public function get(string $key, $def = null)
	{
		if (isset($this->_config[$key])) {
			return $this->_config[$key];
		} else {
			if (empty($this->_config)) {
				return $def;
			}
			$config = $this->_config;
			foreach (explode('.', $key) as $query) {
				if (isset($config[$query])) {
					$config = $config[$query];
				} else {
					$config = $def;
					break;
				}
			}
			return $config;
		}
	}

	public function has(string $key)
	{
		return $this->get($key) !== null;
	}
}