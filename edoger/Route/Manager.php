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

use Edoger\Core\Kernel;

class RouteManager
{
	private $_route = [];
	private $_bind = [];

	public function __construct()
	{
		Kernel::singleton()->app()->request()->path();
	}

	public function add(array $method, $uri, $action)
	{
		$uri	= '/'.trim($uri, '/');
		$method	= array_map('strtolower', $method);
		$route	= new Route($method, $uri, $action);

		$this->_route[] = $route;
		return $route->node();
	}

	public function bind($name, $mw)
	{
		if (!isset($this->_bind[$name])) {
			$this->_bind[$name] = [];
		}

		$this->_bind[$name][] = $mw;
		return $this;
	}
}