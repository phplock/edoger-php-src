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
namespace Edoger\Controller;

use Edoger\Core\Kernel;
use Edoger\Exception\EdogerException;

abstract class ControllerAbstract
{
	final protected function view()
	{
		return Kernel::singleton()->app()->controller()->view();
	}

	final protected function model($mode)
	{
		return Kernel::singleton()->app()->controller()->model(strtolower($mode));
	}

	final protected function request()
	{
		return Kernel::singleton()->app()->request();
	}

	final protected function response()
	{
		return Kernel::singleton()->app()->response();
	}

	final public function __call($method, $params)
	{
		throw new EdogerException("Undefined method {$method} in controller", EDOGER_ERROR_METHOD);
	}
}