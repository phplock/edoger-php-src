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
namespace Edoger\Core\Http;

use Edoger\Core\Kernel;

/**
 * ================================================================================
 * Some Description.
 *
 * 
 * ================================================================================
 */
final class Request
{
	/**
	 * ----------------------------------------------------------------------------
	 * What is it ?
	 * ----------------------------------------------------------------------------
	 *
	 * @var type
	 */
	private static $kernel;

	/**
	 * ----------------------------------------------------------------------------
	 * 全局变量 $_SERVER 的访问管理器实例
	 * ----------------------------------------------------------------------------
	 *
	 * @var type
	 */
	private static $server;

	private static $input;
	
	/**
	 * ----------------------------------------------------------------------------
	 * [__construct description]
	 * ----------------------------------------------------------------------------
	 * 
	 * @param Kernel &$kernel [description]
	 */
	public function __construct(Kernel &$kernel)
	{
		self::$server = new Server();
		self::$input = new Input();
		self::$kernel = &$kernel;
	}

	/**
	 * ----------------------------------------------------------------------------
	 * [server description]
	 * ----------------------------------------------------------------------------
	 * 
	 * @return [type] [description]
	 */
	public function server()
	{
		return self::$server;
	}

	/**
	 * ----------------------------------------------------------------------------
	 * [method description]
	 * ----------------------------------------------------------------------------
	 * 
	 * @return [type] [description]
	 */
	public function method()
	{
		return strtolower(self::$server -> query('REQUEST_METHOD', ''));
	}

	
}