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

final class Response
{
	private $_output = [];
	public function __construct()
	{
		
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

	public function sendHeader()
	{

	}

	public function status(int $code = 0)
	{

	}

	public function clean()
	{
		$this->_output = [];
		return $this;
	}

	public function end(string $data = '')
	{

	}
}