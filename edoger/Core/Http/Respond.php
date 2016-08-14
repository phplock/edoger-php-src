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
class Respond
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
	 * [$hook description]
	 * ----------------------------------------------------------------------------
	 * 
	 * @var [type]
	 */
	private static $hook;

	/**
	 * ----------------------------------------------------------------------------
	 * [$data description]
	 * ----------------------------------------------------------------------------
	 * 
	 * @var array
	 */
	private static $data = [];

	/**
	 * ----------------------------------------------------------------------------
	 * [$options description]
	 * ----------------------------------------------------------------------------
	 * 
	 * @var array
	 */
	private static $options = [];

	/**
	 * ----------------------------------------------------------------------------
	 * [__construct description]
	 * ----------------------------------------------------------------------------
	 * 
	 * @param Kernel &$kernel [description]
	 */
	public function __construct(Kernel &$kernel)
	{
		self::$kernel = &$kernel;
	}

	/**
	 * ----------------------------------------------------------------------------
	 * [getHook description]
	 * ----------------------------------------------------------------------------
	 * 
	 * @return [type] [description]
	 */
	public static function getHook()
	{

	}

	/**
	 * ----------------------------------------------------------------------------
	 * [send description]
	 * ----------------------------------------------------------------------------
	 * 
	 * @param  string       $key   [description]
	 * @param  [type]       $value [description]
	 * @param  bool|boolean $cover [description]
	 * @return [type]              [description]
	 */
	public static function send(string $key, $value, bool $cover = true)
	{
		if ($cover || !isset(self::$data[$key])) {
			self::$data[$key] = $value;
			return true;
		}
		return false;
	}

	/**
	 * ----------------------------------------------------------------------------
	 * [sendArray description]
	 * ----------------------------------------------------------------------------
	 * 
	 * @param  array        $data  [description]
	 * @param  bool|boolean $cover [description]
	 * @return [type]              [description]
	 */
	public static function sendArray(array $data, bool $cover = true)
	{
		if ($cover) {
			self::$data = array_merge(self::$data, $data);
		} else {
			self::$data = array_merge($data, self::$data);
		}
		return true;
	}

	/**
	 * ----------------------------------------------------------------------------
	 * [clean description]
	 * ----------------------------------------------------------------------------
	 * 
	 * @return [type] [description]
	 */
	public static function clean()
	{
		self::$data = [];
		return true;
	}

	/**
	 * ----------------------------------------------------------------------------
	 * [delete description]
	 * ----------------------------------------------------------------------------
	 * 
	 * @param  string $key [description]
	 * @return [type]      [description]
	 */
	public static function delete(string $key)
	{
		if (isset(self::$data[$key])) {
			unset(self::$data[$key]);
		}
		return true;
	}

	/**
	 * ----------------------------------------------------------------------------
	 * [end description]
	 * ----------------------------------------------------------------------------
	 * 
	 * @return [type] [description]
	 */
	public static function end(array $data = [], bool $cover = true, bool $clean = false)
	{
		if ($clean) {
			self::clean();
		}
		if (!empty($data)) {
			self::sendArray($data, $cover);
		}
		self::output();
		exit(0);
	}

	/**
	 * ----------------------------------------------------------------------------
	 * [status description]
	 * ----------------------------------------------------------------------------
	 * 
	 * @param  int    $code [description]
	 * @return [type]       [description]
	 */
	public static function status(int $code)
	{

	}

	/**
	 * ----------------------------------------------------------------------------
	 * [option description]
	 * ----------------------------------------------------------------------------
	 * 
	 * @param  string       $key   [description]
	 * @param  [type]       $value [description]
	 * @param  bool|boolean $cover [description]
	 * @return [type]              [description]
	 */
	public static function option(string $key, $value, bool $cover = true)
	{

	}

	public static function output()
	{
		static $outputed = false;
		if (!$outputed) {
			$outputed = true;
			// self::$engine -> render(self::$data, self::$options);
		}
	}
}