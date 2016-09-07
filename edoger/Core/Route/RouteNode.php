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

use Edoger\Core\Http\Request;
use Edoger\Core\Http\Respond;
use Edoger\Core\Controller;

/**
 * =============================================================================
 * Some Description.
 *
 * 
 * =============================================================================
 */
class RouteNode
{
	/**
	 * -------------------------------------------------------------------------
	 * [$requestPath description]
	 * -------------------------------------------------------------------------
	 * 
	 * @var string
	 */
	public static $requestPath = '';

	/**
	 * -------------------------------------------------------------------------
	 * [$requestPathNodes description]
	 * -------------------------------------------------------------------------
	 * 
	 * @var array
	 */
	public static $requestPathNodes = [];

	/**
	 * -------------------------------------------------------------------------
	 * [$requestPathNodesSize description]
	 * -------------------------------------------------------------------------
	 * 
	 * @var integer
	 */
	public static $requestPathNodesSize = 0;

	/**
	 * -------------------------------------------------------------------------
	 * What is it ?
	 * -------------------------------------------------------------------------
	 *
	 * @var type
	 */
	private $weight = 0;

	/**
	 * -------------------------------------------------------------------------
	 * What is it ?
	 * -------------------------------------------------------------------------
	 *
	 * @var type
	 */
	private $uri;

	/**
	 * -------------------------------------------------------------------------
	 * What is it ?
	 * -------------------------------------------------------------------------
	 *
	 * @var type
	 */
	private $action;

	/**
	 * -------------------------------------------------------------------------
	 * What is it ?
	 * -------------------------------------------------------------------------
	 *
	 * @var type
	 */
	private $middleware = [];
	private $filter = [];
	
	/**
	 * -------------------------------------------------------------------------
	 * [__construct description]
	 * -------------------------------------------------------------------------
	 * 
	 * @param array  $method [description]
	 * @param array  $pignut [description]
	 * @param [type] $action [description]
	 */
	public function __construct(array $middleware, array $filter, $action)
	{

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


	public function match(array $nodes, int $size, $action)
	{
		
	}

	public function weight()
	{
		return $this -> weight;
	}

	public function call()
	{

	}

	/**
	 * -------------------------------------------------------------------------
	 * [parseAction description]
	 * -------------------------------------------------------------------------
	 * 
	 * @param  [type] $action [description]
	 * @return [type]         [description]
	 */
	private static function callAction($action)
	{
		if (is_callable($action)) {

			return call_user_func($action, Request::singleton(), Respond::singleton());
		} elseif (is_string($action) && preg_match('/^(\w+)@(\w+)$/', $action, $matches)) {

			return Controller::callControllerAction($matches[1], $matches[2]);
		} else {

			throw new Exception("Error Processing Request", 1);
		}
	}

	/**
	 * -------------------------------------------------------------------------
	 * [getWeight description]
	 * -------------------------------------------------------------------------
	 * 
	 * @return [type] [description]
	 */
	public function getWeight()
	{
		return $this -> weight;
	}
}