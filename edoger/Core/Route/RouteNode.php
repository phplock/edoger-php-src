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
 * ================================================================================
 * Some Description.
 *
 * 
 * ================================================================================
 */
class RouteNode
{
	/**
	 * ----------------------------------------------------------------------------
	 * What is it ?
	 * ----------------------------------------------------------------------------
	 *
	 * @var type
	 */
	public static $vivavium = [];

	/**
	 * ----------------------------------------------------------------------------
	 * What is it ?
	 * ----------------------------------------------------------------------------
	 *
	 * @var type
	 */
	private static $initialized = false;

	/**
	 * ----------------------------------------------------------------------------
	 * What is it ?
	 * ----------------------------------------------------------------------------
	 *
	 * @var type
	 */
	private static $requestUri;

	/**
	 * ----------------------------------------------------------------------------
	 * What is it ?
	 * ----------------------------------------------------------------------------
	 *
	 * @var type
	 */
	private static $requestPignut;

	/**
	 * ----------------------------------------------------------------------------
	 * What is it ?
	 * ----------------------------------------------------------------------------
	 *
	 * @var type
	 */
	private static $requestPignutSize;

	/**
	 * ----------------------------------------------------------------------------
	 * What is it ?
	 * ----------------------------------------------------------------------------
	 *
	 * @var type
	 */
	private static $requestMethod;

	/**
	 * ----------------------------------------------------------------------------
	 * What is it ?
	 * ----------------------------------------------------------------------------
	 *
	 * @var type
	 */
	private static $requestPort;

	/**
	 * ----------------------------------------------------------------------------
	 * What is it ?
	 * ----------------------------------------------------------------------------
	 *
	 * @var type
	 */
	private static $requestDomain;

	/**
	 * ----------------------------------------------------------------------------
	 * What is it ?
	 * ----------------------------------------------------------------------------
	 *
	 * @var type
	 */
	private $weight = 0;

	/**
	 * ----------------------------------------------------------------------------
	 * What is it ?
	 * ----------------------------------------------------------------------------
	 *
	 * @var type
	 */
	private $success = true;

	/**
	 * ----------------------------------------------------------------------------
	 * What is it ?
	 * ----------------------------------------------------------------------------
	 *
	 * @var type
	 */
	private $uri;

	/**
	 * ----------------------------------------------------------------------------
	 * What is it ?
	 * ----------------------------------------------------------------------------
	 *
	 * @var type
	 */
	private $pignut;

	/**
	 * ----------------------------------------------------------------------------
	 * What is it ?
	 * ----------------------------------------------------------------------------
	 *
	 * @var type
	 */
	private $action;

	/**
	 * ----------------------------------------------------------------------------
	 * What is it ?
	 * ----------------------------------------------------------------------------
	 *
	 * @var type
	 */
	private $params = [];
	
