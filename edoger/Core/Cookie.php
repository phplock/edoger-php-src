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
 * 
 */
final class Cookie
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
	 * What is it ?
	 * ----------------------------------------------------------------------------
	 *
	 * @var type
	 */
	private static $source = [];

	/**
	 * ----------------------------------------------------------------------------
	 * What is it ?
	 * ----------------------------------------------------------------------------
	 *
	 * @var type
	 */
	private static $cookies = [];

	/**
	 * ----------------------------------------------------------------------------
	 * What is it ?
	 * ----------------------------------------------------------------------------
	 *
	 * @var type
	 */
	private static $key;

	/**
	 * ----------------------------------------------------------------------------
	 * What is it ?
	 * ----------------------------------------------------------------------------
	 *
	 * @var type
	 */
	private static $prefix;

	private static $options = [];


	public function __construct(Kernel &$kernel)
	{
		self::$kernel = &$kernel;

		$config = $kernel -> config() -> get('cookie');

		self::$prefix 	= $config['security_prefix'];
		self::$key 		= $config['key'];

		self::$options['expire'] 	= $config['expire'];
		self::$options['path'] 		= $config['path'];
		self::$options['domain'] 	= $config['domain'];
		self::$options['secure'] 	= $config['secure'];
		self::$options['httponly'] 	= $config['httponly'];

		if (!empty($_COOKIE)) {
			foreach ($_COOKIE as $key => $value) {
				
			}
		}
	}

	public function fetch(string $key, $def = null)
	{
		return self::$cookies[$key] ?? $def;
	}

	public function exists(string $key)
	{
		return isset(self::$cookies[$key]);
	}

	public function equal(string $key, $value)
	{
		return $this -> fetch($key) === $value;
	}

	public function send(string $key, string $value, array $option = [])
	{

	}

	public function interim(string $key, string $value, array $option = [])
	{

	}

	public function forever(string $key, string $value, array $option = [])
	{
		
	}

	public function safe(string $key, string $value, array $option = [])
	{

	}

	public function forget(string $key)
	{

	}

	public function source()
	{

	}
}