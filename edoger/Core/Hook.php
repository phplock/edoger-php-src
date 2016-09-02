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
namespace Edoger\Core;

use Error;
use Exception;

/**
 * =============================================================================
 *
 * =============================================================================
 */
class Hook
{
	/**
	 * -------------------------------------------------------------------------
	 * [$hooks description]
	 * -------------------------------------------------------------------------
	 * 
	 * @var array
	 */
	private static $hooks = [];

	/**
	 * -------------------------------------------------------------------------
	 * [$errorCode description]
	 * -------------------------------------------------------------------------
	 * 
	 * @var integer
	 */
	private static $errorCode 		= 0;

	/**
	 * -------------------------------------------------------------------------
	 * [$errorMessage description]
	 * -------------------------------------------------------------------------
	 * 
	 * @var string
	 */
	private static $errorMessage 	= '';

	/**
	 * -------------------------------------------------------------------------
	 * [create description]
	 * -------------------------------------------------------------------------
	 * 
	 * @param  string       $name  [description]
	 * @param  callable     $hook  [description]
	 * @param  bool|boolean $cover [description]
	 * @return [type]              [description]
	 */
	public static function create(string $name, callable $hook, bool $cover = true)
	{
		$name = strtolower($name);

		if ($cover || !isset(self::$hooks[$name])) {
			self::$hooks[$name] = [$hook, 'always'];
			return true;
		} else {
			return false;
		}
	}

	/**
	 * -------------------------------------------------------------------------
	 * [createOnce description]
	 * -------------------------------------------------------------------------
	 * 
	 * @param  string       $name  [description]
	 * @param  callable     $hook  [description]
	 * @param  bool|boolean $cover [description]
	 * @return [type]              [description]
	 */
	public static function createOnce(string $name, callable $hook, bool $cover = true)
	{
		$name = strtolower($name);

		if ($cover || !isset(self::$hooks[$name])) {
			self::$hooks[$name] = [$hook, 'once'];
			return true;
		} else {
			return false;
		}
	}

	/**
	 * -------------------------------------------------------------------------
	 * [remove description]
	 * -------------------------------------------------------------------------
	 * 
	 * @param  string $name [description]
	 * @return [type]       [description]
	 */
	public static function remove(string $name)
	{
		$name = strtolower($name);

		if (isset(self::$hooks[$name])) {
			unset(self::$hooks[$name]);
		}
		return true;
	}

	/**
	 * -------------------------------------------------------------------------
	 * [exists description]
	 * -------------------------------------------------------------------------
	 * 
	 * @param  string $name [description]
	 * @return [type]       [description]
	 */
	public static function exists(string $name)
	{
		return isset(self::$hooks[strtolower($name)]);
	}

	/**
	 * -------------------------------------------------------------------------
	 * [call description]
	 * -------------------------------------------------------------------------
	 * 
	 * @param  string $name   [description]
	 * @param  array  $params [description]
	 * @return [type]         [description]
	 */
	public function call(string $name, array $params = [])
	{
		$name = strtolower($name);

		self::$errorCode 	= 0;
		self::$errorMessage = '';

		if (isset(self::$hooks[$name])) {
			$handler = self::$hooks[$name][0];
			if (self::$hooks[$name][1] === 'once') {
				unset(self::$hooks[$name]);
			}

			try {
				call_user_func_array($handler, $params);
			} catch(Exception $e) {
				self::$errorCode 	= $e -> getCode();
				self::$errorMessage = $e -> getMessage() . ' at ' . $e -> getFile() . ' line ' . $e -> getLine();
				return false;
			} catch(Error $e) {
				self::$errorCode 	= $e -> getCode();
				self::$errorMessage = $e -> getMessage() . ' at ' . $e -> getFile() . ' line ' . $e -> getLine();
				return false;
			}
		}
		return true;
	}

	/**
	 * -------------------------------------------------------------------------
	 * [appendParam description]
	 * -------------------------------------------------------------------------
	 * 
	 * @param  [type]      $param [description]
	 * @param  int|integer $index [description]
	 * @return [type]             [description]
	 */
	public static function getLastErrorCode()
	{
		return self::$errorCode;
	}

	/**
	 * -------------------------------------------------------------------------
	 * [removeParam description]
	 * -------------------------------------------------------------------------
	 * 
	 * @param  integer $index [description]
	 * @return [type]         [description]
	 */
	public static function getLastErrorMessage()
	{
		return self::$errorMessage;
	}
}