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
	private $_wheres;
	private $_middlewares;
	private $_port;
	private $_ip;
	private $_scheme;
	private $_domain;
	private $_xhr;


	public function __construct(&$wheres, &$middlewares, &$port, &$ip, &$scheme, &$domain, &$xhr)
	{
		$this->_wheres		= &$wheres;
		$this->_middlewares	= &$middlewares;
		$this->_port		= &$port;
		$this->_ip			= &$ip;
		$this->_scheme		= &$scheme;
		$this->_domain		= &$domain;
		$this->_xhr			= &$xhr;
	}

	public function where($name, $filter)
	{
		if (!isset($this->_wheres[$name])) {
			$this->_wheres[$name] = [];
		}
		$this->_wheres[$name][] = $filter;
		return $this;
	}

	public function middleware($middleware)
	{
		foreach ((array)$middleware as $mw) {
			$this->_middlewares[] = strtolower($mw);
		}
		
		return $this;
	}

	public function domain($domain)
	{
		foreach ((array)$domain as $dm) {
			$host = parse_url($dm, PHP_URL_HOST);
			if ($host !== false) {
				$this->_domain[] = $host;
			}
		}
		
		return $this;
	}

	public function scheme($scheme)
	{
		foreach ((array)$scheme as $se) {
			$this->_scheme[] = strtolower($se);
		}

		return $this;
	}

	public function httpOnly()
	{
		$this->_scheme = ['http'];
		return $this;
	}

	public function httpsOnly()
	{
		$this->_scheme = ['https'];
		return $this;
	}

	public function ip($ip)
	{
		foreach ((array)$ip as $p) {
			$this->_ip[] = $ip;
		}

		return $this;
	}

	public function listen($port)
	{
		foreach ((array)$port as $p) {
			$this->_port[] = $p;
		}
		
		return $this;
	}

	public function xhrOnly()
	{
		$this->_xhr = true;
		return $this;
	}
}