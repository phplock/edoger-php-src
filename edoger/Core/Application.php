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
use App\Bootstrap;

final class Application
{
	private $_request;
	private $_response;
	public function __construct()
	{
		$this->_request		= new Request();
		$this->_response	= new Response();
	}

	public function bootstrap()
	{
		$bootstrap	= new Bootstrap();
		$methods	= get_class_methods($bootstrap);
		if (is_array($methods)) {
			$kernel = Kernel::singleton();
			foreach ($methods as $method) {
				if (substr($method, 0, 4) === 'init') {
					$bootstrap->$method($kernel);
				}
			}
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
		$router = Kernel::singleton()->router();
		
	}
}