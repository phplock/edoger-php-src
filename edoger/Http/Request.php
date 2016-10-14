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

final class Request
{
	private $_server;
	private $_agent = null;
	public function __construct()
	{
		$this->_server = new Server();
	}

	public function server()
	{
		return $this->_server;
	}

	public function userAgent()
	{
		return $this->_server->get('HTTP_USER_AGENT');
	}

	public function agent()
	{
		if (!$this->_agent) {
			$this->_agent = new Agent($this->userAgent());
		}

		return $this->_agent;
	}
}