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

use Edoger\Core\Application;
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
	 * [$data description]
	 * ----------------------------------------------------------------------------
	 * 
	 * @var array
	 */
	private static $respondData = [];

	/**
	 * ----------------------------------------------------------------------------
	 * [$options description]
	 * ----------------------------------------------------------------------------
	 * 
	 * @var array
	 */
	private static $respondOptions = [];

	/**
	 * ----------------------------------------------------------------------------
	 * [__construct description]
	 * ----------------------------------------------------------------------------
	 * 
	 * @param Kernel &$kernel [description]
	 */
	public function __construct(Application $app)
	{
		$app -> make($this);
	}

	/**
	 * ----------------------------------------------------------------------------
	 * [clean description]
	 * ----------------------------------------------------------------------------
	 * 
	 * @return boolean
	 */
	public function clean()
	{
		self::$respondData = [];
		return true;
	}

	/**
	 * ----------------------------------------------------------------------------
	 * [send description]
	 * ----------------------------------------------------------------------------
	 * 
	 * @param  string $key   [description]
	 * @param  string $value [description]
	 * @return boolean
	 */
	public function send(string $value)
	{
		self::$respondData[] = $value;
		return true;
	}

	public function json(array $data)
	{
		$json = json_encode($data);
		if ($json === false) {
			Kernel::error('JSON encode error: ' . json_last_error_msg(), 500);
		} else {
			$this -> end($json);
		}
	}

	public function sendFile(string $path)
	{
		if (file_exists($path) && !is_dir($path)) {
			$file = file_get_contents($path);
			if ($file === false) {
				Kernel::error('System read file failed: ' . $path, 500);
			} else {
				$this -> end($file);
			}
		} else {
			Kernel::error('The system could not find the file: ' . $path, 500);
		}
	}

	public function render()
	{
		
	}

	/**
	 * ----------------------------------------------------------------------------
	 * [end description]
	 * ----------------------------------------------------------------------------
	 * 
	 * @param  boolean $clean [description]
	 * @return void
	 */
	public function end(string $value = '')
	{
		if ($value !== '') {
			self::$respondData[] = $value;
		}
		
		Kernel::quit();
	}

	/**
	 * ----------------------------------------------------------------------------
	 * [status description]
	 * ----------------------------------------------------------------------------
	 * 
	 * @param  int    $code [description]
	 * @return [type]       [description]
	 */
	public function httpCode(int $code = 0)
	{
		if ($code) {
			return http_response_code($code);
		} else {
			return http_response_code();
		}
	}

	public function header(string $head, bool $replace = true, int $code = null)
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
	public function option(string $key, $value = null)
	{
		if (is_null($value)) {
			if (isset(self::$respondOptions[$key])) {
				unset(self::$respondOptions[$key]);
			}
			return true;
		} else {
			self::$respondOptions[$key] = $value;
			return true;
		}
	}


	

	
}