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

final class Route
{
	private $_methods;
	private $_uri;
	private $_action;

	private $_where			= [];
	private $_middleware	= [];
	private $_compiler		= null;

	public function __construct(array $method, $uri, $action)
	{
		$this->_methods	= $method;
		$this->_uri		= $uri;
		$this->_action	= $action;
	}

	public function getUri()
	{
		return $this->_uri;
	}

	public function getAction()
	{
		return $this->_action;
	}

	public function setAction(callable $action)
	{
		$this->_action = $action;
		return $this;
	}

	public function getMethod()
	{
		return $this->_methods;
	}

	public function getWhere()
	{
		return $this->_where;
	}

	public function setWhere($name, $filter)
	{
		if (!isset($this->_where[$name])) {
			$this->_where[$name] = [];
		}

		$this->_where[$name][] = $filter;
		return $this;
	}

	public function getMiddleware()
	{
		return $this->_middleware;
	}

	public function setMiddleware($mw)
	{
		$this->_middleware[] = $mw;
		return $this;
	}
}