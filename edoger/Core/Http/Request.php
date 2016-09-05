<?php
/*
 +-----------------------------------------------------------------------------+
 | Edoger PHP Framework (EdogerPHP)                                            |
 +-----------------------------------------------------------------------------+
 | Copyright (c) 2014 - 2016 QingShan Luo                                      |
 +-----------------------------------------------------------------------------+
 | The MIT License (MIT)                                                       |
 |                                                                             |
 | Permission is hereby granted, free of charge, to any person obtaining a     |
 | copy of this software and associated documentation files (the “Software”),  |
 | to deal in the Software without restriction, including without limitation   |
 | the rights to use, copy, modify, merge, publish, distribute, sublicense,    |
 | and/or sell copies of the Software, and to permit persons to whom the       |
 | Software is furnished to do so, subject to the following conditions:        |
 |                                                                             |
 | The above copyright notice and this permission notice shall be included in  |
 | all copies or substantial portions of the Software.                         |
 |                                                                             |
 | THE SOFTWARE IS PROVIDED “AS IS”, WITHOUT WARRANTY OF ANY KIND,             |
 | EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF          |
 | MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.      |
 | IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, |
 | DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR       |
 | OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE   |
 | USE OR OTHER DEALINGS IN THE SOFTWARE.                                      |
 +-----------------------------------------------------------------------------+
 |  License: MIT                                                               |
 +-----------------------------------------------------------------------------+
 |  Authors: QingShan Luo <shanshan.lqs@gmail.com>                             |
 +-----------------------------------------------------------------------------+
 */
namespace Edoger\Core\Http;

use Edoger\Exceptions\RuntimeException;

/**
 * =============================================================================
 * Some Description.
 *
 * 
 * =============================================================================
 */
final class Request
{
	/**
	 * -------------------------------------------------------------------------
	 * [$caches description]
	 * -------------------------------------------------------------------------
	 * 
	 * @var array
	 */
	private static $caches = [];
	
	/**
	 * -------------------------------------------------------------------------
	 * [__construct description]
	 * -------------------------------------------------------------------------
	 * 
	 * @param Kernel &$kernel [description]
	 */
	private function __construct()
	{
		
	}

	/**
	 * -------------------------------------------------------------------------
	 * [__clone description]
	 * -------------------------------------------------------------------------
	 * 
	 * @return [type] [description]
	 */
	public function __clone()
	{

	}

	/**
	 * -------------------------------------------------------------------------
	 * [singleton description]
	 * -------------------------------------------------------------------------
	 * 
	 * @return [type] [description]
	 */
	public static function singleton()
	{
		static $instance = null;

		if (is_null($instance)) {
			$instance = new self();
		}

		return $instance;
	}

	/**
	 * -------------------------------------------------------------------------
	 * [method description]
	 * -------------------------------------------------------------------------
	 * 
	 * @return [type] [description]
	 */
	public function method()
	{
		if (!isset(self::$caches['method'])) {
			self::$caches['method'] = strtolower(
				Server::query('REQUEST_METHOD')
				);
		}
		return self::$caches['method'];
	}

	/**
	 * -------------------------------------------------------------------------
	 * [ip description]
	 * -------------------------------------------------------------------------
	 * 
	 * @return [type] [description]
	 */
	public function clientIp()
	{
		if (!isset(self::$caches['clientIp'])) {
			if (Server::exists('HTTP_CLIENT_IP')) {
				self::$caches['clientIp'] = Server::query('HTTP_CLIENT_IP');
			} elseif (Server::exists('HTTP_X_FORWARDED_FOR')) {
				self::$caches['clientIp'] = trim(
					explode(',', Server::query('HTTP_X_FORWARDED_FOR'))[0]
					);
			} else {
				self::$caches['clientIp'] = Server::query('REMOTE_ADDR');
			}
		}
		return self::$caches['clientIp'];
	}

	/**
	 * -------------------------------------------------------------------------
	 * [serverIp description]
	 * -------------------------------------------------------------------------
	 * 
	 * @return [type] [description]
	 */
	public function serverIp()
	{
		if (!isset(self::$caches['serverIp'])) {
			self::$caches['serverIp'] = Server::query('SERVER_ADDR');
		}
		return self::$caches['serverIp'];
	}

	/**
	 * -------------------------------------------------------------------------
	 * [uri description]
	 * -------------------------------------------------------------------------
	 * 
	 * @return [type] [description]
	 */
	public function requestUri()
	{
		if (!isset(self::$caches['requestUri'])) {
			$uri = parse_url(
				urldecode(Server::query('REQUEST_URI', '/')),
				PHP_URL_PATH
				);
			if (is_string($uri)) {
				self::$caches['requestUri'] = $uri;
			} else {
				self::$caches['requestUri'] = '/';
			}
		}
		return self::$caches['requestUri'];
	}

