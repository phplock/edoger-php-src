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
use Edoger\Http\Cookie\CookieAuthor;

final class Response
{
	private $_output = [];
	private $_cookie = null;
	
	public function __construct()
	{
		ob_start();
	}

	public function cookie()
	{
		if (!$this->_cookie) {
			$conf = Kernel::singleton()->config();
			$this->_cookie = new CookieAuthor(
				$conf->get('cookie_secret_key'),
				[
					'expire'	=> $conf->get('cookie_expire'),
					'path'		=> $conf->get('cookie_path'),
					'domain'	=> $conf->get('cookie_domain'),
					'secure'	=> $conf->get('cookie_secure'),
					'httponly'	=> $conf->get('cookie_httponly')
				]
				);
		}

		return $this->_cookie;
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
		$this->clean()->sendHeader('Location:'.$url);
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

	public function getOutput()
	{
		return $this->_output;
	}
}