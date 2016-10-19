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

// System configuration manager.
// This is a very independent component, you can make the appropriate changes, 
// but you have to implement the "Config::get(string $key, mixed $def = null)" method.
final class Config
{
	private $_config = [];

	public function __construct()
	{
		$conf = require ROOT_PATH.'/config/edoger.config.php';

		$this->_config = $conf;
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
}