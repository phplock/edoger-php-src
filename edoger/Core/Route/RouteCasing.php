<?php
/**
 *+----------------------------------------------------------------------------+
 *| Edoger PHP Framework (Edoger)                                              |
 *+----------------------------------------------------------------------------+
 *| Copyright (c) 2014 - 2016 QingShan Luo (Reent)                             |
 *+----------------------------------------------------------------------------+
 *| The MIT License (MIT)                                                      |
 *|                                                                            |
 *| Permission is hereby granted, free of charge, to any person obtaining a    |
 *| copy of this software and associated documentation files (the “Software”), |
 *| to deal in the Software without restriction, including without limitation  |
 *| the rights to use, copy, modify, merge, publish, distribute, sublicense,   |
 *| and/or sell copies of the Software, and to permit persons to whom the      |
 *| Software is furnished to do so, subject to the following conditions:       |
 *|                                                                            |
 *| The above copyright notice and this permission notice shall be included in |
 *| all copies or substantial portions of the Software.                        |
 *|                                                                            |
 *| THE SOFTWARE IS PROVIDED “AS IS”, WITHOUT WARRANTY OF ANY KIND,            |
 *| EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF         |
 *| MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.     |
 *| IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM,|
 *| DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR      |
 *| OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE  |
 *| USE OR OTHER DEALINGS IN THE SOFTWARE.                                     |
 *+----------------------------------------------------------------------------+
 *| License: MIT                                                               |
 *+----------------------------------------------------------------------------+
 *| Authors: QingShan Luo <shanshan.lqs@gmail.com>                             |
 *+----------------------------------------------------------------------------+
 *| Link: https://www.edoger.com/                                              |
 *+----------------------------------------------------------------------------+
 */
namespace Edoger\Core\Route;

/**
 *==============================================================================
 * 
 *==============================================================================
 */
final class RouteCasing
{
	/**
	 * -------------------------------------------------------------------------
	 * [$filter description]
	 * -------------------------------------------------------------------------
	 * 
	 * @var [type]
	 */
	private $filter;
	
	/**
	 * -------------------------------------------------------------------------
	 * [$middleware description]
	 * -------------------------------------------------------------------------
	 * 
	 * @var [type]
	 */
	private $middleware;
	
	/**
	 * -------------------------------------------------------------------------
	 * [$middleware description]
	 * -------------------------------------------------------------------------
	 * 
	 * @var [type]
	 */
	private $isMatch;
	
	/**
	 * -------------------------------------------------------------------------
	 * [$middleware description]
	 * -------------------------------------------------------------------------
	 * 
	 * @var [type]
	 */
	private $domain;
	
	/**
	 * -------------------------------------------------------------------------
	 * [$middleware description]
	 * -------------------------------------------------------------------------
	 * 
	 * @var [type]
	 */
	private $port;
	
	/**
	 * -------------------------------------------------------------------------
	 * [$middleware description]
	 * -------------------------------------------------------------------------
	 * 
	 * @var [type]
	 */
	private $scheme;
	
	/**
	 * -------------------------------------------------------------------------
	 * [$middleware description]
	 * -------------------------------------------------------------------------
	 * 
	 * @var [type]
	 */
	private $xhr;
	
	/**
	 * -------------------------------------------------------------------------
	 * [__construct description]
	 * -------------------------------------------------------------------------
	 * 
	 * @param string $domain      [description]
	 * @param int    $port        [description]
	 * @param string $scheme      [description]
	 * @param bool   $xhr         [description]
	 * @param array  &$filter     [description]
	 * @param array  &$middleware [description]
	 * @param bool   &$isMatch    [description]
	 */
	public function __construct(
		string $domain,
		int $port,
		string $scheme,
		bool $xhr,
		array &$filter,
		array &$middleware,
		bool &$isMatch
		)
	{
		$this -> domain 		= $domain;
		$this -> port 			= $port;
		$this -> scheme 		= $scheme;
		$this -> xhr 			= $xhr;

		$this -> filter			= &$filter;
		$this -> middleware		= &$middleware;
		$this -> isMatch		= &$isMatch;
	}

	/**
	 * -------------------------------------------------------------------------
	 * [filter description]
	 * -------------------------------------------------------------------------
	 * 
	 * @param  array  $filter [description]
	 * @return [type]         [description]
	 */
	public function filter(array $filter)
	{
		if ($this -> isMatch) {
			$this -> filter = $filter;
		}

		return $this;
	}

	/**
	 * -------------------------------------------------------------------------
	 * [middleware description]
	 * -------------------------------------------------------------------------
	 * 
	 * @param  array  $middleware [description]
	 * @return [type]             [description]
	 */
	public function middleware(array $middleware)
	{
		if ($this -> isMatch) {
			$this -> middleware = $middleware;
		}

		return $this;
	}

	/**
	 * -------------------------------------------------------------------------
	 * [scheme description]
	 * -------------------------------------------------------------------------
	 * 
	 * @param  string $scheme [description]
	 * @return [type]         [description]
	 */
	public function scheme($scheme)
	{
		if ($this -> isMatch) {
			$this -> isMatch = in_array(
				$this -> scheme,
				array_map('strtolower', (array)$scheme)
				)
		}

		return $this;
	}

	/**
	 * -------------------------------------------------------------------------
	 * [domain description]
	 * -------------------------------------------------------------------------
	 * 
	 * @param  string $domain [description]
	 * @return [type]         [description]
	 */
	public function domain($domain)
	{
		if ($this -> isMatch) {
			$this -> isMatch = in_array(
				$this -> domain,
				array_map('strtolower', (array)$domain)
				);
		}

		return $this;
	}

	/**
	 * -------------------------------------------------------------------------
	 * [xhr description]
	 * -------------------------------------------------------------------------
	 * 
	 * @return [type] [description]
	 */
	public function xhr(bool $xhr)
	{
		if ($this -> isMatch) {
			$this -> isMatch = $this -> xhr === $xhr;
		}

		return $this;
	}


	/**
	 * -------------------------------------------------------------------------
	 * [hook description]
	 * -------------------------------------------------------------------------
	 * 
	 * @param  callable $hook [description]
	 * @return [type]         [description]
	 */
	public function listen($port)
	{
		if ($this -> isMatch) {
			$this -> isMatch = in_array($this -> port, (array)$port);
		}

		return $this;
	}
}