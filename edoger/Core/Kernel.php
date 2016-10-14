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

use Edoegr\Exception\EdogerException;

final class Kernel
{
	private static $_instance = null;
	private static $_application = null;
	private static $_config = null;
	
	private function __construct()
	{
		$conf = require ROOT_PATH.'/config/edoger.config.php';
		self::$_config = new Config($conf);
	}

	public static function singleton()
	{
		if (!self::$_instance) {
			self::$_instance = new self();
		}
		
		return self::$_instance;
	}

	public function app()
	{
		if (!self::$_application) {
			self::$_application = new Application($this);
		}

		return self::$_application;
	}

	public function config()
	{
		return self::$_config;
	}

	public function termination()
	{

		echo 'Hello World';
	}
}
