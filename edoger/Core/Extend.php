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

use Edoger\Exception\EdogerException;

final class Extend
{
	private $_extend = [];
	private $_namespace;

	public function __construct()
	{
		$this->_namespace = Kernel::singleton()->config()->get('user_extend_namespace', '\\');
	}

	private function load($extend)
	{
		$extend = strtolower($extend);

		if (!isset($this->_extend[$extend])) {
			$userExtendClassName = $this->_namespace.ucfirst($extend).'UserExtend';
			if (class_exists($userExtendClassName, true)) {
				$this->_extend[$extend] = new $userExtendClassName();
			} else {
				$extendClassName = '\\Edoger\\Extend\\'.ucfirst($extend).'Extend';
				if (class_exists($extendClassName, true)) {
					$this->_extend[$extend] = new $extendClassName();
				} else {
					throw new EdogerException('Extend {$extend} is not found', EDOGER_ERROR_NOTFOUND_EXTEND);
				}
			}
		}

		return $this->_extend[$extend];
	}
}