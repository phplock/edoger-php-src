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

	private $_wheres = [];
	private $_middlewares = [];

	private $_port = [];
	private $_ip = [];
	private $_scheme = [];
	private $_domain = [];
	private $_xhr = [];

	public function __construct(array $method, $uri, $action)
	{
		$this->_methods	= array_map('strtolower', $method);
		$this->_uri		= '/'.trim($uri, '/');
		$this->_action	= $action;
	}

	public function where($name, $filter)
	{
		$this->_wheres[$name] = $filter;
		return $this;
	}

	public function middleware(array $middleware)
	{
		foreach ($middleware as $mw) {
			$this->_middlewares[] = strtolower($mw);
		}

		return $this;
	}

	public function domain($domain)
	{
		preg_match('/^https?\:\/\/(.+)/', $domain)
	}

	public function scheme()
	{

	}

	public function ip()
	{

	}

	public function listen()
	{

	}

	public function xhrOnly()
	{

	}

	public function httpOnly()
	{

	}

	public function httpsOnly()
	{

	}
}