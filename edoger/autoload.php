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
namespace Edoger;

/**
 *==============================================================================
 * 框架提供的自动加载类，该自动加载类提供一个增加加载规则的方法。
 *==============================================================================
 */
final class EdogerAutoload
{
	/**
	 * -------------------------------------------------------------------------
	 * 已经设置的自动加载函数。
	 * -------------------------------------------------------------------------
	 * 
	 * @var array
	 */
	private static $handlers = [];
	
	/**
	 * -------------------------------------------------------------------------
	 * 自动加载器。
	 * -------------------------------------------------------------------------
	 * 
	 * @param  string 	$className 	类的名称
	 * @return void
	 */
	public function load(string $className)
	{
		foreach (self::$handlers as $handler) {
			$file = call_user_func($handler[0], $className, $handler[1]);
			if ($file && file_exists($file)) {
				require $file;
				break;
			}
		}
	}

	/**
	 * -------------------------------------------------------------------------
	 * 绑定一个加载规则回调函数。
	 * -------------------------------------------------------------------------
	 * 回调函数必须返回一个绝对路径或者空字符串，如果返回路径的文件不存在，将触
	 * 发队列中下一个回调函数。
	 * 
	 * @param  callable $handler 	回调函数
	 * @param  mixed 	$param 		回调函数的额外参数
	 * @return boolean
	 */
	public static function bind(callable $handler, $param = null)
	{
		self::$handlers[] = [$handler, $param];
		return true;
	}
}