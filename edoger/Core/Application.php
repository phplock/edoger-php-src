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
namespace Edoger\Core;

use Edoger\Http\Request;
use Edoger\Http\Response;

final class Application
{
	private $_request;
	private $_response;
	public function __construct(Kernel $kernel)
	{
		$this->_request = new Request();
		$this->_response = new Response();
	}

	public function bootstrap()
	{
		$file = APP_PATH.'/bootstrap.php';
		if (file_exists($file)) {
			require $file;
		}
		return $this;
	}

	public function request()
	{
		return $this->_request;
	}

	public function response()
	{
		return $this->_response;
	}
	public function error($error = null)
	{

	}
	public function run()
	{

	}
}