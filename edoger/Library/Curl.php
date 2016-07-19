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
namespace Edoger\Library;

/**
 * ================================================================================
 * Some Description.
 *
 * 
 * ================================================================================
 */
class Curl
{
	/**
	 * ----------------------------------------------------------------------------
	 * What is it ?
	 * ----------------------------------------------------------------------------
	 *
	 * @var integer
	 */
	protected $errno = 0;

	/**
	 * ----------------------------------------------------------------------------
	 * What is it ?
	 * ----------------------------------------------------------------------------
	 *
	 * @var string
	 */
	protected $errstr = '';

	/**
	 * ----------------------------------------------------------------------------
	 * What is it ?
	 * ----------------------------------------------------------------------------
	 *
	 * @var array
	 */
	protected $options = [];

	/**
	 * ----------------------------------------------------------------------------
	 * What is it ?
	 * ----------------------------------------------------------------------------
	 *
	 * @var string
	 */
	protected $hostname = '';

	/**
	 * ----------------------------------------------------------------------------
	 * What is it ?
	 * ----------------------------------------------------------------------------
	 *
	 * @var array
	 */
	protected $cookies = [];

	/**
	 * ----------------------------------------------------------------------------
	 * What is it ?
	 * ----------------------------------------------------------------------------
	 *
	 * @var resource
	 */
	protected $curl = null;

	/**
	 * ----------------------------------------------------------------------------
	 * What is it ?
	 * ----------------------------------------------------------------------------
	 *
	 * @param string $hostname The server host name.
	 * @param array  $cookies  The curl global options.
	 * @return void
	 */
	public function __construct(string $hostname, array $cookies = [])
	{
		$this -> hostname = $hostname;

		$this -> options[CURLOPT_COOKIEFILE] 		= '';
		$this -> options[CURLOPT_RETURNTRANSFER] 	= true;
		if (empty($cookies)) {
			$this -> options[CURLOPT_COOKIE] = '';
		} else {
			$this -> options[CURLOPT_COOKIE] = http_build_query($cookies, '', '; ');
		}

		$this -> initCurl();
	}

	/**
	 * ----------------------------------------------------------------------------
	 * What is it ?
	 * ----------------------------------------------------------------------------
	 *
	 * @return void
	 */
	public function __destruct()
	{
		$this -> close();
	}

	/**
	 * ----------------------------------------------------------------------------
	 * What is it ?
	 * ----------------------------------------------------------------------------
	 *
	 * @return Edoger\Library\Curl
	 */
	public function close()
	{
		if ($this -> curl) {
			curl_close($this -> curl);
			$this -> curl = null;
		}

		return $this;
	}

	/**
	 * ----------------------------------------------------------------------------
	 * What is it ?
	 * ----------------------------------------------------------------------------
	 *
	 * @return Edoger\Library\Curl
	 */
	public function option(int $key, $value)
	{
		if ($value === null) {
			if (isset($this -> options[$key])) {
				unset($this -> options[$key]);
			}
		} else {
			$this -> options[$key] = $value;
		}

		return $this;
	}

	/**
	 * ----------------------------------------------------------------------------
	 * What is it ?
	 * ----------------------------------------------------------------------------
	 *
	 * @return integer
	 */
	public function errorCode()
	{
		return $this -> errno;
	}

	/**
	 * ----------------------------------------------------------------------------
	 * What is it ?
	 * ----------------------------------------------------------------------------
	 *
	 * @return string
	 */
	public function errorMessage()
	{
		return $this -> errstr;
	}

	/**
	 * ----------------------------------------------------------------------------
	 * What is it ?
	 * ----------------------------------------------------------------------------
	 *
	 * @param  string   $method    The curl request method.
	 * @param  string   $uri       The curl request uri.
	 * @param  array    $query     Additional request parameters.
	 * @param  callable $callback  Upon completion of the request, the callback 
	 *                             function should be called immediately.
	 * @param  mixed    $arguments Additional arguments for the callback function.
	 * @param  bool     $reload    Whether to use the new curl session?
	 * @return mixed
	 */
	protected function send(string $method, string $uri, array $query, callable $callback, $arguments, bool $reload)
	{
		static $ch = null;

		if ($reload && $ch) {
			curl_close($ch);
			$ch = null;
		}

		$this -> errno 	= 0;
		$this -> errstr = '';

		if ($ch === null) {
			$ch = curl_init();
			if ($ch === false) {
				$ch = null;


				return false;
			}
		}

		
	}

	/**
	 * ----------------------------------------------------------------------------
	 * What is it ?
	 * ----------------------------------------------------------------------------
	 *
	 * @return boolean
	 */
	protected function initCurl()
	{
		$this -> close() -> curl = curl_init();
		if ($this -> curl === false) {
			
			$this -> errno 	= 0;
			$this -> errstr = '';
			return false;
		}

		return true;
	}
}