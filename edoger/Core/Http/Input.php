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
	 * ----------------------------------------------------------------------------
	 * 已经加载的过滤器列表
	 * ----------------------------------------------------------------------------
	 * 
	 * @var array
	 */
	private static $filter = [];

	/**
	 * ----------------------------------------------------------------------------
	 * 以URL方式传递的参数数据
	 * ----------------------------------------------------------------------------
	 * 
	 * @var array
	 */
	private static $get = [];

	/**
	 * ----------------------------------------------------------------------------
	 * 以POST方式传递的参数数据
	 * ----------------------------------------------------------------------------
	 * 
	 * @var array
	 */
	private static $post = [];

	/**
	 * ----------------------------------------------------------------------------
	 * 合并后的来自多个渠道传递的参数数据
	 * ----------------------------------------------------------------------------
	 * 
	 * @var array
	 */
	private static $query = [];

	/**
	 * ----------------------------------------------------------------------------
	 * 初始化，绑定全局变量到组件内部
	 * ----------------------------------------------------------------------------
	 *
	 * @param  array $get  [description]
	 * @param  array $post [description]
	 * @return void
	 */
	public function __construct(array $get, array $post)
	{
		self::$get 	= $get;
		self::$post = $post;

		if (empty($get)) {
			self::$query = $post;
		} elseif (empty($post)) {
			self::$query = $get;
		} else {
			self::$query = $post + $get;
		}
	}

	/**
	 * ----------------------------------------------------------------------------
	 * 从全局 $_GET 中获取指定的数据
	 * ----------------------------------------------------------------------------
	 * 
	 * @param  string 			$key    	要获取的键名
	 * @param  mixed 			$def    	缺省值
	 * @param  string | array 	$filter 	要使用的过滤器或过滤器数组
	 * @return mixed
	 */
	public function get(string $key, $def = null, $filter = null)
	{
		if (isset(self::$get[$key])) {
			if (empty($filter)) {
				return self::$get[$key];
			} else {
				if (self::callFilter($filter, self::$get[$key])) {
					return self::$get[$key];
				} else {
					return $def;
				}
			}
		} else {
			return $def;
		}
	}

	/**
	 * ----------------------------------------------------------------------------
	 * 从全局 $_GET 中获取指定的数据
	 * ----------------------------------------------------------------------------
	 * 
	 * @param  string 			$key    	[description]
	 * @param  mixed 			$def    	[description]
	 * @param  string | array 	$filter 	[description]
	 * @return mixed
	 */
	public function post(string $key, $def = null, $filter = null)
	{
		if (isset(self::$post[$key])) {
			if (empty($filter)) {
				return self::$post[$key];
			} else {
				if (self::callFilter($filter, self::$post[$key])) {
					return self::$post[$key];
				} else {
					return $def;
				}
			}
		} else {
			return $def;
		}
	}

	/**
	 * [query description]
	 * @param  string $key    [description]
	 * @param  [type] $def    [description]
	 * @param  [type] $filter [description]
	 * @return [type]         [description]
	 */
	public function query(string $key, $def = null, $filter = null)
	{
		if (isset(self::$query[$key])) {
			if ($filter) {
				if (self::callFilter($filter, self::$query[$key])) {
					return self::$query[$key];
				} else {
					return $def;
				}
			} else {
				return self::$query[$key];
			}
		} else {
			return $def;
		}
	}

	/**
	 * [search description]
	 * @param  string $key    [description]
	 * @param  [type] $def    [description]
	 * @param  [type] $filter [description]
	 * @param  string $range  [description]
	 * @return [type]         [description]
	 */
	public function search(string $key, $def = null, $filter = null, string $range = 'any')
	{
		$input = [];

		switch ($range) {
			case 'any':
				$input = &self::$query;
				break;
			case 'get':
				$input = &self::$get;
				break;
			case 'post':
				$input = &self::$post;
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
				return isset(self::$query[$key]);
			
			case 'get':
				return isset(self::$get[$key]);

			case 'post':
				return isset(self::$post[$key]);
		}

		return false;
	}

	/**
	 * [optional description]
	 * @param  array         $keys     [description]
	 * @param  callable|null $handler  [description]
	 * @param  [type]        $argument [description]
	 * @return [type]                  [description]
	 */
	public function optional(array $keys, callable $handler = null, $argument = null)
	{
		$input 	= [];
		$errno 	= 0;
		$errstr = '';

		foreach ($keys as $k => $v) {
			$v = (array)$v;
			$m = $v[1] ?? 'any';
			if ($m === 'any') {
				$d = self::$query[$v[0]] ?? null;
			} elseif ($m === 'get') {
				$d = self::$get[$v[0]] ?? null;
			} elseif ($m === 'post') {
				$d = self::$post[$v[0]] ?? null;
			} else {
				$error 	= 1;
				$errstr = 'Unrecognized search pattern: ' . $m;
				break;
			}
			if (is_null($d)) {
				if (isset($v[3])) {
					$input[is_int($k) ? $v[0] : $k] = $v[3];
				}
			} else {
				if (isset($v[2]) && !self::callFilter($v[2], $d, $v[0])) {
					$errno 	= 2;
					$errstr = "Argument {$v[0]} is not valid";
					break;
				}
				if (isset($v[4])) {
					if (is_callable($v[4])) {
						$input[is_int($k) ? $v[0] : $k] = call_user_func($v[4], $d, $v[0]);
					} else {
						$errno 	= 4;
						$errstr = 'Modifier is not valid, with: ' . $v[0];
						break;
					}
				} else {
					$input[is_int($k) ? $v[0] : $k] = $d;
				}
			}
		}

		if ($errno) {
			if ($handler) {
				call_user_func($handler, $v[0], $errno, $errstr, $argument);
			}
			return false;
		}

		return $input;
	}

	/**
	 * [obligatory description]
	 * @param  array         $keys     [description]
	 * @param  callable|null $handler  [description]
	 * @param  [type]        $argument [description]
	 * @return [type]                  [description]
	 */
	public function obligatory(array $keys, callable $handler = null, $argument = null)
	{
		$input 	= [];
		$errno 	= 0;
		$errstr = '';

		foreach ($keys as $k => $v) {
			$v = (array)$v;
			$m = $v[1] ?? 'any';
			if ($m === 'any') {
				$d = self::$query[$v[0]] ?? null;
			} elseif ($m === 'get') {
				$d = self::$get[$v[0]] ?? null;
			} elseif ($m === 'post') {
				$d = self::$post[$v[0]] ?? null;
			} else {
				$error 	= 1;
				$errstr = 'Unrecognized search pattern: ' . $m;
				break;
			}
			if (is_null($d)) {
				$errno 	= 8;
				$errstr = "Argument {$v[0]} does not exist";
				break;
			} else {
				if (isset($v[2]) && !self::callFilter($v[2], $d, $v[0])) {
					$errno 	= 2;
					$errstr = "Argument {$v[0]} is not valid";
					break;
				}
				if (isset($v[3])) {
					if (is_callable($v[3])) {
						$input[is_int($k) ? $v[0] : $k] = call_user_func($v[3], $d, $v[0]);
					} else {
						$errno 	= 4;
						$errstr = 'Modifier is not valid, with: ' . $v[0];
						break;
					}
				} else {
					$input[is_int($k) ? $v[0] : $k] = $d;
				}
			}
		}

		if ($errno) {
			if ($handler) {
				call_user_func($handler, $v[0], $errno, $errstr, $argument);
			}
			return false;
		}

		return $input;
	}

	/**
	 * [magic description]
	 * @param  array         $keys     [description]
	 * @param  callable|null $handler  [description]
	 * @param  [type]        $argument [description]
	 * @return [type]                  [description]
	 */
	public function magic(array $keys, callable $handler = null, $argument = null)
	{
		$input 	= [];
		$errno 	= 0;
		$errstr = '';

		foreach ($keys as $k => $v) {
			$v = (array)$v;
			$m = $v[2] ?? 'any';
			if ($m === 'any') {
				$d = self::$query[$v[0]] ?? null;
			} elseif ($m === 'get') {
				$d = self::$get[$v[0]] ?? null;
			} elseif ($m === 'post') {
				$d = self::$post[$v[0]] ?? null;
			} else {
				$error 	= 1;
				$errstr = 'Unrecognized search pattern: ' . $m;
				break;
			}
			if (is_null($d)) {
				if (isset($v[1]) && !$v[1]) {
					if (isset($v[4])) {
						$input[is_int($k) ? $v[0] : $k] = $v[4];
					}
				} else {
					$errno 	= 8;
					$errstr = "Argument {$v[0]} does not exist";
					break;
				}
			} else {
				if (isset($v[3]) && !self::callFilter($v[3], $d, $v[0])) {
					$errno 	= 2;
					$errstr = "Argument {$v[0]} is not valid";
					break;
				}
				if (isset($v[5])) {
					if (is_callable($v[5])) {
						$input[is_int($k) ? $v[0] : $k] = call_user_func($v[5], $d, $v[0]);
					} else {
						$errno 	= 4;
						$errstr = 'Modifier is not valid, with: ' . $v[0];
						break;
					}
				} else {
					$input[is_int($k) ? $v[0] : $k] = $d;
				}
			}
		}

		if ($errno) {
			if ($handler) {
				call_user_func($handler, $v[0], $errno, $errstr, $argument);
			}
			return false;
		}

		return $input;
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