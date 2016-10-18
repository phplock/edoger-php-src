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


class Casing
{
	private $_route;

	public function __construct(&$route)
	{
		$this->_route = &$route;
	}

	public function where($name, $filter)
	{
		if ($this->_route) {
			$this->_route->setWhere($name, $filter);
		}

		return $this;
	}

	public function middleware($middleware)
	{
		if ($this->_route) {
			if (is_string($middleware)) {
				$this->_route->setMiddleware(strtolower($mw));
			} elseif (is_array($middleware)) {
				foreach ($middleware as $mw) {
					$this->middleware($mw);
				}
			}
		}
		
		return $this;
	}
}