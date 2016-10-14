<?php
/**
 *+------------------------------------------------------------------------------------------------+
 *| Edoger PHP Framework                                                                           |
 *+------------------------------------------------------------------------------------------------+
 *| A simple route analysis and matching module.                                                   |
 *+------------------------------------------------------------------------------------------------+
 *| @package   edoger-php-src                                                                      |
 *| @license   MIT                                                                                 |
 *| @link      https://www.edoger.com/                                                             |
 *| @copyright Copyright (c) 2014 - 2016, QingShan Luo                                             |
 *| @version   1.0.0 Alpha                                                                         |
 *+------------------------------------------------------------------------------------------------+
 *| @author    Qingshan Luo <shanshan.lqs@gmail.com>                                               |
 *+------------------------------------------------------------------------------------------------+
 */
namespace Edoger\Http;

use Edoger\Core\Kernel;

final class Response
{
	private $_output = [];
	public function __construct()
	{
		ob_start();
	}

	public function send(string $data)
	{
		$this->_output[] = $data;
		return $this;
	}

	public function sendFile(string $path)
	{

	}

	public function sendView()
	{

	}

	public function sendHeader(string $header, bool $replace = true, int $code = 0)
	{
		if ($code > 0) {
			return header($header, $replace, $code);
		} else {
			return header($header, $replace);
		}
	}

	public function location(string $url, int $code = 302)
	{
		$this->status($code);
		if (substr($url, 0, 1) === '/') {
			$url = Kernel::singleton()->app()->request()->url($url);
		}
		$this->clean();
		$this->sendHeader('Location:'.$url);
		exit(0);
	}

	public function status(int $code = 0)
	{
		if ($code <= 0) {
			return http_response_code();
		} else {
			return http_response_code($code);
		}
	}

	public function clean()
	{
		$this->_output = [];
		return $this;
	}

	public function end(string $data = '')
	{

	}

	public function getOutput()
	{
		return $this->_output;
	}
}