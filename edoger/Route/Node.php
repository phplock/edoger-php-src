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
namespace Edoger\Route;


class Node
{
	private $_method;
	private $_uri;
	private $_action;

	private $_where = [];
	private $_middleware = [];

	public function __construct($method, $uri, $action)
	{
		$this->_method = $method;
		$this->_uri = $uri;
		$this->_action = $action;
	}

	public function where($name, $filter)
	{

	}

	public function middleware(array $middlewares)
	{

	}
}