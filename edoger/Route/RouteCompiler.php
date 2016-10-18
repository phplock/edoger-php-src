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

// Routing compiler.
// Through the compiler, to obtain the matching resources related to the routing.
class RouteCompiler
{
	private $_route;
	
	public function __construct(array $method, $uri, $action)
	{
		$this->_route = new Route($method, $uri, $action);
	}

	public function getRoute()
	{
		return $this->_route;
	}
}