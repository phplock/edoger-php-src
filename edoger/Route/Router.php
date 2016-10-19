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
namespace Edoger\Route;

use Edoger\Core\Kernel;

class Router
{
	private $_path;
	private $_info;
	private $_size;
	private $_defaultController;
	private $_defaultAction;

	public function __construct()
	{
		$config = Kernel::singleton()->config();
		$this->_defaultController = $config->get('default_controller', 'index');
		$this->_defaultAction = $config->get('default_action', 'index');
		$this->_path = Kernel::singleton()->app()->request()->path();
		$this->_info = preg_split('/\//', $this->_path, 0, PREG_SPLIT_NO_EMPTY);
		$this->_size = count($this->_info);
	}

	public function getControllerName()
	{
		return $this->item(0, $this->_defaultController);
	}

	public function getActionName()
	{
		return $this->item(1, $this->_defaultAction);
	}

	public function item($index, $def = null)
	{
		if ($index < 0) {
			$index += $this->_size;
		}

		return isset($this->_info[$index]) ? $this->_info[$index] : $def;
	}
}