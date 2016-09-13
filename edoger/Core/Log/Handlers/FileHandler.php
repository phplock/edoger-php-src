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
namespace Edoger\Core\Log\Handlers;

/**
 *+----------------------------------------------------------------------------+
 *| 日志记录器，用于将日志记录到指定的文件。                                   |
 *+----------------------------------------------------------------------------+
 *| 必须的配置项：                                                             |
 *|                                                                            |
 *|        dir    日志保存的目录，这个目录一定是基于站点根目录的               |
 *|        format 日志文件名的日期格式化字符串                                 |
 *|                                                                            |
 *+----------------------------------------------------------------------------+
 */
class FileHandler
{
	/**
	 * -------------------------------------------------------------------------
	 * 日志文件的路径。
	 * -------------------------------------------------------------------------
	 * 
	 * @var string
	 */
	private $file = EDOGER_ROOT;
	
	/**
	 * -------------------------------------------------------------------------
	 * 初始化日志记录器，计算日志文件路径。
	 * -------------------------------------------------------------------------
	 * 
	 * @param array 	$config 	日志记录器的配置信息
	 */
	public function __construct(array $config)
	{
		$this -> file .= $config['dir'] . '/' . date($config['format']) . '.log';
	}

	/**
	 * -------------------------------------------------------------------------
	 * 保存一条日志。
	 * -------------------------------------------------------------------------
	 * 
	 * @param  integer 	$level 		日志的级别
	 * @param  string 	$name 		日志的级别名称
	 * @param  string 	$date 		格式化后的日期字符串
	 * @param  string 	$message 	日志内容
	 * @return void
	 */
	public function save(int $level, string $name, string $date, string $message)
	{
		$log = '[' . $date . '][' . $name . ']' . $message;
		
		error_log($log . PHP_EOL, 3, $this -> file);
	}
}