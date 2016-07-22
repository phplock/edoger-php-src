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
	 * Shared component.
	 * ----------------------------------------------------------------------------
	 *
	 * All system components are here to create a self reference.
	 * 
	 * @var array
	 */
	private static $shared = [];

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
	 * Creates And Returns The Edoger Application Instance.
	 * ----------------------------------------------------------------------------
	 *
	 * @return Edoger\Core\Application
	 */
	public function app()
	{
		static $application = null;

		if (!$application) {
			$application = new Application($this, self::$shared);
		}

		return $application;
	}
}
