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
	private $_route;

	public function __construct(Route $route)
	{
		$this->_route = $route;
	}

	public function where($name, $filter)
	{
		$this->_route->addWhere($name, $filter);
		return $this;
	}

	public function middleware($middleware)
	{
		foreach ((array)$middleware as $mw) {
			$this->_route->addMiddleware(strtolower($mw));
		}
		
		return $this;
	}

	public function domain($domain)
	{
		foreach ((array)$domain as $dm) {
			$host = parse_url($dm, PHP_URL_HOST);
			if ($host !== false) {
				$this->_route->addDomain($host);
			}
		}
		
		return $this;
	}

	public function httpOnly()
	{
		$this->_route->setScheme('http');
		return $this;
	}

	public function httpsOnly()
	{
		$this->_route->setScheme('https');
		return $this;
	}

	public function ip($ip)
	{
		foreach ((array)$ip as $p) {
			$this->_route->addIp($p);
		}

		return $this;
	}

	public function listen($port)
	{
		$this->_route->addPort($port);
		return $this;
	}

	public function xhrOnly()
	{
		$this->_route->xhrOnly();
		return $this;
	}
}