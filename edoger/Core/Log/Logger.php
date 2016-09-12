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
namespace Edoger\Core\Log;

/**
 *+----------------------------------------------------------------------------+
 *| 系统日志记录与管理组件                                                     |
 *+----------------------------------------------------------------------------+
 *| 
 *+----------------------------------------------------------------------------+
 */
final class Logger
{
	/**
	 * -------------------------------------------------------------------------
	 * 表示日志的 DEBUG 级别，这个级别通常用于开发环境，用以记录一些帮助调试的日
	 * 志信息。
	 * 例如：组件的加载情况和运行情况等。
	 * -------------------------------------------------------------------------
	 */
	const LEVEL_DEBUG = 1;

	/**
	 * -------------------------------------------------------------------------
	 * 表示日志的 INFO 级别，这个级别通常用于记录一些系统常规的运行情况。
	 * 例如：SQL的执行记录等。
	 * -------------------------------------------------------------------------
	 */
	const LEVEL_INFO = 2;

	/**
	 * -------------------------------------------------------------------------
	 * 表示日志的 NOTICE 级别，这个级别用于记录一些不影响系统运行，但是比较感兴
	 * 趣的信息。
	 * 例如：过滤器的拦截情况。
	 * -------------------------------------------------------------------------
	 */
	const LEVEL_NOTICE = 4;

	/**
	 * -------------------------------------------------------------------------
	 * 表示日志的 WARNING 级别，一般用于记录已经对程序的运行结果产生影响但是并没
	 * 有超出预期的问题。
	 * 例如：一些数据的编码失败等。
	 * -------------------------------------------------------------------------
	 */
	const LEVEL_WARNING = 8;

	/**
	 * -------------------------------------------------------------------------
	 * 表示系统发生了错误的日志级别 ERROR ，一般用于记录将严重运行系统运行结果的
	 * 错误或异常问题，这些问题通常都在预料之中，但是需要立即解决。
	 * 例如：数据库连接失败。
	 * -------------------------------------------------------------------------
	 */
	const LEVEL_ERROR = 16;

	/**
	 * -------------------------------------------------------------------------
	 * 这是系统出现比较意外的致命错误的日志级别 CRITICAL ，通常表示已经超出系统
	 * 处理范围的错误和问题。
	 * 例如：关键的类找不到并无法加载，关键参数丢失等。
	 * -------------------------------------------------------------------------
	 */
	const LEVEL_CRITICAL = 32;

	/**
	 * -------------------------------------------------------------------------
	 * 这是非常严重的日志级别 ALERT ，这表示系统遇到了非常严重的问题，如果不立即
	 * 处理，有很大的可能造成系统无法继续运行。
	 * 例如：捕获到未知的异常，配置文件的配置项严重不合法，依赖的非关键组件找不
	 * 到或者无法加载，一些关键的框架内部方法参数严重不合法等。
	 * -------------------------------------------------------------------------
	 */
	const LEVEL_ALERT = 64;

	/**
	 * -------------------------------------------------------------------------
	 * 最高的日志级别 EMERGENCY ，这个级别的日志表示系统发生了不可逆转的问题或者
	 * 发生了完全意料之外的事情，有极大的可能性造成了数据损失。一般建议系统立即
	 * 降级运行或者暂停运行并同时联系维护人员。
	 * 例如：系统文件被意外删除等。
	 * -------------------------------------------------------------------------
	 */
	const LEVEL_EMERGENCY = 128;

	/**
	 * -------------------------------------------------------------------------
	 * 所有日志级别对应的文字描述。
	 * -------------------------------------------------------------------------
	 * 注意：不在定义范围类的日志级别将被系统解析成 UNKNOWN 级别。
	 * 
	 * @var array
	 */
	private static $levelNameMap = [

		self::LEVEL_DEBUG 		=> 'DEBUG',
		self::LEVEL_INFO 		=> 'INFO',
		self::LEVEL_NOTICE 		=> 'NOTICE',
		self::LEVEL_WARNING 	=> 'WARNING',
		self::LEVEL_ERROR 		=> 'ERROR',
		self::LEVEL_CRITICAL 	=> 'CRITICAL',
		self::LEVEL_ALERT 		=> 'ALERT',
		self::LEVEL_EMERGENCY 	=> 'EMERGENCY'

	];

	/**
	 * -------------------------------------------------------------------------
	 * 日志记录器捕获到的所有符合要求的日志。
	 * -------------------------------------------------------------------------
	 * 这里记录的每一个元素都包括：级别，级别的名称，日期，日志内容。
	 * 
	 * @var array
	 */
	private static $logs = [];

	/**
	 * -------------------------------------------------------------------------
	 * 日志记录器。
	 * -------------------------------------------------------------------------
	 * 日志记录器必须实现 save 方法。
	 * 
	 * @var object
	 */
	private static $handler = null;

	/**
	 * -------------------------------------------------------------------------
	 * 需要被捕获记录的最低日志级别。
	 * -------------------------------------------------------------------------
	 * 默认的级别为 ERROR 。
	 * 
	 * @var integer
	 */
	private static $level = Logger::LEVEL_ERROR;

