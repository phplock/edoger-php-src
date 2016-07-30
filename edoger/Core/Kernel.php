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
namespace Edoger\Core;

use Edoger\Exceptions\RuntimeException;

/**
 * ================================================================================
 * Kernel Of Edoger Framework.
 *
 * All of the frame components are registered in this core.
 * ================================================================================
 */
final class Kernel
{
	/**
	 * ----------------------------------------------------------------------------
	 * The Root Directory Of The WEB Site.
	 * ----------------------------------------------------------------------------
	 *
	 * @var string
	 */
	private static $root;

	/**
	 * ----------------------------------------------------------------------------
	 * Application Configuration Options Manager.
	 * ----------------------------------------------------------------------------
	 *
	 * @var Edoger\Core\Config
	 */
	private static $config;

	/**
	 * ----------------------------------------------------------------------------
	 * An Instance The Application.
	 * ----------------------------------------------------------------------------
	 *
	 * @var Edoger\Core\Application
	 */
	private static $application;

	/**
	 * ----------------------------------------------------------------------------
	 * Get The Kernel Version.
	 * ----------------------------------------------------------------------------
	 * 
	 * @return string
	 */
	public static function version()
	{
		return '1.0.0';
	}

	/**
	 * ----------------------------------------------------------------------------
	 * 
	 * ----------------------------------------------------------------------------
	 * 
	 * @return void
	 */
	private function __construct()
	{
		self::$root = dirname(dirname(__DIR__));

		$file = self::$root . '/config/edoger.php';
		if (!file_exists($file)) {
			throw new RuntimeException(
				"The edoger configuration file {$file} does not exist", 5001
				);
		}
		$configuration = require $file;

		//	Create system configuration manager.
		self::$config = new Config($configuration);
	}

	/**
	 * ----------------------------------------------------------------------------
	 * Creates And Returns The Edoger Kernel Instance.
	 * ----------------------------------------------------------------------------
	 *
	 * This instance will only be created once.
	 * 
	 * @return Edoger\Core\Kernel
	 */
	public static function core()
	{
		static $kernel = null;
		if (is_null($kernel)) {
			$kernel = new self();
		}
		return $kernel;
	}

	/**
	 * ----------------------------------------------------------------------------
	 * 
	 * ----------------------------------------------------------------------------
	 * 
	 * @return void
	 */
	public function root(string $uri = '')
	{
		if ($uri) {
			return self::$root . '/' . ltrim($uri, '/');
		} else {
			return self::$root;
		}
	}

	/**
	 * ----------------------------------------------------------------------------
	 * 
	 * ----------------------------------------------------------------------------
	 * 
	 * @return void
	 */
	public function create(string $fileName)
	{
		$file = self::$root . '/config/' . $fileName . '.php';

		if (!file_exists($file)) {
			throw new RuntimeException(
				"The application configuration file {$file} does not exist", 5001
				);
		}

		$configuration = require $file;

		self::$application = new Application($this, new Config($configuration));
	}

	/**
	 * ----------------------------------------------------------------------------
	 * Get The Edoger Application Instance.
	 * ----------------------------------------------------------------------------
	 *
	 * @return Edoger\Core\Application
	 */
	public function app()
	{
		return self::$application;
	}

	/**
	 * ----------------------------------------------------------------------------
	 * Get The Edoger Application Configuration Manager Instance.
	 * ----------------------------------------------------------------------------
	 *
	 * @return Edoger\Core\Config
	 */
	public function config()
	{
		return self::$config;
	}
}
