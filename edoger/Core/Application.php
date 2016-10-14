<?php
/**
 *+------------------------------------------------------------------------------------------------+
 *| Edoger PHP Framework                                                                           |
 *+------------------------------------------------------------------------------------------------+
 *| A simple route analysis and matching module.                                                   |
 *+------------------------------------------------------------------------------------------------+
 *| @package   edoger-php-src                                                                      |
 *| @license   MIT                                                                                 |
 *| @link      https://www.edoger.com/                                                             |
 *| @copyright Copyright (c) 2014 - 2016, QingShan Luo                                             |
 *| @version   1.0.0 Alpha                                                                         |
 *+------------------------------------------------------------------------------------------------+
 *| @author    Qingshan Luo <shanshan.lqs@gmail.com>                                               |
 *+------------------------------------------------------------------------------------------------+
 */
namespace Edoger\Core;

final class Application
{
	private static $_config = null;

	public function __construct(Kernel $kernel)
	{
		$conf = require ROOT_PATH.'/config/application.config.php';
		self::$_config = new Config($conf);
	}

	public function config()
	{
		return self::$_config;
	}

	public function bootstrap()
	{
		$file = APP_PATH.'/bootstrap.php';
		if (file_exists($file)) {
			require $file;
		}
		return $this;
	}

	public function run()
	{

	}
}