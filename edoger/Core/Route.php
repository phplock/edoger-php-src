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
 * Some Description.
 *
 * 
 * ================================================================================
 */
final class Route
{
	/**
	 * ----------------------------------------------------------------------------
	 * What is it ?
	 * ----------------------------------------------------------------------------
	 *
	 * @var string
	 */
	private static $uri 		= '/';

	/**
	 * ----------------------------------------------------------------------------
	 * What is it ?
	 * ----------------------------------------------------------------------------
	 *
	 * @var array
	 */
	private static $sections 	= [];

	/**
	 * ----------------------------------------------------------------------------
	 * What is it ?
	 * ----------------------------------------------------------------------------
	 *
	 * @var integer
	 */
	private static $length 		= 0;

	/**
	 * ----------------------------------------------------------------------------
	 * What is it ?
	 * ----------------------------------------------------------------------------
	 *
	 * @var string
	 */
	private static $method 		= '';

	/**
	 * ----------------------------------------------------------------------------
	 * What is it ?
	 * ----------------------------------------------------------------------------
	 *
	 * @var integer
	 */
	private static $port 		= 0;

	/**
	 * ----------------------------------------------------------------------------
	 * What is it ?
	 * ----------------------------------------------------------------------------
	 *
	 * @return type
	 */
	public function __construct(string $uri, string $method, int $port)
	{
		if ($uri !== '/') {
			self::$uri 		= $uri;
			self::$sections = preg_split('/\//', $uri, 0, PREG_SPLIT_NO_EMPTY);
			self::$length 	= count(self::$sections);
		}

		self::$method 	= $method;
		self::$port 	= $port;
	}

	/**
	 * ----------------------------------------------------------------------------
	 * What is it ?
	 * ----------------------------------------------------------------------------
	 *
	 * @return type
	 */
	public static function parseUri(string $uri)
	{
		if ($uri === '/') {
			if (self::$length) {
				return false;
			} else {
				return [];
			}
		} else {

			$nodes 		= preg_split('/\//', $uri, 0, PREG_SPLIT_NO_EMPTY);

			$patterns 	= [];
			$keys 		= [];

			foreach ($nodes as $node) {
				if (preg_match('/^\:(\w+)(\??)$/', $node, $m)) {
					$patterns[] = $m[2] ? '([\w\-]+)' : '([\w\-]*)';
					$keys[] 	= $m[1];
				} else {
					$patterns[] = preg_quote($v);
				}
			}

			$pattern = '/^' . join('\/', $patterns) . '$/';
			$subject = join('/', array_pad($this -> sections, count($nodes), ''));

			if (preg_match($pattern, $subject, $matches)) {
				return array_combine($keys, array_slice($matches, 1));
			} else {
				return false;
			}
		}
	}

	
}
