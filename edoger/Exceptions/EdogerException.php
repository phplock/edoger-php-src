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
namespace Edoger\Exceptions;

use Exception;

/**
 *+----------------------------------------------------------------------------+
 *| 框架的通用异常类                                                           |
 *+----------------------------------------------------------------------------+
 *| 在任何情况下，框架内部的自定义异常都应当继承这个通用异常类。               |
 *|                                                                            |
 *| 异常码与日志级别对应关系：                                                 |
 *|                                                                            |
 *|                  code <= 5000000     ERROR                                 |
 *|        5000000 < code <= 6000000     WARNING                               |
 *|        6000000 < code <= 7000000     NOTICE                                |
 *|        7000000 < code <= 8000000     INFO                                  |
 *|        8000000 < code <= 9000000     DEBUG                                 |
 *|        9000000 < code                ALERT                                 |
 *|                                                                            |
 *| 任何异常都将导致应用程序立即执行退出动作，这个特性不同于系统错误对应的日志 |
 *| 级别，系统错误只有在达到 ERROR 级别之后才会触发应用程序的退出动作。        |
 *|                                                                            |
 *| 注意：如果异常被捕获，那么应用程序退出动作不会被触发。                     |
 *+----------------------------------------------------------------------------+
 */
class EdogerException extends Exception
{
	//
}