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

abstract class ControllerAbstract
{
	final protected function view()
	{
		return Kernel::singleton()->app()->controller()->view();
	}

	final protected function model()
	{
		return Kernel::singleton()->app()->controller()->model();
	}

	final protected function request()
	{
		return Kernel::singleton()->app()->request();
	}

	final protected function response()
	{
		return Kernel::singleton()->app()->response();
	}
}