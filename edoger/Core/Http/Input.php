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

use Edoger\Core\Http\Input\Getter;
use Edoger\Core\Http\Input\Poster;
use Edoger\Core\Http\Input\Filter;
use Edoger\Core\Http\Input\Modifier;

/**
 * ================================================================================
 * 客户端输入数据管理器，该管理器用于管理通过 $_GET 和 $_POST 传递的全部参数，组件
 * 为整个应用程序提供便捷安全的数据访问接口，在任何情况下，读取从客户端传递过来的数
 * 据，都应当通过该组件提供的方法来读取。
 * ================================================================================
 */
class Input
{
	const ERROR_NONE = 0;
	const ERROR_NOT_FOUND = 1;
	const ERROR_NOT_VALID = 2;
	const ERROR_ARGUMENT = 128;

	/**
	 * ----------------------------------------------------------------------------
	 * 已经加载的过滤器列表
	 * ----------------------------------------------------------------------------
	 * 
	 * @var array
	 */
	private $filter = [];



	/**
	 * ----------------------------------------------------------------------------
	 * 从全局 $_GET 中获取指定的数据
	 * ----------------------------------------------------------------------------
	 * 
	 * @param  string 			$key    	要获取的键名
	 * @param  mixed 			$def    	缺省值
	 * @param  string | array | callable 	$filter 	要使用的过滤器或过滤器数组
	 * @return mixed
	 */
	public function get(string $key, $def = null)
	{
		return $_GET[$key] ?? $def;
	}

	/**
	 * ----------------------------------------------------------------------------
	 * 从全局 $_POST 中获取指定的数据
	 * ----------------------------------------------------------------------------
	 * 
	 * @param  string 			$key    	[description]
	 * @param  mixed 			$def    	[description]
	 * @param  string | array 	$filter 	[description]
	 * @return mixed
	 */
	public function post(string $key, $def = null)
	{
		return $_POST[$key] ?? $def;
	}

	/**
	 * ----------------------------------------------------------------------------
	 * 从 $_GET 和 $_POST 中获取数据，优先级和请求方式相同
	 * ----------------------------------------------------------------------------
	 * 
	 * @param  string $key    [description]
	 * @param  [type] $def    [description]
	 * @param  [type] $filter [description]
	 * @return [type]         [description]
	 */
	public function any(string $key, $def = null)
	{
		return $_POST[$key] ?? $_GET[$key] ?? $def;
	}

	/**
	 * ----------------------------------------------------------------------------
	 * 从数据集合中搜索给定的键名，一旦被搜索到就会立即返回从而忽略其他候选搜索项
	 * ----------------------------------------------------------------------------
	 * 
	 * @param  array $keys    [description]
	 * @param  [type] $def    [description]
	 * @param  [type] $filter [description]
	 * @param  string $range  [description]
	 * @return [type]         [description]
	 */
	public function search(array $keys, $def = null, string $range = 'any')
	{
		switch ($range) {

			case 'any':
				foreach ($keys as $k) {
					if (isset($_POST[$key])) {
						return $_POST[$key];
					} elseif (isset($_GET[$key])) {
						return $_GET[$key];
					}
				}

			case 'get':
				foreach ($keys as $k) {
					if (isset($_GET[$key])) {
						return $_GET[$key];
					}
				}

			case 'post':
				foreach ($keys as $k) {
					if (isset($_POST[$key])) {
						return $_POST[$key];
					}
				}
		}

		return $def;
	}

	/**
	 * ----------------------------------------------------------------------------
	 * 检查给定名称的键是否存在于数据集合中
	 * ----------------------------------------------------------------------------
	 * 
	 * @param  string $key   [description]
	 * @param  string $range [description]
	 * @return [type]        [description]
	 */
	public function exists(string $key, string $range = 'any')
	{
		switch ($range) {
			case 'any':
				return isset($_POST[$key]) || isset($_GET[$key]);
			
			case 'get':
				return isset($_GET[$key]);

			case 'post':
				return isset($_POST[$key]);
		}

		return false;
	}

	/**
	 * ----------------------------------------------------------------------------
	 * 以可选参数模式获取一组参数
	 * ----------------------------------------------------------------------------
	 * 别名 => [参数名称，查找范围，过滤器，修改器，缺省值]
	 * 
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
	 * ----------------------------------------------------------------------------
	 * 以必选参数模式获取一组参数
	 * ----------------------------------------------------------------------------
	 * 别名 => [参数名称，查找范围，过滤器，修改器，缺省值]
	 * 
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
	 * ----------------------------------------------------------------------------
	 * 以指定的可选或者必选模式获取一组参数
	 * ----------------------------------------------------------------------------
	 * 
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
	 * ----------------------------------------------------------------------------
	 * 注册一个系统过滤器
	 * ----------------------------------------------------------------------------
	 * 
	 * @return [type] [description]
	 */
	public static function registerFilter()
	{

	}

	/**
	 * ----------------------------------------------------------------------------
	 * 内部方法，调用一个或者一组过滤器，并返回数据是否通过了过滤器
	 * ----------------------------------------------------------------------------
	 * 
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
		} elseif (is_array($filter)) {
			foreach ($filter as $f) {
				if (!self::callFilter($f, $value)) {
					return false;
				}
			}
			return true;
		} else {
			return false;
		}
	}
}