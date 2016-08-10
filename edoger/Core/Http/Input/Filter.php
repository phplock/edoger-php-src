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
namespace Edoger\Core\Http\Input;


/**
 * ================================================================================
 * 输入数据过滤器管理组件，该组件用于管理和调度输入数据管理器所使用到的全部过滤器，
 * 为整个系统输入数据提供安全过滤服务和验证拦截服务
 * ================================================================================
 */
class Filter
{
	/**
	 * ----------------------------------------------------------------------------
	 * 本地通用过滤器，这些过滤器来自于系统本身自带的和用户自定义的全局过滤器，这些
	 * 过滤器在整个程序脚本执行周期内都一直存在
	 * ----------------------------------------------------------------------------
	 * 
	 * @var array
	 */
	private static $filterList = [];

	/**
	 * ----------------------------------------------------------------------------
	 * 加载一个本地过滤器组件
	 * ----------------------------------------------------------------------------
	 * 
	 * @param  string 	$group 	本地过滤器组件的名称
	 * @param  boolean 	$cover 	是否覆盖名称冲突的过滤器
	 * @return boolean
	 */
	public static function local(string $group, bool $cover = true)
	{
		//
	}

	/**
	 * ----------------------------------------------------------------------------
	 * 添加一个全局生效的用户级自定义过滤器
	 * ----------------------------------------------------------------------------
	 * 
	 * @param  string 	$name    	过滤名称
	 * @param  callable $handler 	过滤器执行函数
	 * @param  boolean 	$cover   	是否覆盖名称冲突的过滤器
	 * @return boolean
	 */
	public static function register(string $name, callable $handler, bool $cover = true)
	{
		if ($cover || !isset(self::$filterList[$name])) {
			self::$filterList[$name] = $handler;
			return true;
		}
		return false;
	}

	/**
	 * ----------------------------------------------------------------------------
	 * 调用一个回调函数类型的过滤器
	 * ----------------------------------------------------------------------------
	 * 
	 * @param  callable 		$filter 	过滤器
	 * @param  string | array   $value  	等待过滤的数据或数据组
	 * @return boolean
	 */
	private static function callFunctionFilter(callable $filter, $value)
	{
		if (is_string($value) || is_numeric($value)) {
			return (bool)call_user_func($filter, $value);
		} elseif (is_array($value) && !empty($value)) {
			foreach ($value as $v) {
				if (!self::callFunctionFilter($filter, $v)) {
					return false;
				}
			}
			return true;
		} else {
			return false;
		}
	}

	/**
	 * ----------------------------------------------------------------------------
	 * 调用一个指定名称的全局过滤器
	 * ----------------------------------------------------------------------------
	 * 
	 * @param  string 			$filter 	过滤器
	 * @param  string | array   $value  	等待过滤的数据或数据组
	 * @return boolean
	 */
	private static function callPredefinedFilter(string $filter, $value)
	{
		if (is_string($value) || is_numeric($value)) {
			return (bool)call_user_func(self::$filterList[$filter], $value);
		} elseif (is_array($value) && !empty($value)) {
			foreach ($value as $v) {
				if (!self::callPredefinedFilter($filter, $v)) {
					return false;
				}
			}
			return true;
		} else {
			return false;
		}
	}

	/**
	 * ----------------------------------------------------------------------------
	 * 调用一个给定正则表达式的过滤器
	 * ----------------------------------------------------------------------------
	 * 
	 * @param  string 			$filter 	过滤器，这必须是一个有效的正则表达式
	 * @param  string | array   $value  	等待过滤的数据或数据组
	 * @return boolean
	 */
	private static function callPregFilter(string $filter, $value)
	{
		if (is_string($value) || is_numeric($value)) {
			return (bool)preg_match($filter, $value);
		} elseif (is_array($value) && !empty($value)) {
			foreach ($value as $v) {
				if (!self::callPregFilter($filter, $v)) {
					return false;
				}
			}
			return true;
		} else {
			return false;
		}
	}

	/**
	 * ----------------------------------------------------------------------------
	 * 使用给定的过滤器或过滤器组过滤给定的数据或数据组，返回数据或数据组中的全部数
	 * 据是否通过了给定的过滤器或过滤器组中的全部过滤器
	 * ----------------------------------------------------------------------------
	 * 
	 * @param  callable | string | arrray 	$filter 	过滤器或过滤器组
	 * @param  string | array   			$value  	等待过滤的数据或数据组
	 * @return boolean
	 */
	public static function call($filter, $value)
	{
		if (is_callable($filter)) {
			return self::callFunctionFilter($filter, $value);
		} elseif (is_string($filter)) {
			if (isset(self::$filterList[$filter])) {
				return self::callPredefinedFilter($filter, $value);
			} elseif (substr($filter, 0, 1) === '/' && substr($filter, -1) === '/') {
				return self::callPregFilter($filter, $value);
			} else {
				return false;
			}
		} elseif (is_array($filter) && !empty($filter)) {
			foreach ($filter as $f) {
				if (!self::call($f, $value)) {
					return false;
				}
			}
			return true;
		} else {
			return false;
		}
	}
}