	/**
	 * -------------------------------------------------------------------------
	 * [protocol description]
	 * -------------------------------------------------------------------------
	 * 
	 * @return [type] [description]
	 */
	public function protocol()
	{
		if (!isset(self::$caches['protocol'])) {
			self::$caches['protocol'] = strtolower(
				Server::query('REQUEST_SCHEME')
				);
		}
		return self::$caches['protocol'];
	}

	/**
	 * -------------------------------------------------------------------------
	 * [xhr description]
	 * -------------------------------------------------------------------------
	 * 
	 * @return [type] [description]
	 */
	public function xhr()
	{
		if (!isset(self::$caches['xhr'])) {
			self::$caches['xhr'] = strtoupper(
				Server::query('HTTP_X_REQUESTED_WITH')
				) === 'XMLHTTPREQUEST';
		}
		return self::$caches['xhr'];
	}

	/**
	 * -------------------------------------------------------------------------
	 * [secure description]
	 * -------------------------------------------------------------------------
	 * 
	 * @return [type] [description]
	 */
	public function secure()
	{
		if (!isset(self::$caches['secure'])) {
			self::$caches['secure'] = $this -> protocol() === 'https';
		}
		return self::$caches['secure'];
	}

	/**
	 * -------------------------------------------------------------------------
	 * [hostname description]
	 * -------------------------------------------------------------------------
	 * 
	 * @return [type] [description]
	 */
	public function hostname()
	{
		if (!isset(self::$caches['hostname'])) {
			self::$caches['hostname'] = Server::query('HTTP_HOST');
		}
		return self::$caches['hostname'];
	}

	/**
	 * -------------------------------------------------------------------------
	 * [port description]
	 * -------------------------------------------------------------------------
	 * 
	 * @return [type] [description]
	 */
	public function port()
	{
		if (!isset(self::$caches['port'])) {
			self::$caches['port'] = (int)Server::query('SERVER_PORT');
		}
		return self::$caches['port'];
	}

	/**
	 * -------------------------------------------------------------------------
	 * [userAgent description]
	 * -------------------------------------------------------------------------
	 * 
	 * @return [type] [description]
	 */
	public function userAgent()
	{
		if (!isset(self::$caches['userAgent'])) {
			self::$caches['userAgent'] = Server::query('HTTP_USER_AGENT');
		}
		return self::$caches['userAgent'];
	}

	/**
	 * -------------------------------------------------------------------------
	 * [baseUrl description]
	 * -------------------------------------------------------------------------
	 * 
	 * @param  string $protocol [description]
	 * @return [type]           [description]
	 */
	public function baseUrl()
	{
		if (!isset(self::$caches['baseUrl'])) {
			self::$caches['baseUrl']
				= $this -> protocol() . '://' . $this -> hostname();
		}
		return self::$caches['baseUrl'];
	}

	/**
	 * -------------------------------------------------------------------------
	 * [refererUrl description]
	 * -------------------------------------------------------------------------
	 * 
	 * @return [type] [description]
	 */
	public function refererUrl()
	{
		if (!isset(self::$caches['refererUrl'])) {
			self::$caches['refererUrl'] = Server::query('HTTP_REFERER');
		}
		return self::$caches['refererUrl'];
	}

	/**
	 * -------------------------------------------------------------------------
	 * [get description]
	 * -------------------------------------------------------------------------
	 * 
	 * @param  string $key [description]
	 * @param  [type] $def [description]
	 * @return [type]      [description]
	 */
	public function get(string $key, $def = null)
	{
		return $_GET[$key] ?? $def;
	}

	/**
	 * -------------------------------------------------------------------------
	 * [post description]
	 * -------------------------------------------------------------------------
	 * 
	 * @param  string $key [description]
	 * @param  [type] $def [description]
	 * @return [type]      [description]
	 */
	public function post(string $key, $def = null)
	{
		return $_POST[$key] ?? $def;
	}

	/**
	 * -------------------------------------------------------------------------
	 * [getpost description]
	 * -------------------------------------------------------------------------
	 * 
	 * @param  string $key [description]
	 * @param  [type] $def [description]
	 * @return [type]      [description]
	 */
	public function getpost(string $key, $def = null)
	{
		return $_GET[$key] ?? $_POST[$key] ?? $def;
	}

	/**
	 * -------------------------------------------------------------------------
	 * [postget description]
	 * -------------------------------------------------------------------------
	 * 
	 * @param  string $key [description]
	 * @param  [type] $def [description]
	 * @return [type]      [description]
	 */
	public function postget(string $key, $def = null)
	{
		return $_POST[$key] ?? $_GET[$key] ?? $def;
	}

	/**
	 * -------------------------------------------------------------------------
	 * [route description]
	 * -------------------------------------------------------------------------
	 * 
	 * @return [type] [description]
	 */
	public function route()
	{

	}
}