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
 * A library for sending simple Curl requests (POST/GET only).
 *
 * Using this library can send a HTTP request to the back-end server and get the 
 * corresponding response, while automatically processing the corresponding cookie 
 * so that you don't have to worry about losing the session.
 * ================================================================================
 */
class Curl
{
	/**
	 * ----------------------------------------------------------------------------
	 * The curl setup options.
	 * ----------------------------------------------------------------------------
	 *
	 * @var array
	 */
	private $options = [];

	/**
	 * ----------------------------------------------------------------------------
	 * The cookie data.
	 * ----------------------------------------------------------------------------
	 *
	 * @var array
	 */
	private $cookies = [];

	/**
	 * ----------------------------------------------------------------------------
	 * Initialization of an curl request object.
	 * ----------------------------------------------------------------------------
	 *
	 * @param  string 	$hostname 	The server host name.
	 * @param  array 	$cookies 	The cookie data with curl request.
	 * @return void
	 */
	public function __construct(string $hostname, array $cookies = [])
	{
		$this -> options[CURLOPT_COOKIEFILE] 		= '';
		$this -> options[CURLOPT_RETURNTRANSFER] 	= true;
		$this -> options[CURLOPT_CONNECTTIMEOUT] 	= 30;
		$this -> options[CURLOPT_TIMEOUT] 			= 60;
		$this -> options[CURLOPT_HTTP_VERSION] 		= CURL_HTTP_VERSION_1_1;
		$this -> options[CURLOPT_URL] 				= $hostname;

		if (empty($cookies)) {
			$this -> cookies = $cookies;
		}
	}

	/**
	 * ----------------------------------------------------------------------------
	 * Set request header information.
	 * ----------------------------------------------------------------------------
	 * 
	 * @param  array 	$header 	The request header array.
	 * @return $this
	 */
	public function setHeader(array $header)
	{
		$this -> options[CURLOPT_HTTPHEADER] = $header;
		return $this;
	}

	/**
	 * ----------------------------------------------------------------------------
	 * Set request User-Agent information.
	 * ----------------------------------------------------------------------------
	 * 
	 * @param  string 	$userAgent 	The user-agent string.
	 * @return $this
	 */
	public function setUserAgent(string $userAgent)
	{
		$this -> options[CURLOPT_USERAGENT] = $userAgent;
		return $this;
	}

	/**
	 * ----------------------------------------------------------------------------
	 * Send an HTTP request, and return the result of the response.
	 * ----------------------------------------------------------------------------
	 *
	 * Method returns an index array containing four elements, of which the first 
	 * element in response to the result (if an error occurs, it will be false), 
	 * the second element is an HTTP response code, the third element is the error 
	 * code. The four elements is describing the error.
	 * 
	 * @param  array 	$options 	The curl setup options.
	 * @return array
	 */
	protected function send(array $options)
	{
		$result = [false, 0, 0, ''];

		$ch = curl_init();
		if ($ch === false) {
			$result[2] = 102401;
			$result[3] = 'Failed To Initialize Curl Session';
		} else {
			if (!empty($this -> cookies)) {
				$options[CURLOPT_COOKIE] = http_build_query($this -> cookies, '', '; ');
			}
			if (curl_setopt_array($ch, $options)) {
				$result[0] 	= curl_exec($ch);
				$result[1] 	= curl_getinfo($ch, CURLINFO_HTTP_CODE);
				$cookies 	= curl_getinfo($ch, CURLINFO_COOKIELIST);
				if (!empty($cookies)) {
					$now = time();
					foreach ($cookies as $v) {
						$v = preg_split('/\t/', $v);
						if ($v[4] && $v[4] <= $now) {
							if (isset($this -> cookies[$v[5]])) {
								unset($this -> cookies[$v[5]]);
							}
						} else {
							$this -> cookies[$v[5]] = $v[6];
						}
					}
				}
				$result[2] = curl_errno($ch);
				$result[3] = curl_error($ch);
			} else {
				$result[2] = 102402;
				$result[3] = 'Failed To Set The Curl Options';
			}
			curl_close($ch);
		}

		return $result;
	}

	/**
	 * ----------------------------------------------------------------------------
	 * Send a 'GET' request to the server.
	 * ----------------------------------------------------------------------------
	 *
	 * Callback function should accept five parameters: the first parameter is the 
	 * server response data (if an error occurs, this parameter is set to false), 
	 * the second parameter is server response code and the third parameter is an 
	 * error code (if there are no mistakes, this parameter will be set to 0), the 
	 * fourth parameter is describing the error (if there are no mistakes, this 
	 * parameter will be set to an empty string), the fifth parameter is pass 
	 * additional parameters.
	 *
	 * The return value of the callback function is used as the return value of the 
	 * method.
	 * 
	 * @param  string   $uri 	Requested path or file.
	 * @param  callable $action Completed callback function.
	 * @param  array    $query  Request parameter.
	 * @param  mixed 	$arg    Additional arguments for the callback function.
	 * @return mixed
	 */
	public function get(string $uri, callable $action, array $query = [], $arg = null)
	{
		$options = $this -> options;
		$options[CURLOPT_URL] .= $uri;
		$options[CURLOPT_HTTPGET] = true;
		if (!empty($query)) {
			$options[CURLOPT_URL] .= '?' . http_build_query($query, '', '&');
		}

		$data 	= $this -> send($options);
		$data[] = $arg;

		return call_user_func_array($action, $data);
	}

	/**
	 * ----------------------------------------------------------------------------
	 * Send a 'POST' request to the server.
	 * ----------------------------------------------------------------------------
	 *
	 * Callback function should accept five parameters: the first parameter is the 
	 * server response data (if an error occurs, this parameter is set to false), 
	 * the second parameter is server response code and the third parameter is an 
	 * error code (if there are no mistakes, this parameter will be set to 0), the 
	 * fourth parameter is describing the error (if there are no mistakes, this 
	 * parameter will be set to an empty string), the fifth parameter is pass 
	 * additional parameters.
	 *
	 * The return value of the callback function is used as the return value of the 
	 * method.
	 *
	 * If the argument is passed in the request, the value of the 'Content-Type' will 
	 * be set to 'multipart/form-data'.
	 * 
	 * @param  string   $uri 	Requested path or file.
	 * @param  callable $action Completed callback function.
	 * @param  array    $query  Request parameter.
	 * @param  mixed 	$arg    Additional arguments for the callback function.
	 * @return mixed
	 */
	public function post(string $uri, callable $action, array $query = [], $arg = null)
	{
		$options = $this -> options;
		$options[CURLOPT_URL] .= $uri;
		$options[CURLOPT_POST] = true;
		if (!empty($query)) {
			$options[CURLOPT_POSTFIELDS] = $query;
		}

		$data 	= $this -> send($options);
		$data[] = $arg;

		return call_user_func_array($action, $data);
	}
}