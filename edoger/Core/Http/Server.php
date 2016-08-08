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


/**
 * ================================================================================
 * 针对全局变量 $_SERVER 的访问管理器
 * ================================================================================
 */
final class Server
{
	/**
	 * ----------------------------------------------------------------------------
	 * 全局变量 $_SERVER 的引用
	 * ----------------------------------------------------------------------------
	 *
	 * @var array
	 */
	private $server = [];
	
	/**
	 * ----------------------------------------------------------------------------
	 * 初始化组件本身，绑定全局变量 $_SERVER 的引用
	 * ----------------------------------------------------------------------------
	 *
	 * @return void
	 */
	public function __construct()
	{
		if (!empty($_SERVER)) {
			$this -> server = &$_SERVER;
		}
	}

	/**
	 * ----------------------------------------------------------------------------
	 * 读取并返回一个指定键名的数据
	 * ----------------------------------------------------------------------------
	 * 
	 * @param  string 	$key 	要读取的键
	 * @param  mixed 	$def 	缺省值
	 * @return mixed
	 */
	public function query(string $key, $def = null)
	{
		return $this -> server[$key] ?? $def;
	}

	/**
	 * ----------------------------------------------------------------------------
	 * 从变量中搜索多个键，只有有一个存在，立即返回键值
	 * ----------------------------------------------------------------------------
	 * 
	 * @param  array 	$keys 	要查找的多个键，用 "|" 连接的字符串
	 * @param  mixed 	$def 	缺省值
	 * @return mixed
	 */
	public function search(array $keys, $def = null)
	{
		foreach ($keys as $query) {
			if (isset($this -> server[$query])) {
				return $this -> server[$query];
			}
		}
		return $def;
	}

	/**
	 * ----------------------------------------------------------------------------
	 * 检查指定的键名是否存在
	 * ----------------------------------------------------------------------------
	 * 
	 * @param  string 	$key 	要检查的键名
	 * @return boolean
	 */
	public function exists(string $key)
	{
		return isset($this -> server[$key]);
	}
}