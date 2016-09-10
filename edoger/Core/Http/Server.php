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
namespace Edoger\Core\Http;


/**
 * =============================================================================
 * 针对全局变量 $_SERVER 的访问管理器
 * =============================================================================
 */
final class Server
{
	/**
	 * -------------------------------------------------------------------------
	 * 读取并返回一个指定键名的数据
	 * -------------------------------------------------------------------------
	 * 
	 * @param  string 	$key 	要读取的键
	 * @param  string 	$def 	缺省值
	 * @return mixed
	 */
	public static function query(string $key, string $def = '')
	{
		return $_SERVER[$key] ?? $def;
	}

	/**
	 * -------------------------------------------------------------------------
	 * 从变量中搜索多个键，只有有一个存在，立即返回键值
	 * -------------------------------------------------------------------------
	 * 
	 * @param  array 	$keys 	要查找的多个键
	 * @param  string 	$def 	缺省值
	 * @return mixed
	 */
	public static function search(array $keys, string $def = '')
	{
		foreach ($keys as $query) {
			if (isset($_SERVER[$query])) {
				return $_SERVER[$query];
			}
		}
		return $def;
	}

	/**
	 * -------------------------------------------------------------------------
	 * 检查指定的键名是否存在
	 * -------------------------------------------------------------------------
	 * 
	 * @param  string 	$key 	要检查的键名
	 * @return boolean
	 */
	public static function exists(string $key)
	{
		return isset($_SERVER[$key]);
	}

	/**
	 * -------------------------------------------------------------------------
	 * 检查指定的键值是否等于给定的值
	 * -------------------------------------------------------------------------
	 * 
	 * @param  string 	$key   	要比较的键名
	 * @param  string 	$value 	需要比较值
	 * @return boolean
	 */
	public static function equal(string $key, string $value)
	{
		return self::query($key) === $value
	}
}