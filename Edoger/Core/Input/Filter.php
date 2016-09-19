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
 * 输入数据过滤器管理组件。
 *==============================================================================
 */
class Filter
{
	/**
	 * -------------------------------------------------------------------------
	 * 可用的过滤器。
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
	 * @param  string   $name    [description]
	 * @param  callable $handler [description]
	 * @return [type]            [description]
	 */
	public function register(string $name, callable $handler)
	{
		$this -> pool[$name] = $handler;
		return true;
	}

	/**
	 * -------------------------------------------------------------------------
	 * 调用一个回调函数类型的过滤器
	 * -------------------------------------------------------------------------
	 * 
	 * @param  callable 		$filter 	过滤器
	 * @param  string | array   $value  	等待过滤的数据或数据组
	 * @return boolean
	 */
	private function callFunctionFilter(callable $filter, $value)
	{
		if (is_scalar($value)) {
			return (bool)call_user_func($filter, (string)$value);
		} elseif (is_array($value)) {
			foreach ($value as $v) {
				if (!$this -> callFunctionFilter($filter, $v)) {
					return false;
				}
			}
			return true;
		} else {
			return false;
		}
	}

	/**
	 * -------------------------------------------------------------------------
	 * 调用一个给定正则表达式的过滤器
	 * -------------------------------------------------------------------------
	 * 
	 * @param  string 			$filter 	过滤器，这必须是一个有效的正则表达式
	 * @param  string | array   $value  	等待过滤的数据或数据组
	 * @return boolean
	 */
	private function callPregFilter(string $filter, $value)
	{
		if (is_scalar($value)) {
			return (bool)preg_match($filter, (string)$value);
		} elseif (is_array($value)) {
			foreach ($value as $v) {
				if (!$this -> callPregFilter($filter, $v)) {
					return false;
				}
			}
			return true;
		} else {
			return false;
		}
	}

	/**
	 * -------------------------------------------------------------------------
	 * 使用给定的过滤器或过滤器组过滤给定的数据或数据组，返回数据或数据组中的全部数
	 * 据是否通过了给定的过滤器或过滤器组中的全部过滤器
	 * -------------------------------------------------------------------------
	 * 
	 * @param  callable | string | arrray 	$filter 	过滤器或过滤器组
	 * @param  string | array   			$value  	等待过滤的数据或数据组
	 * @return boolean
	 */
	public function call($filter, $value)
	{
		if (is_null($filter)) {
			return true;
		} elseif (is_callable($filter)) {
			return $this -> callFunctionFilter($filter, $value);
		} elseif (is_string($filter)) {
			if (isset($this -> pool[$filter])) {
				return $this -> callFunctionFilter($this -> pool[$filter], $value);
			} elseif (substr($filter, 0, 1) === '/') {
				return $this -> callPregFilter($filter, $value);
			} else {
				return false;
			}
		} elseif (is_array($filter)) {
			foreach ($filter as $f) {
				if (!$this -> call($f, $value)) {
					return false;
				}
			}
			return true;
		} else {
			return false;
		}
	}
}