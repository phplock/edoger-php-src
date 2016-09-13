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

use Closure;
use Edoger\Core\Kernel;
use Edoger\Core\Http\Request;

/**
 * =============================================================================
 * Some Description.
 *
 * 
 * =============================================================================
 */
final class Routing
{
	
	private static $params 	= [];
	private static $uri 	= '';
	private static $nodes 	= [];
	private static $size 	= 0;
	private static $shared 	= [];
	private static $manager;
	private static $middleware 	= [];
	private static $filter 		= [];
	private static $hook 		= nul;
	private static $isMatch 	= false;
	private static $casing;
	private static $action 	= null;
	private static $route 	= '';
	private static $method;
	private static $isInLoop = false;
	private static $queue = [];
	private static $weight = 0;
	private static $matchParams = [];

	/**
	 * -------------------------------------------------------------------------
	 * [__construct description]
	 * -------------------------------------------------------------------------
	 * 
	 * @param Kernel &$kernel [description]
	 */
	public function __construct(Request $request, string $middlewareNamespace)
	{
		$manager = new RouteManager();

		$params = &self::$params;
		$uri 	= &self::$uri;
		$nodes 	= &self::$nodes;
		$size 	= &self::$size;
		$shared = &self::$shared;

		(function() use (&$params, &$uri, &$nodes, &$size, &$shared){
			self::$params 	= &$params;
			self::$uri 		= &$uri;
			self::$nodes 	= &$nodes;
			self::$size 	= &$size;
			self::$shared 	= &$shared;
		}) -> call($manager);

		self::$manager = $manager;

		$casing = new RouteCasing(
			Kernel::request() -> hostname(),
			Kernel::request() -> port(),
			Kernel::request() -> protocol(),
			Kernel::request() -> xhr()
			);

		$middleware = &self::$middleware;
		$filter 	= &self::$filter;
		$hook 		= &self::$hook;
		$isMatch 	= &self::$isMatch;

		(function() use (&$middleware, &$filter, &$hook, &$isMatch){
			self::$middleware 	= &$middleware;
			self::$filter 		= &$filter;
			self::$hook 		= &$hook;
			self::$isMatch 		= &$isMatch;
		}) -> call($manager);

		self::$casing = $casing;

		RouteMiddleware::setMiddlewareNamespace(
			Kernel::app() -> config() -> get('route.middleware.namespace')
			);

		self::$method = Kernel::request() -> method();
	}

	/**
	 * -------------------------------------------------------------------------
	 * [analyzeCasing description]
	 * -------------------------------------------------------------------------
	 * 
	 * @return [type] [description]
	 */
	private static function analyzeCasing()
	{
		if (self::$isMatch) {
			self::$queue[] = [
				'middleware' 	=> self::$middleware,
				'filter' 		=> self::$filter,
				'hook' 			=> self::$hook,
				'route' 		=> self::$route,
				'action' 		=> self::$action
			];
			self::$isMatch = false;
		}
	}

	/**
	 * -------------------------------------------------------------------------
	 * What is it ?
	 * -------------------------------------------------------------------------
	 *
	 * @return type
	 */
	public static function match(array $methods, string $route, $action)
	{
		self::analyzeCasing();

		self::$isMatch = in_array(self::$method, $methods);

		if (self::$isMatch) {
			self::$route 		= $route;
			self::$action 		= $action;
			self::$middleware 	= [];
			self::$filter 		= [];
			self::$hook 		= null;
		}

		return self::$casing;
	}

