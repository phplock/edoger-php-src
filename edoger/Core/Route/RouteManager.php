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
final class RouteManager
{
	/**
	 * -------------------------------------------------------------------------
	 * [$params description]
	 * -------------------------------------------------------------------------
	 * 
	 * @var [type]
	 */
	private static $params;

	/**
	 * -------------------------------------------------------------------------
	 * [$uri description]
	 * -------------------------------------------------------------------------
	 * 
	 * @var [type]
	 */
	private static $uri;

	private static $nodes;
	private static $size;
	
	/**
	 * -------------------------------------------------------------------------
	 * [__construct description]
	 * -------------------------------------------------------------------------
	 * 
	 * @param array  &$params [description]
	 * @param string &$uri    [description]
	 */
	public function __construct(array &$params, string &$uri, array &$nodes, int &$size)
	{
		self::$params 	= &$params;
		self::$uri 		= &$uri;
		self::$nodes 	= &$nodes;
		self::$size 	= &$size;
	}

	/**
	 * -------------------------------------------------------------------------
	 * [get description]
	 * -------------------------------------------------------------------------
	 * 
	 * @param  string $key [description]
	 * @param  [type] $def [description]
	 * @return [type]      [description]
	 */
	public function get(string $key, $def = null)
	{
		return self::$params[$key] ?? $def;
	}

	/**
	 * -------------------------------------------------------------------------
	 * [set description]
	 * -------------------------------------------------------------------------
	 * 
	 * @param string       $key   [description]
	 * @param [type]       $value [description]
	 * @param bool|boolean $cover [description]
	 */
	public function set(string $key, $value, bool $cover = true)
	{
		if ($cover || !isset(self::$params[$key])) {
			self::$params[$key] = $value;
			return true;
		} else {
			return false;
		}
	}

	/**
	 * -------------------------------------------------------------------------
	 * [exists description]
	 * -------------------------------------------------------------------------
	 * 
	 * @param  string $key [description]
	 * @return [type]      [description]
	 */
	public function exists(string $key)
	{
		return isset(self::$params[$key]);
	}

	/**
	 * -------------------------------------------------------------------------
	 * [equal description]
	 * -------------------------------------------------------------------------
	 * 
	 * @param  string $key   [description]
	 * @param  [type] $value [description]
	 * @return [type]        [description]
	 */
	public function equal(string $key, $value)
	{
		return $this -> get($key) === $value;
	}

	/**
	 * -------------------------------------------------------------------------
	 * [paramsCount description]
	 * -------------------------------------------------------------------------
	 * 
	 * @return [type] [description]
	 */
	public function paramsCount()
	{
		return count(self::$params);
	}

	/**
	 * -------------------------------------------------------------------------
	 * [replace description]
	 * -------------------------------------------------------------------------
	 * 
	 * @param  array  $params [description]
	 * @return [type]         [description]
	 */
	public function replace(array $params)
	{
		self::$params = $params;
		return true;
	}

	/**
	 * -------------------------------------------------------------------------
	 * [remove description]
	 * -------------------------------------------------------------------------
	 * 
	 * @param  string $key [description]
	 * @return [type]      [description]
	 */
	public function remove(string $key)
	{
		if (isset(self::$params[$key])) {
			unset(self::$params[$key]);
		}
		return true;
	}

	/**
	 * -------------------------------------------------------------------------
	 * [removeAll description]
	 * -------------------------------------------------------------------------
	 * 
	 * @return [type] [description]
	 */
	public function removeAll()
	{
		self::$params = [];
		return true;
	}

	/**
	 * -------------------------------------------------------------------------
	 * [isEmpty description]
	 * -------------------------------------------------------------------------
	 * 
	 * @return boolean [description]
	 */
	public function isEmpty()
	{
		return empty(self::$params);
	}

	/**
	 * -------------------------------------------------------------------------
	 * [uri description]
	 * -------------------------------------------------------------------------
	 * 
	 * @return [type] [description]
	 */
	public function uri()
	{
		return self::$uri;
	}

	/**
	 * -------------------------------------------------------------------------
	 * [nodes description]
	 * -------------------------------------------------------------------------
	 * 
	 * @return [type] [description]
	 */
	public function nodes()
	{
		return self::$nodes;
	}

	/**
	 * -------------------------------------------------------------------------
	 * [node description]
	 * -------------------------------------------------------------------------
	 * 
	 * @param  int    $index [description]
	 * @return [type]        [description]
	 */
	public function node(int $index, $def = null)
	{
		if ($index >= 0) {
			return self::$nodes[$index] ?? $def;
		} else {
			return self::$nodes[self::$size + $index] ?? $def;
		}
	}

	/**
	 * -------------------------------------------------------------------------
	 * [size description]
	 * -------------------------------------------------------------------------
	 * 
	 * @return [type] [description]
	 */
	public function size()
	{
		return self::$size;
	}
}