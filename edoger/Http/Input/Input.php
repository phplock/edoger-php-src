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
namespace Edoger\Http\Input;

class Input
{
	private $_get = [];
	private $_post = [];
	private $_input = [];
	public function __construct(string $method)
	{
		$this->_get = $_GET;
		$this->_post = $_POST;
		
		if (in_array($method, ['get', 'header'])) {
			$this->_input = array_merge($this->_post, $this->_get);
		} else {
			$this->_input = array_merge($this->_get, $this->_post);
		}
	}

	public function get(string $key, $def = null)
	{
		return $this->_get[$key] ?? $def;
	}

	public function post(string $key, $def = null)
	{
		return $this->_post[$key] ?? $def;
	}

	public function any(string $key, $def = null)
	{
		return $this->_input[$key] ?? $def;
	}
}