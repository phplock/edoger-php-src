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
 * 输入数据修改器管理组件。
 *==============================================================================
 */
class Modifier
{
	/**
	 * -------------------------------------------------------------------------
	 * [$modifierList description]
	 * -------------------------------------------------------------------------
	 * 
	 * @var array
	 */
	private $pool = [];

	/**
	 * -------------------------------------------------------------------------
	 * [register description]
	 * -------------------------------------------------------------------------
	 * 
	 * @param string       $name    [description]
	 * @param callable     $handler [description]
	 * @param bool|boolean $cover   [description]
	 */
	public function register(string $name, callable $handler)
	{
		$this -> pool[$name] = $handler;
		return true;
	}

	/**
	 * -------------------------------------------------------------------------
	 * [callFunctionModifier description]
	 * -------------------------------------------------------------------------
	 * 
	 * @param  callable $modifier [description]
	 * @param  [type]   $value    [description]
	 * @return [type]             [description]
	 */
	private function callFunctionModifier(callable $modifier, $value)
	{
		if (is_scalar($value)) {
			return call_user_func($modifier, $value);
		} elseif (is_array($value)) {
			$data = [];
			foreach ($value as $k => $v) {
				$data[$k] = $this -> callFunctionModifier($modifier, $v);
			}
			return $data;
		} else {
			return $value;
		}
	}

	/**
	 * -------------------------------------------------------------------------
	 * [call description]
	 * -------------------------------------------------------------------------
	 * 
	 * @param  [type] $modifier [description]
	 * @param  [type] $value    [description]
	 * @return [type]           [description]
	 */
	public function call($modifier, $value)
	{
		if (is_null($modifier)) {
			return $value;
		} elseif (is_callable($modifier)) {
			return $this -> callFunctionModifier($modifier, $value);
		} elseif (is_string($modifier)) {
			if (isset($this -> pool[$modifier])) {
				return $this -> callFunctionModifier($this -> pool[$modifier], $value);
			} else {
				return $value;
			}
		} elseif (is_array($modifier)) {
			$temp = $value;
			foreach ($modifier as $m) {
				$temp = $this -> call($m, $temp);
			}
			return $temp;
		} else {
			return $value;
		}
	}
}