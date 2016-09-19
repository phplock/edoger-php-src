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
namespace Edoger\Core\Http\Input;

/**
 * ================================================================================
 *
 * ================================================================================
 */
class Getter
{
	/**
	 * ----------------------------------------------------------------------------
	 * 查找指定名称的数据
	 * ----------------------------------------------------------------------------
	 *
	 * 方法返回一个二元索引数组，数组的第一个元素是找到的数据，第二个是一个描述内部
	 * 运行情况的整数。
	 * 
	 * @param  string 					$key      	数据名称
	 * @param  mixed 					$def      	缺省值
	 * @param  string|callable|array 	$filter   	过滤器或过滤器组
	 * @param  string|callable 			$modifier 	修改器
	 * @return array
	 */
	public static function fetch(string $key, $def = null, $filter = null, $modifier = null)
	{
		$data = [null, 0];

		if (isset($_GET[$key])) {
			if ($filter !== null && !Filter::call($filter, $_GET[$key])) {
				$data[0] = $def;
				$data[1] = 2;
			} else {
				if ($modifier === null) {
					$data[0] = $_GET[$key];
				} else {
					$data[0] = Modifier::call($modifier, $_GET[$key]);
					if ($data[1] === null) {
						$data[0] = 4;
					}
				}
			}
		} else {
			$data[0] = $def;
			$data[1] = 1;
		}

		return $data;
	}

	/**
	 * ----------------------------------------------------------------------------
	 * 按顺序搜索一组数据，只要找到一个就立即返回
	 * ----------------------------------------------------------------------------
	 *
	 * 方法返回一个三元索引数组，数组的第一个元素是找到的数据，第二个是一个描述内部
	 * 运行情况的整数，第三个元素是找到的键名称。
	 * 
	 * @param  array  $keys     [description]
	 * @param  [type] $def      [description]
	 * @param  [type] $filter   [description]
	 * @param  [type] $modifier [description]
	 * @return [type]           [description]
	 */
	public static function search(array $keys, $def = null, $filter = null, $modifier = null)
	{
		$data = [null, 0, ''];

		foreach ($keys as $v) {
			if (isset($_GET[$v])) {
				
				$data[2] = $v;

				if ($filter !== null && !Filter::call($filter, $_GET[$v])) {
					$data[0] = $def;
					$data[1] = 2;
				} else {
					if ($modifier === null) {
						$data[0] = $_GET[$v];
					} else {
						$data[0] = Modifier::call($modifier, $_GET[$v]);
						if ($data[1] === null) {
							$data[0] = 4;
						}
					}
				}

				return $data;
			}
		}

		$data[0] = $def;
		$data[1] = 1;

		return $data;
	}

	/**
	 * ----------------------------------------------------------------------------
	 * 检查指定名称的数据是否存在
	 * ----------------------------------------------------------------------------
	 * 
	 * @param  string  	$key 	数据名称
	 * @return boolean
	 */
	public static function has(string $key)
	{
		return isset($_GET[$key]);
	}
}