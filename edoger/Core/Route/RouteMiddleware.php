<?php
/*
 +-----------------------------------------------------------------------------+
 | Edoger PHP Framework (EdogerPHP)                                            |
 +-----------------------------------------------------------------------------+
 | Copyright (c) 2014 - 2016 QingShan Luo                                      |
 +-----------------------------------------------------------------------------+
 | The MIT License (MIT)                                                       |
 |                                                                             |
 | Permission is hereby granted, free of charge, to any person obtaining a     |
 | copy of this software and associated documentation files (the “Software”),  |
 | to deal in the Software without restriction, including without limitation   |
 | the rights to use, copy, modify, merge, publish, distribute, sublicense,    |
 | and/or sell copies of the Software, and to permit persons to whom the       |
 | Software is furnished to do so, subject to the following conditions:        |
 |                                                                             |
 | The above copyright notice and this permission notice shall be included in  |
 | all copies or substantial portions of the Software.                         |
 |                                                                             |
 | THE SOFTWARE IS PROVIDED “AS IS”, WITHOUT WARRANTY OF ANY KIND,             |
 | EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF          |
 | MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.      |
 | IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, |
 | DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR       |
 | OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE   |
 | USE OR OTHER DEALINGS IN THE SOFTWARE.                                      |
 +-----------------------------------------------------------------------------+
 |  License: MIT                                                               |
 +-----------------------------------------------------------------------------+
 |  Authors: QingShan Luo <shanshan.lqs@gmail.com>                             |
 +-----------------------------------------------------------------------------+
 */
namespace Edoger\Core\Route;


/**
 * =============================================================================
 *
 * =============================================================================
 */
class RouteMiddleware
{
	/**
	 * -------------------------------------------------------------------------
	 * [$middlewareList description]
	 * -------------------------------------------------------------------------
	 * 
	 * @var array
	 */
	private static $middlewareList = [];

	/**
	 * -------------------------------------------------------------------------
	 * [$middlewareNamespace description]
	 * -------------------------------------------------------------------------
	 * 
	 * @var string
	 */
	private static $middlewareNamespace = '';
	
	/**
	 * -------------------------------------------------------------------------
	 * [__construct description]
	 * -------------------------------------------------------------------------
	 * 
	 * @param string $namespace [description]
	 */
	public static function setMiddlewareNamespace(string $namespace)
	{
		self::$middlewareNamespace = $namespace;
	}

	/**
	 * -------------------------------------------------------------------------
	 * [getMiddlewareInstance description]
	 * -------------------------------------------------------------------------
	 * 
	 * @param  string $name [description]
	 * @return [type]       [description]
	 */
	private static function getMiddlewareInstance(string $name)
	{
		$className 	= self::$middlewareNamespace . '\\' . $name;
		$key 		= md5($className);

		if (!isset(self::$middlewareList[$key])) {
			self::$middlewareList[$key] = new $className();
		}

		return self::$middlewareList[$key];
	}

	/**
	 * -------------------------------------------------------------------------
	 * [run description]
	 * -------------------------------------------------------------------------
	 * 
	 * @param  string $name [description]
	 * @return [type]       [description]
	 */
	public static function run(string $name)
	{
		return (bool)self::getMiddlewareInstance($name) -> handle(
			Routing::getManager()
			);
	}
}