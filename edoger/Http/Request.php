<?php
/**
 *+------------------------------------------------------------------------------------------------+
 *| Edoger PHP Framework                                                                           |
 *+------------------------------------------------------------------------------------------------+
 *| A simple and efficient PHP framework.                                                          |
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
use Edoger\Http\Input\Input;
use Edoger\Http\Session\Session;
use Edoger\Http\Cookie\CookieReader;

// Request component class.
final class Request
{
	private $_server;
	private $_agent = null;
	private $_input = null;
	private $_cookie = null;
	private $_session = null;
	private $_cache = [];

	public function __construct()
	{
		$this->_server = new Server();
	}

	public function server()
	{
		return $this->_server;
	}

	public function input()
	{
		if (!$this->_input) {
			$this->_input = new Input($this->method());
		}

		return $this->_input;
	}

	public function get(string $key, $def = null)
	{
		return $this->input()->get($key, $def);
	}

	public function post(string $key, $def = null)
	{
		return $this->input()->post($key, $def);
	}

	public function cookie()
	{
		if (!$this->_cookie) {
			$this->_cookie = new CookieReader(Kernel::singleton()->config()->get('cookie_secret_key'));
		}
		
		return $this->_cookie;
	}

	public function session()
	{
		if (!$this->_session) {
			$this->_session = new Session();
		}
		
		return $this->_session;
	}

	public function path()
	{
		if (!isset($this->_cache['path'])) {
			$this->_cache['path'] = urldecode(
				parse_url($this->server()->search(['PATH_INFO', 'REQUEST_URI'], '/'), PHP_URL_PATH)
				);
		}

		return $this->_cache['path'];
	}

	public function pathInfo()
	{
		if (!isset($this->_cache['pathInfo'])) {
			$this->_cache['pathInfo'] = preg_split('/\//', $this->path(), 0, PREG_SPLIT_NO_EMPTY);
		}

		return $this->_cache['pathInfo'];
	}

	public function pathLength()
	{
		if (!isset($this->_cache['pathLength'])) {
			$this->_cache['pathLength'] = count($this->pathInfo());
		}

		return $this->_cache['pathLength'];
	}

	public function pathItem(int $index)
	{
		$info = $this->pathInfo();
		if ($index < 0) {
			$index += $this->pathLength();
		}

		return $info[$index] ?? null;
	}

	public function url(string $path = '')
	{
		if (!isset($this->_cache['url'])) {
			$this->_cache['url'] = $this->scheme().'://'.$this->domain();
		}

		if ($path && substr($path, 0, 1) !== '/') {
			$path = '/'.$path;
		}

		return $this->_cache['url'].$path;
	}

	public function userAgent()
	{
		if (!isset($this->_cache['userAgent'])) {
			$this->_cache['userAgent'] = $this->server()->get('HTTP_USER_AGENT');
		}

		return $this->_cache['userAgent'];
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
		if (!isset($_cache['referrer'])) {
			$_cache['referrer'] = $this->server()->get('HTTP_REFERER');
		}

		return $_cache['referrer'];
	}

	public function port()
	{
		if (!isset($_cache['port'])) {
			$_cache['port'] = (int)$this->server()->get('SERVER_PORT', 0);
		}

		return $_cache['port'];
	}

	public function method()
	{
		if (!isset($_cache['method'])) {
			$_cache['method'] = strtolower($this->server()->get('REQUEST_METHOD'));
		}

		return $_cache['method'];
	}

	public function scheme()
	{
		if (!isset($_cache['scheme'])) {
			$_cache['scheme'] = strtolower($this->server()->get('REQUEST_SCHEME'));
		}

		return $_cache['scheme'];
	}

	public function isHttps()
	{
		if (!isset($_cache['isHttps'])) {
			if ($this->server()->exists('HTTPS')) {
				$_cache['isHttps'] = !empty($this->server()->get('HTTPS'));
			} else {
				$_cache['isHttps'] = $this->scheme() === 'https';
			}
		}

		return $_cache['isHttps'];
	}

	public function clientIp()
	{
		if (!isset($_cache['clientIp'])) {
			if ($this->server()->exists('HTTP_CLIENT_IP')) {
				$_cache['clientIp'] = $this->server()->get('HTTP_CLIENT_IP');
			} elseif ($this->server()->exists('HTTP_X_FORWARDED_FOR')) {
				$temp = explode(',', $this->server()->get('HTTP_X_FORWARDED_FOR'));
				$_cache['clientIp'] = trim(reset($temp));
			} else {
				$_cache['clientIp'] = $this->server()->get('REMOTE_ADDR', '0.0.0.0');
			}
		}
		
		return $_cache['clientIp'];	
	}

	public function host()
	{
		if (!isset($_cache['host'])) {
			$_cache['host'] = $this->server()->get('HTTP_HOST');
		}

		return $_cache['host'];
	}

	public function domain()
	{
		return $this->host();
	}

	public function isXhr()
	{
		if (!isset($_cache['isXhr'])) {
			$_cache['isXhr'] = strtoupper(
				$this->server()->get('HTTP_X_REQUESTED_WITH')
				) === 'XMLHTTPREQUEST';
		}

		return $_cache['isXhr'];
	}
}