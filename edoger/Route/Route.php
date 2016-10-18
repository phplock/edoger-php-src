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
	private $_node;

	private $_wheres		= [];
	private $_middlewares	= [];
	
	private $_compiler		= null;

	public function __construct(array $method, $uri, $action)
	{
		$this->_methods	= array_map('strtolower', $method);
		$this->_uri		= '/'.trim($uri, '/');
		$this->_action	= $action;
		$this->_node	= new Node($this);
	}

	public function node()
	{
		return $this->_node;
	}

	public function getCompiler()
	{
		if (!$this->_compiler) {
			$this->_compiler = new Compiler($this->_uri, $this->_action);
		}

		return $this->_compiler;
	}

	public function getMethods()
	{
		return $this->_methods;
	}

	public function getWheres()
	{
		return $this->_wheres;
	}

	public function getMiddlewares()
	{
		return $this->_middlewares;
	}
}