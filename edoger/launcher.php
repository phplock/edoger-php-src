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

/**
 * --------------------------------------------------------------------------------
 * The Edoger PHP Framework Root Directory.
 * --------------------------------------------------------------------------------
 * 
 * This is the initial search directory for automatic loader.
 */
const EDOGER_ROOT = __DIR__;


/**
 * --------------------------------------------------------------------------------
 * Registered Automatic Loader.
 * --------------------------------------------------------------------------------
 * 
 * Automatic loader is responsible for loading the various components provided by 
 * the framework.
 */
spl_autoload_register(

	function(string $class){

		//	Create the class file path.
		$path = preg_replace(
			['/\\\\/', '/Edoger/'],
			['/', EDOGER_ROOT],
			$class
			) . '.php';

		if (file_exists($path)) {
			require $path;
		}
		
	}, true, true);


/**
 * --------------------------------------------------------------------------------
 * Gets The Framework Kernel Instance Object.
 * --------------------------------------------------------------------------------
 * 
 * This should be the preferred method of scheduling framework in the application.
 *
 * @return Edoger\Core\Kernel
 */
function edoger()
{
	return Edoger\Core\Kernel::core();
}
