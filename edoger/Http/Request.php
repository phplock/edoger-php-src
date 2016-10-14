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

	public function path()
	{
		return urldecode(
			parse_url($this->server()->search(['PATH_INFO', 'REQUEST_URI'], '/'), PHP_URL_PATH)
			);
	}

	public function userAgent()
	{
		return $this->server()->get('HTTP_USER_AGENT');
	}

	public function agent()
	{
		if (!$this->_agent) {
			$this->_agent = new Agent($this->userAgent());
		}

		return $this->_agent;
	}

	public function referrer()
	{
		return $this->server()->get('HTTP_REFERER');
	}

	public function port()
	{
		return (int)$this->server()->get('SERVER_PORT', 0);
	}

	public function method()
	{
		return strtolower($this->server()->get('REQUEST_METHOD'));
	}

	public function scheme()
	{
		return strtolower($this->server()->get('REQUEST_SCHEME'));
	}

	public function clientIp()
	{
		if ($this->server()->exists('HTTP_CLIENT_IP')) {
			return $this->server()->get('HTTP_CLIENT_IP');
		} elseif ($this->server()->exists('HTTP_X_FORWARDED_FOR')) {
			$temp = explode(',', $this->server()->get('HTTP_X_FORWARDED_FOR'));
			return trim(reset($temp));
		} else {
			return $this->server()->get('REMOTE_ADDR', '0.0.0.0');
		}
	}
	
	public function host()
	{
		return $this->server()->get('HTTP_HOST');
	}

	public function domain()
	{
		return $this->server()->get('HTTP_HOST');
	}

	public function isXhr()
	{
		return strtoupper($this->server()->get('HTTP_X_REQUESTED_WITH')) === 'XMLHTTPREQUEST';
	}
}