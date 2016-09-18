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
namespace Edoger\Core\Http;

/**
 *+----------------------------------------------------------------------------+
 *| 系统输出缓冲管理组件                                                       |
 *+----------------------------------------------------------------------------+
 */
final class Output
{
	/**
	 * -------------------------------------------------------------------------
	 * 输出缓冲。
	 * -------------------------------------------------------------------------
	 * 
	 * @var array
	 */
	private static $buffer = [];
	
	/**
	 * -------------------------------------------------------------------------
	 * 初始化组件。
	 * -------------------------------------------------------------------------
	 * 
	 * @param array 	&$buffer 	等待绑定的输出缓冲变量
	 */
	public function __construct(array &$buffer)
	{
		self::$buffer = &$buffer;
		ob_start();
	}

	/**
	 * -------------------------------------------------------------------------
	 * 添加一个待输出的数据。
	 * -------------------------------------------------------------------------
	 * 
	 * @param  string $data [description]
	 * @return boolean
	 */
	public static function push(string $data)
	{
		if ($data !== '') {
			self::$buffer[] = $data;
		}
		return true;
	}

	/**
	 * -------------------------------------------------------------------------
	 * 移除最近添加的一个数据。
	 * -------------------------------------------------------------------------
	 * 
	 * @return boolean
	 */
	public static function pop()
	{
		if (!empty(self::$buffer)) {
			array_pop(self::$buffer);
		}
		return true;
	}

	/**
	 * -------------------------------------------------------------------------
	 * 清除所有待输出数据。
	 * -------------------------------------------------------------------------
	 * 
	 * @return boolean
	 */
	public static function clean()
	{
		self::$buffer = [];
		return true;
	}

	/**
	 * -------------------------------------------------------------------------
	 * 获取待输出数据的数量。
	 * -------------------------------------------------------------------------
	 * 
	 * @return integer
	 */
	public static function length()
	{
		return count(self::$buffer);
	}

	/**
	 * -------------------------------------------------------------------------
	 * 检查待输出数据是否为空。
	 * -------------------------------------------------------------------------
	 * 
	 * @return boolean
	 */
	public static function isEmpty()
	{
		return empty(self::$buffer);
	}
}