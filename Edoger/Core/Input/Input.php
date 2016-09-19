<?php
/**
 *+----------------------------------------------------------------------------+
 *| Edoger PHP Framework (Edoger)                                              |
 *+----------------------------------------------------------------------------+
 *| Copyright (c) 2014 - 2016 QingShan Luo (Reent)                             |
 *+----------------------------------------------------------------------------+
 *| The MIT License (MIT)                                                      |
 *|                                                                            |
 *| Permission is hereby granted, free of charge, to any person obtaining a    |
 *| copy of this software and associated documentation files (the “Software”), |
 *| to deal in the Software without restriction, including without limitation  |
 *| the rights to use, copy, modify, merge, publish, distribute, sublicense,   |
 *| and/or sell copies of the Software, and to permit persons to whom the      |
 *| Software is furnished to do so, subject to the following conditions:       |
 *|                                                                            |
 *| The above copyright notice and this permission notice shall be included in |
 *| all copies or substantial portions of the Software.                        |
 *|                                                                            |
 *| THE SOFTWARE IS PROVIDED “AS IS”, WITHOUT WARRANTY OF ANY KIND,            |
 *| EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF         |
 *| MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.     |
 *| IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM,|
 *| DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR      |
 *| OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE  |
 *| USE OR OTHER DEALINGS IN THE SOFTWARE.                                     |
 *+----------------------------------------------------------------------------+
 *| License: MIT                                                               |
 *+----------------------------------------------------------------------------+
 *| Authors: QingShan Luo <shanshan.lqs@gmail.com>                             |
 *+----------------------------------------------------------------------------+
 *| Link: https://www.edoger.com/                                              |
 *+----------------------------------------------------------------------------+
 */
namespace Edoger\Core\Input;

/**
 *==============================================================================
 * 应用程序服务的核心类。
 *==============================================================================
 */
class Input
{
	const PRI_GET 		= 1;
	const PRI_POST 		= 2;
	const RANGE_GET 	= 'get';
	const RANGE_POST 	= 'post';
	const RANGE_ANY 	= 'any';

	const E_NONE = 0;
	const E_NOT_FOUND = 1;
	const E_NOT_VALID = 2;

	/**
	 * -------------------------------------------------------------------------
	 * [$filter description]
	 * -------------------------------------------------------------------------
	 * 
	 * @var [type]
	 */
	private $filter;

	/**
	 * -------------------------------------------------------------------------
	 * [$modifier description]
	 * -------------------------------------------------------------------------
	 * 
	 * @var [type]
	 */
	private $modifier;

	/**
	 * -------------------------------------------------------------------------
	 * [$priority description]
	 * -------------------------------------------------------------------------
	 * 
	 * @var [type]
	 */
	private $priority = self::PRI_POST;

	/**
	 * -------------------------------------------------------------------------
	 * [__construct description]
	 * -------------------------------------------------------------------------
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this -> filter 	= new Filter();
		$this -> modifier 	= new Modifier();
	}

	/**
	 * -------------------------------------------------------------------------
	 * [priority description]
	 * -------------------------------------------------------------------------
	 * 
	 * @param  int|integer $pri [description]
	 * @return [type]           [description]
	 */
	public function priority(int $pri = 0)
	{
		switch ($pri) {
			case self::PRI_GET:
				$this -> priority = self::PRI_GET;
				break;
			
			case self::PRI_POST:
				$this -> priority = self::PRI_POST;
				break;
		}

		return $this -> priority;
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
		return $_GET[$key] ?? $def;
	}

	/**
	 * -------------------------------------------------------------------------
	 * [query description]
	 * -------------------------------------------------------------------------
	 * 
	 * @param  string $key [description]
	 * @param  [type] $def [description]
	 * @return [type]      [description]
	 */
	public function query(string $key, $def = null)
	{
		return $this -> get($key, $def);
	}

	/**
	 * -------------------------------------------------------------------------
	 * [post description]
	 * -------------------------------------------------------------------------
	 * 
	 * @param  string $key [description]
	 * @param  [type] $def [description]
	 * @return [type]      [description]
	 */
	public function post(string $key, $def = null)
	{
		return $_POST[$key] ?? $def;
	}

	/**
	 * -------------------------------------------------------------------------
	 * [body description]
	 * -------------------------------------------------------------------------
	 * 
	 * @param  string $key [description]
	 * @param  [type] $def [description]
	 * @return [type]      [description]
	 */
	public function body(string $key, $def = null)
	{
		return $this -> post($key, $def);
	}

