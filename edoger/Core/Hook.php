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

use Error;
use Exception;
use Edoger\Interfaces\EdogerExceptionInterface;
use Edoger\Exceptions\RuntimeException;

/**
 * ================================================================================
 *
 * ================================================================================
 */
class Hook
{
	/**
	 * ----------------------------------------------------------------------------
	 * [$hooks description]
	 * ----------------------------------------------------------------------------
	 * 
	 * @var array
	 */
	private static $hooks = [];

	/**
	 * ----------------------------------------------------------------------------
	 * [$name description]
	 * ----------------------------------------------------------------------------
	 * 
	 * @var [type]
	 */
	private $name;

	/**
	 * ----------------------------------------------------------------------------
	 * [$handler description]
	 * ----------------------------------------------------------------------------
	 * 
	 * @var [type]
	 */
	private $handler;

	/**
	 * ----------------------------------------------------------------------------
	 * [$params description]
	 * ----------------------------------------------------------------------------
	 * 
	 * @var array
	 */
	private $params = [];

	/**
	 * ----------------------------------------------------------------------------
	 * [__construct description]
	 * ----------------------------------------------------------------------------
	 * 
	 * @param string   $name    [description]
	 * @param callable $handler [description]
	 */
	public function __construct(string $name, callable $handler)
	{
		$name = strtolower($name);
		if (isset(self::$hooks[$name])) {
			throw new RuntimeException("Hook {$name} already exists and cannot be repeated");
		}
		$this -> name 		= $name;
		$this -> handler 	= $handler;

		self::$hooks[$name] = &$this;
	}

	/**
	 * ----------------------------------------------------------------------------
	 * [call description]
	 * ----------------------------------------------------------------------------
	 * 
	 * @return [type] [description]
	 */
	public function call()
	{
		try {
			call_user_func_array($this -> handler, $this -> params);
			return true;
		} catch(Exception $e) {
			if ($e instanceof EdogerExceptionInterface) {
				$level 		= $e -> getLevel();
				$message 	= $e -> getLog();
				edoger() -> logger() -> log($level, $message);
			} else {
				$message 	= "{$e -> getMessage()} at {$e -> getFile()} line {$e -> getLine()}";
				edoger() -> logger() -> alert("Hook {$this -> name} runtime exception: {$message}");
			}
			return false;
		} catch(Error $e) {
			$message = "{$e -> getMessage()} at {$e -> getFile()} line {$e -> getLine()}";
			edoger() -> logger() -> alert("Hook {$this -> name} runtime exception: {$message}");
			return false;
		}
	}

	/**
	 * ----------------------------------------------------------------------------
	 * [appendParam description]
	 * ----------------------------------------------------------------------------
	 * 
	 * @param  [type]      $param [description]
	 * @param  int|integer $index [description]
	 * @return [type]             [description]
	 */
	public function addParam($param, int $index = -1)
	{
		if ($index < 0) {
			$this -> params[] = $param;
		} elseif (isset($this -> params[$index])) {
			$this -> params[$index] = $param;
		}
		return $this;
	}

	/**
	 * ----------------------------------------------------------------------------
	 * [removeParam description]
	 * ----------------------------------------------------------------------------
	 * 
	 * @param  integer $index [description]
	 * @return [type]         [description]
	 */
	public function removeParam($index = -1)
	{
		if ($index < 0) {
			$this -> params = [];
		} elseif (isset($this -> params[$index])) {
			
		}
		return $this;
	}
}