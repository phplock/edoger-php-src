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
class Compiler
{
	private $_uri;
	private $_info = [];
	private $_size = 0;
	private $_action;
	private $_weight = 0;
	
	public function __construct(&$uri, &$action)
	{
		$this->_uri		= &$uri;
		$this->_action	= &$action;

		if ($uri !== '/') {
			$this->_info = preg_split('/\//', $uri, 0, PREG_SPLIT_NO_EMPTY);
			$this->_size = count($this->_info);
		}
	}

	public function weight()
	{
		return $this->_weight;
	}

	public function congruence()
	{

	}

	public function equal()
	{
		return $this->_route;
	}

	public function obligatory()
	{

	}

	public function optional()
	{

	}

	public function callAction()
	{

	}
}