	/**
	 * ----------------------------------------------------------------------------
	 * [__construct description]
	 * ----------------------------------------------------------------------------
	 * 
	 * @param array  $method [description]
	 * @param array  $pignut [description]
	 * @param [type] $action [description]
	 */
	public function __construct(array $method, array $pignut, $action)
	{
		if (!self::$initialized) {
			self::$initialized = true;

		}
		
		if (!in_array(self::$requestMethod, $method)) {
			$this -> success = false;
			return;
		}

		$length = count($pignut);

		if ($length < self::$requestPignutSize) {
			$this -> success = false;
			return;
		}

		$uri = '/' . implode('/', $pignut);

		if ($uri === self::$requestUri) {
			$this -> weight = 99999999.0;
			$this -> uri 	= $uri;
			$this -> pignut = $pignut;

			$this -> action = self::parseAction($action);

			self::$vivavium[] = &$this;
			return;
		}

		if (!preg_match('/\:/', $uri)) {
			$this -> success = false;
			return;
		}

		$patterns 		= [];
		$keys 			= [];
		$arithmometer 	= [0, 0, 0];

		foreach ($pignut as $millet) {
			if (preg_match('/^\:(\w+)(\??)$/', $millet, $matches)) {
				if ($matches[2]) {
					$patterns[] = '([\w\-]+)';
					$arithmometer[0]++;
				} else {
					$patterns[] = '([\w\-]*)';
					$arithmometer[1]++;
				}
				$keys[] = $matches[1];
			} else {
				$patterns[] = preg_quote($millet);
				$arithmometer[2]++;
			}
		}

		$pattern = '/^' . implode('\/', $patterns) . '$/';
		$subject = implode('/', array_pad(self::$requestPignut, $length, ''));

		if (preg_match($pattern, $subject, $matches)) {
			$weight = 0.0;

			$weight += $arithmometer[0] * 10;
			$weight += $arithmometer[1] * 100;
			$weight += $arithmometer[2] * 1000;

			$sum = array_sum($arithmometer);

			$weight += $arithmometer[0] / $sum * 1000;
			$weight += $arithmometer[1] / $sum * 10000;
			$weight += $arithmometer[2] / $sum * 100000;

			$this -> weight = $weight;
			$this -> uri 	= $uri;
			$this -> pignut = $pignut;
			$this -> params = array_combine($keys, array_slice($matches, 1));

			$this -> action = self::parseAction($action);

			self::$vivavium[] = &$this;
		} else {
			$this -> success = false;
		}
	}

	/**
	 * ----------------------------------------------------------------------------
	 * [parseAction description]
	 * ----------------------------------------------------------------------------
	 * 
	 * @param  [type] $action [description]
	 * @return [type]         [description]
	 */
	private static function parseAction($action)
	{
		if (is_callable($action)) {
			return $action;
		} elseif (
			is_string($action) &&
			preg_match('/^(\w+)@(\w+)$/', $action, $matches)
			) {
			
			$controller = $matches[1];
			$method 	= $matches[2];
			
			//	Parse ...

		} else {

			//	Error
			$this -> success = false;
		}
	}

	/**
	 * ----------------------------------------------------------------------------
	 * [getWeight description]
	 * ----------------------------------------------------------------------------
	 * 
	 * @return [type] [description]
	 */
	public function getWeight()
	{
		return $this -> weight;
	}

	/**
	 * ----------------------------------------------------------------------------
	 * [getSuccess description]
	 * ----------------------------------------------------------------------------
	 * 
	 * @return [type] [description]
	 */
	public function getSuccess()
	{
		return $this -> success;
	}

	/**
	 * ----------------------------------------------------------------------------
	 * [port description]
	 * ----------------------------------------------------------------------------
	 * 
	 * @param  [type] $port [description]
	 * @return [type]       [description]
	 */
	public function port($port)
	{
		if ($this -> success && !in_array(self::$requestPort, (array)$port)) {
			$this -> success = false;
		}

		return $this;
	}

	/**
	 * ----------------------------------------------------------------------------
	 * [where description]
	 * ----------------------------------------------------------------------------
	 * 
	 * @param  array  $filter [description]
	 * @return [type]         [description]
	 */
	public function where(array $filter)
	{
		if ($this -> success && !empty($filter) && !empty($this -> params)) {
			foreach ($this -> params as $key => $value) {
				if (
					$value !== '' && 
					isset($filter[$key]) && 
					!preg_match($filter[$key], $value)
					) {
					$this -> success = false;
					break;
				}
			}
		}

		return $this;
	}

	/**
	 * ----------------------------------------------------------------------------
	 * [domain description]
	 * ----------------------------------------------------------------------------
	 * 
	 * @param  [type] $domain [description]
	 * @return [type]         [description]
	 */
	public function domain($domain)
	{
		if ($this -> success && !in_array(
			self::$requestDomain,
			array_map('strtolower', (array)$domain)
			)) {
			$this -> success = false;
		}

		return $this;
	}
}