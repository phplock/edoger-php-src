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
namespace Edoger\Core\Logger;

/**
 * ================================================================================
 * Some Description.
 *
 * 
 * ================================================================================
 */
final class Logger
{
	/**
	 * ----------------------------------------------------------------------------
	 * What is it ?
	 * ----------------------------------------------------------------------------
	 */
	const DEBUG = 128;

	/**
	 * ----------------------------------------------------------------------------
	 * What is it ?
	 * ----------------------------------------------------------------------------
	 */
	const INFO = 64;

	/**
	 * ----------------------------------------------------------------------------
	 * What is it ?
	 * ----------------------------------------------------------------------------
	 */
	const NOTICE = 32;

	/**
	 * ----------------------------------------------------------------------------
	 * What is it ?
	 * ----------------------------------------------------------------------------
	 */
	const WARNING = 16;

	/**
	 * ----------------------------------------------------------------------------
	 * What is it ?
	 * ----------------------------------------------------------------------------
	 */
	const ERROR = 8;

	/**
	 * ----------------------------------------------------------------------------
	 * What is it ?
	 * ----------------------------------------------------------------------------
	 */
	const CRITICAL = 4;

	/**
	 * ----------------------------------------------------------------------------
	 * What is it ?
	 * ----------------------------------------------------------------------------
	 */
	const ALERT = 2;

	/**
	 * ----------------------------------------------------------------------------
	 * What is it ?
	 * ----------------------------------------------------------------------------
	 */
	const EMERGENCY = 1;

	/**
	 * ----------------------------------------------------------------------------
	 * What is it ?
	 * ----------------------------------------------------------------------------
	 */
	const ALL = 255;


	private static $instances = [];


	/**
	 * ----------------------------------------------------------------------------
	 * What is it ?
	 * ----------------------------------------------------------------------------
	 *
	 * @var type
	 */
	public function __construct(string $passageway)
	{
		self::$instances[strtolower($passageway)] = &$this;
	}
}