	/**
	 * -------------------------------------------------------------------------
	 * 设置触发日志记录器程序的最低日志级别。
	 * -------------------------------------------------------------------------
	 * 
	 * @param  integer 	$level 	日志级别
	 * @return boolean
	 */
	public static function setLevel(int $level)
	{
		if (isset(self::$levelNameMap[$level])) {
			self::$level = $level;
			return true;
		} else {
			return false;
		}
	}

	/**
	 * -------------------------------------------------------------------------
	 * 获取记录的所有日志。
	 * -------------------------------------------------------------------------
	 * 
	 * @return array
	 */
	public static function getLogs()
	{
		return self::$logs;
	}

	/**
	 * -------------------------------------------------------------------------
	 * 设置系统使用的日志记录器
	 * -------------------------------------------------------------------------
	 * 所有可用的日志记录器都在记录器目录中，这些都是由系统自带的，你也可以根据
	 * 实际情况自定义日志记录器，但是必须遵循相关的约定，以便在未来的版本中兼容。
	 *
	 * 在日志记录器程序设定成功之后，系统将自动传递当前捕获到的所有日志到记录器，
	 * 以便完成所有的日志记录，这主要是为了保证在日志记录器程序设定之前产生的日
	 * 志能够被及时的保存，但是这就有一个陷阱，如果切换了日志记录器之后再手动换
	 * 回来，会导致日志被重复记录，所以在处理一个独立请求的过程中，在处理完成之
	 * 前最好不要切换日志记录器程序。
	 * 
	 * @param  string 	$handler 	日志记录器的名称
	 * @return boolean
	 */
	public static function useHandler(string $handler)
	{
		$handler 	= ucfirst(strtolower($handler));
		$className 	= '\\Edoger\\Core\\Log\\Handlers\\' . $handler . 'Handler';
		
		if (class_exists($className, true)) {
			$handler = new $className();
			if (!empty(self::$logs)) {
				foreach (self::$logs as $log) {
					$handler -> save($log[0], $log[1], $log[2], $log[3]);
				}
			}
			self::$handler = $handler;
			return true;
		} else {
			return false;
		}
	}

	/**
	 * -------------------------------------------------------------------------
	 * 记录一条指定级别的日志。
	 * -------------------------------------------------------------------------
	 * 如果指定的级别不能识别，将转换成未知日志，值得注意的是，未知日志将会高于
	 * 所有已定义的日志级别。
	 *
	 * 注意：如果在记录日志之前没有设定任何日志记录器程序，那么日志在脚本退出都
	 * 将全部丢失。
	 * 
	 * @param  integer 	$level 		日志级别
	 * @param  string 	$message 	日志内容
	 * @return void
	 */
	public static function log(int $level, string $message)
	{
		if (isset(self::$levelNameMap[$level])) {
			$name 	= self::$levelNameMap[$level];
		} else {
			$name 	= 'UNKNOWN';
			$level 	= 256;
		}
		if ($level >= Logger::LEVEL_ERROR) {
			$date = date('Y-m-d H:i:s');
			self::$logs[] = [$level, $name, $date, $message];
			if (self::$handler) {
				self::$handler -> save($level, $name, $date, $message);
			}
		}
	}

	/**
	 * -------------------------------------------------------------------------
	 * 记录一条 DEBUG 级别的日志
	 * -------------------------------------------------------------------------
	 * 
	 * @return void
	 */
	public static function debug(string $message)
	{
		self::log(self::LEVEL_DEBUG, $message);
	}


	/**
	 * -------------------------------------------------------------------------
	 * 记录一条 INFO 级别的日志
	 * -------------------------------------------------------------------------
	 * 
	 * @return void
	 */
	public static function info(string $message)
	{
		self::log(self::LEVEL_INFO, $message);
	}


	/**
	 * -------------------------------------------------------------------------
	 * 记录一条 NOTICE 级别的日志
	 * -------------------------------------------------------------------------
	 * 
	 * @return void
	 */
	public static function notice(string $message)
	{
		self::log(self::LEVEL_NOTICE, $message);
	}


	/**
	 * -------------------------------------------------------------------------
	 * 记录一条 WARNING 级别的日志
	 * -------------------------------------------------------------------------
	 * 
	 * @return void
	 */
	public static function warning(string $message)
	{
		self::log(self::LEVEL_WARNING, $message);
	}


	/**
	 * -------------------------------------------------------------------------
	 * 记录一条 ERROR 级别的日志
	 * -------------------------------------------------------------------------
	 * 
	 * @return void
	 */
	public static function error(string $message)
	{
		self::log(self::LEVEL_ERROR, $message);
	}


	/**
	 * -------------------------------------------------------------------------
	 * 记录一条 CRITICAL 级别的日志
	 * -------------------------------------------------------------------------
	 * 
	 * @return void
	 */
	public static function critical(string $message)
	{
		self::log(self::LEVEL_CRITICAL, $message);
	}


	/**
	 * -------------------------------------------------------------------------
	 * 记录一条 ALERT 级别的日志
	 * -------------------------------------------------------------------------
	 * 
	 * @return void
	 */
	public static function alert(string $message)
	{
		self::log(self::LEVEL_ALERT, $message);
	}


	/**
	 * -------------------------------------------------------------------------
	 * 记录一条 EMERGENCY 级别的日志
	 * -------------------------------------------------------------------------
	 * 
	 * @return void
	 */
	public static function emergency(string $message)
	{
		self::log(self::LEVEL_EMERGENCY, $message);
	}
}