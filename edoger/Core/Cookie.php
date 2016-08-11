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

	/**
	 * ----------------------------------------------------------------------------
	 * [$options description]
	 * ----------------------------------------------------------------------------
	 * 
	 * @var array
	 */
	private static $options = [];

	/**
	 * ----------------------------------------------------------------------------
	 * [__construct description]
	 * ----------------------------------------------------------------------------
	 * 
	 * @param Kernel &$kernel [description]
	 */
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
			self::$source = $_COOKIE;
			$prefixLength = strlen($config['security_prefix']);
			foreach ($_COOKIE as $key => $value) {
				if (substr($key, 0, $prefixLength) === $config['security_prefix']) {
					
				} else {
					self::$cookies[$key] = self::decrypt($value);
					if (is_null(self::$cookies[$key])) {
						self::forget($key);
						unset(self::$cookies[$key]);
					}
				}
			}
		}
	}

	private static function decrypt($cookie)
	{
		$json = base64_decode($cookie);
		if ($json !== false) {
			$data = json_decode($json, true);
			if (JSON_ERROR_NONE === json_last_error()) {
				return $data;
			}
		}

		return null;
	}

	/**
	 * ----------------------------------------------------------------------------
	 * 获取一个指定名称的cookie
	 * ----------------------------------------------------------------------------
	 * 
	 * @param  string $key [description]
	 * @param  [type] $def [description]
	 * @return [type]      [description]
	 */
	public static function fetch(string $key, $def = null)
	{
		return self::$cookies[$key] ?? $def;
	}

	/**
	 * ----------------------------------------------------------------------------
	 * 检查指定名称的cookie是否存在
	 * ----------------------------------------------------------------------------
	 * 
	 * @param  string $key [description]
	 * @return [type]      [description]
	 */
	public static function exist(string $key)
	{
		return isset(self::$cookies[$key]);
	}

	/**
	 * ----------------------------------------------------------------------------
	 * 检查指定名称的cookie是否与给定的值完全相等
	 * ----------------------------------------------------------------------------
	 * 
	 * @param  string $key   [description]
	 * @param  [type] $value [description]
	 * @return [type]        [description]
	 */
	public static function equal(string $key, $value)
	{
		return $this -> fetch($key) === $value;
	}

	/**
	 * ----------------------------------------------------------------------------
	 * 发送一个自定义cookie
	 * ----------------------------------------------------------------------------
	 * 
	 * @param  string $key    [description]
	 * @param  string $value  [description]
	 * @param  array  $option [description]
	 * @return [type]         [description]
	 */
	public static function send(string $key, $value, array $option = [])
	{

	}

	/**
	 * ----------------------------------------------------------------------------
	 * 创建一个临时的会话cookie
	 * ----------------------------------------------------------------------------
	 * 
	 * @param  string $key    [description]
	 * @param  string $value  [description]
	 * @param  array  $option [description]
	 * @return [type]         [description]
	 */
	public static function interim(string $key, $value, array $option = [])
	{

	}

	/**
	 * ----------------------------------------------------------------------------
	 * 创建一个相对永久的cookie，有效期5年
	 * ----------------------------------------------------------------------------
	 * 
	 * @param  string $key    [description]
	 * @param  string $value  [description]
	 * @param  array  $option [description]
	 * @return [type]         [description]
	 */
	public static function forever(string $key, $value, array $option = [])
	{
		
	}

	/**
	 * ----------------------------------------------------------------------------
	 * 创建一个安全的带签名的加密cookie
	 * ----------------------------------------------------------------------------
	 * 
	 * @param  string $key    [description]
	 * @param  string $value  [description]
	 * @param  array  $option [description]
	 * @return [type]         [description]
	 */
	public static function safe(string $key, $value, array $option = [])
	{

	}

	/**
	 * ----------------------------------------------------------------------------
	 * 让一个指定的cookie过期
	 * ----------------------------------------------------------------------------
	 * 
	 * @param  string $key [description]
	 * @return [type]      [description]
	 */
	public static function forget(string $key)
	{

	}

	/**
	 * ----------------------------------------------------------------------------
	 * 获取当前获取的全部原始cookie（加密的cookie未被解密）
	 * ----------------------------------------------------------------------------
	 * 
	 * @return [type] [description]
	 */
	public static function source()
	{

	}
}