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
namespace Edoger\Core\Route;

/**
* 
*/
final class RouteCasing
{
	private static $middleware 	= [];
	private static $filter 		= [];
	private static $action 		= null;
	private static $route 		= '';
	private static $isMatch 	= [];

	private static $domain;
	private static $port;
	private static $scheme;
	private static $xhr;
	
	public function __construct(string $domain, int $port, string $scheme, bool $xhr)
	{
		self::$domain 	= $domain;
		self::$port 	= $port;
		self::$scheme 	= $scheme;
		self::$xhr 		= $xhr;
	}

	public function filter(array $filter)
	{
		if (self::$isMatch && !empty($filter)) {
			self::$filter = $filter;
		}

		return $this;
	}

	public function scheme(string $scheme)
	{
		if (self::$isMatch && self::$scheme !== strtolower($scheme)) {
			self::$isMatch = false;
		}

		return $this;
	}

	public function domain(string $domain)
	{
		if (self::$isMatch && self::$domain !== strtolower($domain)) {
			self::$isMatch = false;
		}

		return $this;
	}

	public function domains(array $domains)
	{
		if (self::$isMatch && !empty($domains)) {
			foreach ($domains as $d) {
				if (self::$domain !== strtolower($d)) {
					self::$isMatch = false;
					break;
				}
			}
		}

		return $this;
	}

	public function xhrOnly()
	{
		if (self::$isMatch && !self::$xhr) {
			self::$isMatch = false;
		}

		return $this;
	}

	public function middleware(array $middleware)
	{
		if (self::$isMatch) {
			self::$middleware = $middleware;
		}

		return $this;
	}
}