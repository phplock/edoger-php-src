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

class Manager
{
	private $_casing;
	private $_casingRoute = null;
	private $_route = [];
	private $_bind = [];

	public function __construct()
	{
		$this->_casing = new Casing($this->_casingRoute);
	}

	public function add(array $method, $uri, $action)
	{
		$uri	= '/'.trim($uri, '/');
		$method	= array_map('strtolower', $method);
		$route	= new Route($method, $uri, $action);

		$this->_route[]		= $route;
		$this->_casingRoute	= $route;

		return $this->_casing;
	}

	public function bind($name, $mw)
	{
		if (!isset($this->_bind[$name])) {
			$this->_bind[$name] = [];
		}

		$this->_bind[$name][] = $mw;
		return $this;
	}

	public function setRouteMiddlewareNamespace()
	{

	}

	public function setParamMiddlewareNamespace()
	{
		
	}
}