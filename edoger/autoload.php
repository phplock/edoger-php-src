<?php
/**
 *+------------------------------------------------------------------------------------------------+
 *| Edoger PHP Framework                                                                           |
 *+------------------------------------------------------------------------------------------------+
 *| A simple and efficient PHP framework.                                                          |
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

// Automatic loader.
final class EdogerAutoload
{
	public static function load(string $name)
	{
		if (preg_match('/^Edoger\\\\/', $name)) {

			// Automatic loading the edoger PHP framework files.
			$path = EDOGER_PATH.ltrim(str_replace('\\', '/', $name), 'Edoger').'.php';
			if (file_exists($path)) {
				require $path;
			}
		} elseif (preg_match('/^App\\\\/', $name)) {

			// Automatically loading application files.
			$path = APP_PATH.ltrim(str_replace('\\', '/', $name), 'App').'.php';
			if (file_exists($path)) {
				require $path;
			}
		}
	}
}

// Registered automatic loader.
spl_autoload_register([EdogerAutoload::class, 'load']);