<?php
/**
 * Edoger PHP Framework (EdogerPHP)
 * 
 * A simple and efficient PHP framework.
 *
 * By REENT (Qingshan Luo)
 * Version 1.0.0
 *
 * http://www.edoger.com/
 *
 * The MIT License (MIT)
 * Copyright (c) 2016 REENT (Qingshan Luo)
 * Permission is hereby granted, free of charge, to any person obtaining a 
 * copy of this software and associated documentation files (the “Software”), 
 * to deal in the Software without restriction, including without limitation 
 * the rights to use, copy, modify, merge, publish, distribute, sublicense, 
 * and/or sell copies of the Software, and to permit persons to whom the 
 * Software is furnished to do so, subject to the following conditions:
 * 
 * The above copyright notice and this permission notice shall be included in 
 * all copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED “AS IS”, WITHOUT WARRANTY OF ANY KIND, 
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF 
 * MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. 
 * IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, 
 * DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR 
 * OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE 
 * USE OR OTHER DEALINGS IN THE SOFTWARE.
 */
namespace Edoger\Core\Route;

use Closure;
use Edoger\Core\Kernel;
use Edoger\Core\Http\Request;

/**
 * ================================================================================
 * Some Description.
 *
 * 
 * ================================================================================
 */
final class Routing
{
	/**
	 * ----------------------------------------------------------------------------
	 * [$params description]
	 * ----------------------------------------------------------------------------
	 * 
	 * @var array
	 */
	private static $params 	= [];

	/**
	 * ----------------------------------------------------------------------------
	 * [$uri description]
	 * ----------------------------------------------------------------------------
	 * 
	 * @var string
	 */
	private static $uri 	= '';

	/**
	 * ----------------------------------------------------------------------------
	 * [$nodes description]
	 * ----------------------------------------------------------------------------
	 * 
	 * @var array
	 */
	private static $nodes 	= [];

	/**
	 * ----------------------------------------------------------------------------
	 * [$size description]
	 * ----------------------------------------------------------------------------
	 * 
	 * @var integer
	 */
	private static $size 	= 0;

	/**
	 * ----------------------------------------------------------------------------
	 * [$shared description]
	 * ----------------------------------------------------------------------------
	 * 
	 * @var array
	 */
	private static $shared 	= [];

	/**
	 * ----------------------------------------------------------------------------
	 * [$kernel description]
	 * ----------------------------------------------------------------------------
	 * 
	 * @var [type]
	 */
	private static $manager;

	/**
	 * ----------------------------------------------------------------------------
	 * [__construct description]
	 * ----------------------------------------------------------------------------
	 * 
	 * @param Kernel &$kernel [description]
	 */
	public function __construct()
	{
		$manager = new RouteManager();

		$params = &self::$params;
		$uri 	= &self::$uri;
		$nodes 	= &self::$nodes;
		$size 	= &self::$size;
		$shared = &self::$shared;

		(function() use (&$params, &$uri, &$nodes, &$size, &$shared){
			self::$params 	= &$params;
			self::$uri 		= &$uri;
			self::$nodes 	= &$nodes;
			self::$size 	= &$size;
			self::$shared 	= &$shared;
		}) -> call($manager);

		self::$manager = $manager;

		$casing = new RouteCasing(
			Request::singleton() -> hostname(),
			Request::singleton() -> port(),
			Request::singleton() -> protocol(),
			Request::singleton() -> xhr()
			);
	}

	/**
	 * ----------------------------------------------------------------------------
	 * What is it ?
	 * ----------------------------------------------------------------------------
	 *
	 * @return type
	 */
	public static function match(array $method, string $uri, $action)
	{
		$pignut = preg_split('/\//', $uri, 0, PREG_SPLIT_NO_EMPTY);

		return new Node($method, $pignut, $action);
	}
}