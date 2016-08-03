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

use Edoger\Core\Kernel;

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
	 * [$me description]
	 * ----------------------------------------------------------------------------
	 * 
	 * @var [type]
	 */
	private static $me;

	/**
	 * ----------------------------------------------------------------------------
	 * [$kernel description]
	 * ----------------------------------------------------------------------------
	 * 
	 * @var [type]
	 */
	private $kernel;

	/**
	 * ----------------------------------------------------------------------------
	 * [__construct description]
	 * ----------------------------------------------------------------------------
	 * 
	 * @param Kernel &$kernel [description]
	 */
	public function __construct(Kernel &$kernel)
	{
		$this -> kernel = &$kernel;
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

	/**
	 * ----------------------------------------------------------------------------
	 * What is it ?
	 * ----------------------------------------------------------------------------
	 *
	 * @return type
	 */
	public static function get(string $uri, $action)
	{
		return self::match(['get'], $uri, $action);
	}

	/**
	 * ----------------------------------------------------------------------------
	 * What is it ?
	 * ----------------------------------------------------------------------------
	 *
	 * @return type
	 */
	public static function post(string $uri)
	{
		return self::match(['post'], $uri, $action);
	}

	/**
	 * ----------------------------------------------------------------------------
	 * What is it ?
	 * ----------------------------------------------------------------------------
	 *
	 * @return type
	 */
	public static function put(string $uri)
	{
		return self::match(['put'], $uri, $action);
	}

	/**
	 * ----------------------------------------------------------------------------
	 * What is it ?
	 * ----------------------------------------------------------------------------
	 *
	 * @return type
	 */
	public static function head(string $uri)
	{
		return self::match(['head'], $uri, $action);
	}

	/**
	 * ----------------------------------------------------------------------------
	 * What is it ?
	 * ----------------------------------------------------------------------------
	 *
	 * @return type
	 */
	public static function delete(string $uri)
	{
		return self::match(['delete'], $uri, $action);
	}

	/**
	 * ----------------------------------------------------------------------------
	 * What is it ?
	 * ----------------------------------------------------------------------------
	 *
	 * @return type
	 */
	public static function connect(string $uri)
	{
		return self::match(['connect'], $uri, $action);
	}

	/**
	 * ----------------------------------------------------------------------------
	 * What is it ?
	 * ----------------------------------------------------------------------------
	 *
	 * @return type
	 */
	public static function options(string $uri)
	{
		return self::match(['options'], $uri, $action);
	}

	/**
	 * ----------------------------------------------------------------------------
	 * What is it ?
	 * ----------------------------------------------------------------------------
	 *
	 * @return type
	 */
	public static function trace(string $uri)
	{
		return self::match(['trace'], $uri, $action);
	}

	/**
	 * ----------------------------------------------------------------------------
	 * What is it ?
	 * ----------------------------------------------------------------------------
	 *
	 * @return type
	 */
	public static function any(string $uri)
	{
		return self::match(
			['get','post','put','head','delete','connect','options','trace'],
			$uri,
			$action
			);
	}
}