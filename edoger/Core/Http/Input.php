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
namespace Edoger\Core\Http;


class Input
{
	/**
	 * [$filter description]
	 * @var array
	 */
	private static $filter = [];

	/**
	 * [$get description]
	 * @var [type]
	 */
	private $get;

	/**
	 * [$post description]
	 * @var [type]
	 */
	private $post;

	/**
	 * [__construct description]
	 */
	public function __construct()
	{
		if (!empty($_GET)) {
			$this -> get = $_GET;
		}

		if (!empty($_POST)) {
			$this -> post = $_POST;
		}
	}

	/**
	 * [get description]
	 * @param  string $key    [description]
	 * @param  [type] $def    [description]
	 * @param  [type] $filter [description]
	 * @return [type]         [description]
	 */
	public function get(string $key, $def = null, $filter = null)
	{
		if (isset($this -> get[$key])) {
			if ($filter) {
				if (self::callFilter($filter, $this -> get[$key])) {
					return $this -> get[$key];
				} else {
					return $def;
				}
			} else {
				return $this -> get[$key];
			}
		} else {
			return $def;
		}
	}

	/**
	 * [post description]
	 * @param  string $key    [description]
	 * @param  [type] $def    [description]
	 * @param  [type] $filter [description]
	 * @return [type]         [description]
	 */
	public function post(string $key, $def = null, $filter = null)
	{
		if (isset($this -> post[$key])) {
			if ($filter) {
				if (self::callFilter($filter, $this -> post[$key])) {
					return $this -> post[$key];
				} else {
					return $def;
				}
			} else {
				return $this -> post[$key];
			}
		} else {
			return $def;
		}
	}

	/**
	 * [fetch description]
	 * @param  string $key    [description]
	 * @param  [type] $def    [description]
	 * @param  [type] $filter [description]
	 * @return [type]         [description]
	 */
	public function fetch(string $key, $def = null, $filter = null)
	{
		if (isset($this -> get[$key])) {
			if ($filter) {
				if (self::callFilter($filter, $this -> get[$key])) {
					return $this -> get[$key];
				} else {
					return $def;
				}
			} else {
				return $this -> get[$key];
			}
		} elseif (isset($this -> post[$key])) {
			if ($filter) {
				if (self::callFilter($filter, $this -> post[$key])) {
					return $this -> post[$key];
				} else {
					return $def;
				}
			} else {
				return $this -> post[$key];
			}
		} else {
			return $def;
		}
	}

	/**
	 * [optional description]
	 * @param  array   $keys    [description]
	 * @param  boolean $pattern [description]
	 * @return [type]           [description]
	 */
	public function optional(array $keys, bool $pattern = false)
	{
		$input = [];

		if (empty($keys)) {
			return $input;
		}

		if ($pattern) {
			$b = &$this -> get;
			$a = &$this -> post;
		} else {
			$a = &$this -> get;
			$b = &$this -> post;
		}

		$error = false;
		foreach ($keys as $k => $v) {
			$v 		= (array)$v;
			$value 	= $a[$v[0]] ?? $b[$v[0]] ?? $v[2] ?? null;
			if ($value === null) {
				continue;
			}

			if (isset($v[1]) && !self::callFilter($v[1], $value)) {
				$error = true;
				break;
			}

			$input[is_int($k) ? $v[0] : $k] = $value;
		}

		return $error ? false : $input;
	}

	/**
	 * [obligatory description]
	 * @param  array   $keys    [description]
	 * @param  boolean $pattern [description]
	 * @return [type]           [description]
	 */
	public function obligatory(array $keys, bool $pattern = false)
	{
		$input = [];

		if (empty($keys)) {
			return $input;
		}

		if ($pattern) {
			$b = &$this -> get;
			$a = &$this -> post;
		} else {
			$a = &$this -> get;
			$b = &$this -> post;
		}

		$error = false;
		foreach ($keys as $k => $v) {
			$v 		= (array)$v;
			$value 	= $a[$v[0]] ?? $b[$v[0]] ?? null;

			if ($value === null) {
				$error = true;
				break;
			}

			if (isset($v[1]) && !self::callFilter($v[1], $value)) {
				$error = true;
				break;
			}

			$input[is_int($k) ? $v[0] : $k] = $value;
		}

		return $error ? false : $input;
	}

	/**
	 * [magic description]
	 * @param  array  $keys [description]
	 * @return [type]       [description]
	 */
	public function magic(array $keys)
	{
		$input = [];

		if (empty($keys)) {
			return $input;
		}
	}

	/**
	 * [search description]
	 * @param  string       $key     [description]
	 * @param  [type]       $def     [description]
	 * @param  string       $range   [description]
	 * @param  bool|boolean $pattern [description]
	 * @return [type]                [description]
	 */
	public function search(string $key, $def = null, string $range = 'any', bool $pattern = false)
	{
		$input = [];

		switch ($range) {
			case 'any':
				if ($pattern) {
					$input = $this -> post + $this -> get;
				} else {
					$input = $this -> get + $this -> post;
				}
				break;
			
			case 'get':
				$input = &$this -> get;
				break;

			case 'post':
				$input = &$this -> post;
				break;
		}

		if (!empty($input)) {
			foreach (explode('|', $key) as $k) {
				if (isset($input[$k])) {
					return $input[$k];
				}
			}
		}

		return $def;
	}

	/**
	 * [exists description]
	 * @param  string $key   [description]
	 * @param  string $range [description]
	 * @return [type]        [description]
	 */
	public function exists(string $key, string $range = 'any')
	{
		switch ($range) {
			case 'any':
				return isset($this -> get[$key]) || isset($this -> post[$key]);
			
			case 'get':
				return isset($this -> get[$key]);

			case 'post':
				return isset($this -> post[$key]);
		}

		return false;
	}

	/**
	 * [registerFilter description]
	 * @return [type] [description]
	 */
	public static function registerFilter()
	{

	}

	/**
	 * [callFilter description]
	 * @param  [type] $filter [description]
	 * @param  [type] $value  [description]
	 * @return [type]         [description]
	 */
	private static function callFilter($filter, $value)
	{
		if (is_callable($filter)) {
			return (bool)call_user_func($filter, $value);
		} elseif (is_string($filter)) {
			if (isset(self::$filter[$filter])) {
				return (bool)call_user_func(self::$filter[$filter], $value);
			} elseif (
				substr($filter, 0, 1) === '/' && substr($filter, -1) === '/'
				) {
				return (bool)preg_match($filter, $value);
			}
		}
		return false;
	}
}