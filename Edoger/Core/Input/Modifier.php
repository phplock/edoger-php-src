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
 * ================================================================================
 * 输入数据修改器管理组件，该组件用于在返回查找到的客户端传输的数据之前进行预处理，
 * 通常在转换数据类型，以及一些逻辑处理，修改器的返回值将作为最终的返回值。
 * ================================================================================
 *
 * 注意：错误的修改器或者未定义的修改器，以及当修改器处理的数据不是字符串、数字和由字
 * 符串或数字组成的数组（不能为空数组）时，修改器会返回 NULL。
 */
class Modifier
{
	/**
	 * ----------------------------------------------------------------------------
	 * [$modifierList description]
	 * ----------------------------------------------------------------------------
	 * 
	 * @var array
	 */
	private static $modifierList = [];
	
	/**
	 * ----------------------------------------------------------------------------
	 * [local description]
	 * ----------------------------------------------------------------------------
	 * 
	 * @param  string       $group [description]
	 * @param  bool|boolean $cover [description]
	 * @return [type]              [description]
	 */
	public static function local(string $group, bool $cover = true)
	{
		//
	}

	/**
	 * ----------------------------------------------------------------------------
	 * [register description]
	 * ----------------------------------------------------------------------------
	 * 
	 * @param string       $name    [description]
	 * @param callable     $handler [description]
	 * @param bool|boolean $cover   [description]
	 */
	public static function register(string $name, callable $handler, bool $cover = true)
	{
		if ($cover || !isset(self::$modifierList[$name])) {
			self::$modifierList[$name] = $handler;
			return true;
		}
		return false;
	}

	/**
	 * ----------------------------------------------------------------------------
	 * [callFunctionModifier description]
	 * ----------------------------------------------------------------------------
	 * 
	 * @param  callable $modifier [description]
	 * @param  [type]   $value    [description]
	 * @return [type]             [description]
	 */
	private static function callFunctionModifier(callable $modifier, $value)
	{
		if (is_string($value) || is_numeric($value)) {
			return call_user_func($modifier, $value);
		} elseif (is_array($value) && !empty($value)) {
			$data = [];
			foreach ($value as $k => $v) {
				$data[$k] = self::callFunctionModifier($modifier, $v);
			}
			return $data;
		} else {
			return $value;
		}
	}

	/**
	 * ----------------------------------------------------------------------------
	 * [callPredefinedModifier description]
	 * ----------------------------------------------------------------------------
	 * 
	 * @param  string $modifier [description]
	 * @param  [type] $value    [description]
	 * @return [type]           [description]
	 */
	private static function callPredefinedModifier(string $modifier, $value)
	{
		if (is_string($value) || is_numeric($value)) {
			return call_user_func(self::$modifierList[$modifier], $value);
		} elseif (is_array($value) && !empty($value)) {
			$data = [];
			foreach ($value as $k => $v) {
				$data[$k] = self::callPredefinedModifier($modifier, $v);
			}
			return $data;
		} else {
			return $value;	
		}
	}

	/**
	 * ----------------------------------------------------------------------------
	 * [call description]
	 * ----------------------------------------------------------------------------
	 * 
	 * @param  [type] $modifier [description]
	 * @param  [type] $value    [description]
	 * @return [type]           [description]
	 */
	public static function call($modifier, $value)
	{
		if (is_null($modifier)) {
			return $value;
		} elseif (is_callable($modifier)) {
			return self::callFunctionModifier($modifier, $value);
		} elseif (is_string($modifier) && isset(self::$modifierList[$modifier])) {
			return self::callPredefinedModifier($modifier, $value);
		} else {
			return $value;
		}
	}
}