	/**
	 * -------------------------------------------------------------------------
	 * [any description]
	 * -------------------------------------------------------------------------
	 * 
	 * @param  string $key [description]
	 * @param  [type] $def [description]
	 * @return [type]      [description]
	 */
	public function any(string $key, $def = null)
	{
		switch ($this -> priority) {
			case self::PRI_POST:
				return $_POST[$key] ?? $_GET[$key] ?? $def;
			
			case self::PRI_GET:
				return $_GET[$key] ?? $_POST[$key] ?? $def;
		}
		
		return $def;
	}

	/**
	 * -------------------------------------------------------------------------
	 * [fetch description]
	 * -------------------------------------------------------------------------
	 * 
	 * @param  string $key [description]
	 * @param  [type] $def [description]
	 * @return [type]      [description]
	 */
	public function fetch(string $key, $def = null)
	{
		return $this -> any($key, $def);
	}

	/**
	 * -------------------------------------------------------------------------
	 * [getpost description]
	 * -------------------------------------------------------------------------
	 * 
	 * @param  string $key [description]
	 * @param  [type] $def [description]
	 * @return [type]      [description]
	 */
	public function getpost(string $key, $def = null)
	{
		return $_GET[$key] ?? $_POST[$key] ?? $def;
	}

	/**
	 * -------------------------------------------------------------------------
	 * [postget description]
	 * -------------------------------------------------------------------------
	 * 
	 * @param  string $key [description]
	 * @param  [type] $def [description]
	 * @return [type]      [description]
	 */
	public function postget(string $key, $def = null)
	{
		return $_POST[$key] ?? $_GET[$key] ?? $def;
	}

	/**
	 * -------------------------------------------------------------------------
	 * [searchGet description]
	 * -------------------------------------------------------------------------
	 * 
	 * @param  array  $keys [description]
	 * @param  [type] $def  [description]
	 * @return [type]       [description]
	 */
	public function searchGet(array $keys, $def = null)
	{
		foreach ($keys as $key) {
			if (isset($_GET[$key])) {
				return $_GET[$key];
			}
		}

		return $def;
	}

	/**
	 * -------------------------------------------------------------------------
	 * [searchPost description]
	 * -------------------------------------------------------------------------
	 * 
	 * @param  array  $keys [description]
	 * @param  [type] $def  [description]
	 * @return [type]       [description]
	 */
	public function searchPost(array $keys, $def = null)
	{
		foreach ($keys as $key) {
			if (isset($_POST[$key])) {
				return $_POST[$key];
			}
		}
		
		return $def;
	}

	/**
	 * -------------------------------------------------------------------------
	 * [search description]
	 * -------------------------------------------------------------------------
	 * 
	 * @param  array  $keys [description]
	 * @param  [type] $def  [description]
	 * @return [type]       [description]
	 */
	public function search(array $keys, $def = null)
	{
		switch ($this -> priority) {
			case self::PRI_POST:
				foreach ($keys as $key) {
					if (isset($_POST[$key])) {
						return $_POST[$key];
					} elseif (isset($_GET[$key])) {
						return $_GET[$key];
					}
				}
				break;

			case self::PRI_GET:
				foreach ($keys as $key) {
					if (isset($_GET[$key])) {
						return $_GET[$key];
					} elseif (isset($_POST[$key])) {
						return $_POST[$key];
					}
				}
				break;
		}
		
		return $def;
	}

	/**
	 * -------------------------------------------------------------------------
	 * [existsGet description]
	 * -------------------------------------------------------------------------
	 * 
	 * @param  string $key [description]
	 * @return [type]      [description]
	 */
	public function existsGet(string $key)
	{
		return isset($_GET[$key]);
	}

	/**
	 * -------------------------------------------------------------------------
	 * [existsPost description]
	 * -------------------------------------------------------------------------
	 * 
	 * @param  string $key [description]
	 * @return [type]      [description]
	 */
	public function existsPost(string $key)
	{
		return isset($_POST[$key]);
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
		return isset($_POST[$key]) || isset($_GET[$key]);
	}

	/**
	 * -------------------------------------------------------------------------
	 * [capture description]
	 * -------------------------------------------------------------------------
	 * 
	 * @param  string $key      [description]
	 * @param  string $range    [description]
	 * @param  [type] $filter   [description]
	 * @param  [type] $modifier [description]
	 * @return [type]           [description]
	 */
	public function capture(string $key, string $range = '', $filter = null, $modifier = null)
	{
		$retu = [self::E_NONE];

		if ($range === self::RANGE_POST) {
			$retu[1] = $_POST[$key] ?? null;
		} elseif ($range === self::RANGE_GET) {
			$retu[1] = $_GET[$key] ?? null;
		} else {
			switch ($this -> priority) {
				case self::PRI_POST:
					$retu[1] = $_POST[$key] ?? $_GET[$key] ?? null;
					break;

				case self::PRI_GET:
					$retu[1] = $_GET[$key] ?? $_POST[$key] ?? null;
					break;

				default:
					$retu[1] = null;
					break;
			}
		}
		
		if (is_null($retu[1])) {
			$retu[0] = self::E_NOT_FOUND;
		} else {
			if ($filter && !$this -> filter -> call($retu[1], $filter)) {
				$retu[0] = self::E_NOT_VALID;
			} else {
				if ($modifier) {
					$retu[1] = $this -> modifier -> call($retu[1], $modifier);
				}
			}
		}

		return $retu;
	}

