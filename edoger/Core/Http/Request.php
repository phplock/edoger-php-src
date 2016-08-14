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

	/**
	 * [$input description]
	 * @var [type]
	 */
	private static $input;

	private static $requestUri = '/';
	private static $requesrRoutes = [];
	private static $reqiestRoutesSize = 0;
	
	/**
	 * ----------------------------------------------------------------------------
	 * [__construct description]
	 * ----------------------------------------------------------------------------
	 * 
	 * @param Kernel &$kernel [description]
	 */
	public function __construct(Kernel &$kernel)
	{
		self::$server 	= new Server();
		self::$input 	= new Input();
		self::$kernel 	= &$kernel;

		self::parseRequestUri();
	}

	private static function parseRequestUri()
	{
		$sourceUri = self::$server -> search(['PATH_INFO', 'REQUEST_URI'], '/');
		if (!$sourceUri || $sourceUri === '/') {
			return;
		}

		$path = parse_url(urldecode($sourceUri), PHP_URL_PATH);
		if ($path === false) {
			return;
		}

		self::$requesrRoutes 		= preg_split('/\//', $path, PREG_SPLIT_NO_EMPTY);
		self::$reqiestRoutesSize 	= count(self::$requesrRoutes);
		self::$requestUri 			.= implode('/', self::$requesrRoutes);
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

	/**
	 * ----------------------------------------------------------------------------
	 * [ip description]
	 * ----------------------------------------------------------------------------
	 * 
	 * @return [type] [description]
	 */
	public function ip()
	{
		return self::$server -> search([
			'HTTP_X_IP',
			'REMOTE_ADDR'
			], '0.0.0.0');
	}

	/**
	 * ----------------------------------------------------------------------------
	 * [uri description]
	 * ----------------------------------------------------------------------------
	 * 
	 * @return [type] [description]
	 */
	public function uri()
	{
		return self::$requestUri;
	}

	/**
	 * ----------------------------------------------------------------------------
	 * [route description]
	 * ----------------------------------------------------------------------------
	 * 
	 * @return [type] [description]
	 */
	public function route(int $index)
	{
		return self::$requesrRoutes[$index] ?? null;
	}

	/**
	 * ----------------------------------------------------------------------------
	 * [routes description]
	 * ----------------------------------------------------------------------------
	 * 
	 * @return [type] [description]
	 */
	public function routes()
	{
		self::$requesrRoutes
	}

	/**
	 * ----------------------------------------------------------------------------
	 * [hostname description]
	 * ----------------------------------------------------------------------------
	 * 
	 * @return [type] [description]
	 */
	public function hostname()
	{
		return self::$server -> query('HTTP_HOST', '');
	}

	/**
	 * ----------------------------------------------------------------------------
	 * [port description]
	 * ----------------------------------------------------------------------------
	 * 
	 * @return [type] [description]
	 */
	public function port()
	{
		return (int)self::$server -> query('SERVER_PORT', 80);
	}

	/**
	 * ----------------------------------------------------------------------------
	 * [userAgent description]
	 * ----------------------------------------------------------------------------
	 * 
	 * @return [type] [description]
	 */
	public function userAgent()
	{
		return self::$server -> query('HTTP_USER_AGENT', '');
	}
}