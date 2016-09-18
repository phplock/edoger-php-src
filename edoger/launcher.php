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

/**
 *+----------------------------------------------------------------------------+
 *| 框架的根目录                                                               |
 *+----------------------------------------------------------------------------+
 */
const EDOGER_ROOT = __DIR__;

/**
 *+----------------------------------------------------------------------------+
 *| 框架的版本字符串                                                           |
 *+----------------------------------------------------------------------------+
 */
const EDOGER_VERSION = '1.0.0';

/**
 *+----------------------------------------------------------------------------+
 *| 框架的版本字数值                                                           |
 *+----------------------------------------------------------------------------+
 */
const EDOGER_VERSION_INTEGER = 10000;

/**
 *+----------------------------------------------------------------------------+
 *| 加载自动加载组件                                                           |
 *+----------------------------------------------------------------------------+
 */
require EDOGER_ROOT . '/autoload.php';

/**
 *+----------------------------------------------------------------------------+
 *| 注册框架提供的自动加载程序                                                 |
 *+----------------------------------------------------------------------------+
 */
spl_autoload_register([new Edoger\EdogerAutoload(), 'load'], true, true);

/**
 *+----------------------------------------------------------------------------+
 *| 绑定框架内部的自动加载规则                                                 |
 *+----------------------------------------------------------------------------+
 */
Edoger\EdogerAutoload::bind(
	function($className, $root){
		return $root . str_replace('\\', '/', $className) . '.php';
	},
	dirname(EDOGER_ROOT) . '/'
);

/**
 *+----------------------------------------------------------------------------+
 *| 初始化框架核心，返回框架核心实例                                           |
 *+----------------------------------------------------------------------------+
 */
return new Edoger\Core\Kernel(
	new Config(EDOGER_ROOT . '/../config/edoger.php')
	);