	/**
	 * -------------------------------------------------------------------------
	 * [optional description]
	 * -------------------------------------------------------------------------
	 * 
	 * @param  array         $keys     [description]
	 * @param  callable|null $handler  [description]
	 * @param  [type]        $argument [description]
	 * @return [type]                  [description]
	 */
	public function optional(array $keys, callable $handler = null, $argument = null)
	{
		$input = [];

		foreach ($keys as $alias => $query) {

			$query = array_pad((array)$query, 5, null);
			if (is_null($query[1]) || !is_string($query[1])) {
				$query[1] = self::RANGE_ANY;
			}

			if (is_numeric($alias)) {
				$alias = $query[0];
			}

			list($code, $data) = $this -> capture($query[0], $query[1], $query[2], $query[3]);
			
			if ($code === self::E_NONE) {
				$input[$alias] = $data;
			} elseif ($code === self::E_NOT_FOUND) {
				if (!is_null($query[4])) {
					$input[$alias] = $query[4];
				}
			} else {
				if ($handler) {
					call_user_func($handler, $code, $query[0], $argument);
				}
				$input = [];
				break;
			}
		}

		return $input;
	}

	/**
	 * -------------------------------------------------------------------------
	 * [obligatory description]
	 * -------------------------------------------------------------------------
	 * 
	 * @param  array         $keys     [description]
	 * @param  callable|null $handler  [description]
	 * @param  [type]        $argument [description]
	 * @return [type]                  [description]
	 */
	public function obligatory(array $keys, callable $handler = null, $argument = null)
	{
		$input 	= [];

		foreach ($keys as $alias => $query) {

			$query = array_pad((array)$query, 4, null);
			if (is_null($query[1]) || !is_string($query[1])) {
				$query[1] = self::RANGE_ANY;
			}

			if (is_numeric($alias)) {
				$alias = $query[0];
			}

			list($code, $data) = $this -> capture($query[0], $query[1], $query[2], $query[3]);
			
			if ($code === self::E_NONE) {
				$input[$alias] = $data;
			} else {
				if ($handler) {
					call_user_func($handler, $code, $query[0], $argument);
				}
				$input = [];
				break;
			}
		}

		return $input;
	}

	/**
	 * -------------------------------------------------------------------------
	 * [magic description]
	 * -------------------------------------------------------------------------
	 * 
	 * @param  array         $keys     [description]
	 * @param  callable|null $handler  [description]
	 * @param  [type]        $argument [description]
	 * @return [type]                  [description]
	 */
	public function magic(array $keys, callable $handler = null, $argument = null)
	{
		$input 	= [];

		foreach ($keys as $alias => $query) {

			$query = array_pad((array)$query, 6, null);
			if (is_null($query[1]) || !is_string($query[1])) {
				$query[1] = self::RANGE_ANY;
			}

			if (is_numeric($alias)) {
				$alias = $query[0];
			}

			list($code, $data) = $this -> capture($query[0], $query[1], $query[2], $query[3]);
			
			if ($code === self::E_NONE) {
				$input[$alias] = $data;
			} else {
				if ($code === self::E_NOT_FOUND && $query[4]) {
					if (!is_null($query[5])) {
						$input[$alias] = $query[5];
					}
					continue;
				}
				if ($handler) {
					call_user_func($handler, $code, $query[0], $argument);
				}
				$input = [];
				break;
			}
		}

		return $input;
	}

	/**
	 * -------------------------------------------------------------------------
	 * [registerFilter description]
	 * -------------------------------------------------------------------------
	 * 
	 * @param  string   $name    [description]
	 * @param  callable $handler [description]
	 * @return [type]            [description]
	 */
	public static function registerFilter(string $name, callable $handler)
	{
		return $this -> filter -> register($name, $handler);
	}

	/**
	 * -------------------------------------------------------------------------
	 * [registerModifier description]
	 * -------------------------------------------------------------------------
	 * 
	 * @param  string   $name    [description]
	 * @param  callable $handler [description]
	 * @return [type]            [description]
	 */
	public static function registerModifier(string $name, callable $handler)
	{
		return $this -> modifier -> register($name, $handler);
	}
}