	/**
	 * -------------------------------------------------------------------------
	 * [loop description]
	 * -------------------------------------------------------------------------
	 * 
	 * @param  callable|null $callback [description]
	 * @param  [type]        $other    [description]
	 * @return [type]                  [description]
	 */
	public static function loop(callable $callback = null, $other = null)
	{
		if (self::$isInLoop) {
			return;
		}

		self::$isInLoop = true;

		self::analyzeCasing();
		if (empty(self::$queue)) {
			if ($callback) {
				call_user_func($callback, true, $other);
			}
			return;
		}

		$uri 	= Kernel::request() -> requestUri();
		$size 	= 0;
		$nodes 	= [];

		if ($uri !== '/') {
			$size 	= count($nodes);
			$nodes 	= preg_split('/\//', $uri, 0, PREG_SPLIT_NO_EMPTY);
		}

		$queue = [];

		do {
			$o = array_shift(self::$queue);
			
			if (self::parseRoute($o['route'], $o['filter'], $nodes, $size, $uri)) {
				
				$o['weight'] 	= self::$weight;
				$o['nodes'] 	= $nodes;
				$o['size'] 		= $size;
				$o['params'] 	= self::$matchParams;

				if (self::$weight === 999999999) {
					
					$queue = [$o];
					break;
				}

				$queue[] = $o;
			}

		} while (!empty(self::$queue));

		if (empty($queue)) {
			
			if ($callback) {
				call_user_func($callback, true, $other);
			}
			return;
		}

		if (count($queue) > 1) {
			uasort($queue, function($a, $b){
				if ($a['weight'] === $b['weight']) {
					return 0;
				}
				return $a['weight'] > $b['weight'] ? 1 : -1;
			});
		}

		foreach ($queue as $route) {
			if (!self::runRoute($route)) {
				break;
			}
		}

		if ($callback) {
			call_user_func($callback, false, $other);
		}
	}

	/**
	 * -------------------------------------------------------------------------
	 * [parseRoute description]
	 * -------------------------------------------------------------------------
	 * 
	 * @param  string $route  [description]
	 * @param  array  $nodes  [description]
	 * @param  int    $size   [description]
	 * @param  string $uri    [description]
	 * @param  array  $filter [description]
	 * @return [type]         [description]
	 */
	private static function parseRoute(string $path, array $filter, array $nodes, int $size, string $uri)
	{
		self::$weight 		= 0;
		self::$matchParams 	= [];

		if (substr($path, 0, 1) !== '/') {
			$path = '/' . $path;
		}

		if ($path === $uri) {
			self::$weight = 999999999;
			return true;
		}

		$info 	= preg_split('/\//', $path, 0, PREG_SPLIT_NO_EMPTY);
		$length = count($info);

		if ($length < $size) {
			return false;
		}

		$equal 		= 0;
		$obligatory = 0;
		$optional 	= 0;

		$inOptional = false;

		foreach ($info as $k => $r) {
			if (isset($nodes[$k])) {

				if ($inOptional) {
					if (preg_match('//^\:([a-z]+)\?$/i', $r, $m)) {
						self::$matchParams[$m[1]] = null;
						$optional++;
						continue;
					}

					return false;
				}

				if ($r === $nodes[$k]) {
					$equal++;
				} elseif (preg_match('/^\:([a-z]+)(\??)$/i', $r, $m)) {
					
					if (isset($filter[$m[1]]) && !self::callFilter($m[1], $nodes[$k])) {
						return false;
					}

					self::$matchParams[$m[1]] = $nodes[$k];

					if ($m[2]) {
						$optional++;
						$inOptional = true;
					} else {
						$obligatory++;
					}
				} else {
					return false;
				}
			} else {
				if (preg_match('//^\:([a-z]+)\?$/i', $r, $m)) {
					self::$matchParams[$m[1]] = null;
					$optional++;
				} else {
					return false;
				}
			}
		}

		self::$weight = $equal * 1000000 + $obligatory * 1000 + $optional;
		return true;
	}

	/**
	 * -------------------------------------------------------------------------
	 * [runRoute description]
	 * -------------------------------------------------------------------------
	 * 
	 * @param  array  $route [description]
	 * @return [type]        [description]
	 */
	private static function runRoute(array $route)
	{

	}

	/**
	 * -------------------------------------------------------------------------
	 * [getManager description]
	 * -------------------------------------------------------------------------
	 * 
	 * @return [type] [description]
	 */
	public static function getManager()
	{
		return self::$manager;
	}
}