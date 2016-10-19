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

use Edoger\View\View;
use Edoger\Model\Model;
use Edoger\Core\Kernel;
use Edoger\Exception\EdogerException;

class Controller
{
	private $_view;
	private $_model;
	private $_controller = [];
	private $_namespace;

	public function __construct()
	{
		$this->_view		= new View();
		$this->_model		= new Model();
		$this->_namespace	= Kernel::singleton()->config()->get('controller_namespace', '\\');
	}

	public function load($controller)
	{
		$controller = ucfirst(strtolower($controller)).'Controller';
		if (!isset($this->_controller[$controller])) {
			$className = $this->_namespace.$controller;
			if (class_exists($className)) {
				$this->_controller[$controller] = new $className();
			} else {
				throw new EdogerException("Controller {$controller} is not found", EDOGER_ERROR_NOTFOUND_CONTROLLER);
			}
		}
		
		return $this->_controller[$controller];
	}

	public function model($mode)
	{
		return $this->_model->load(ucfirst($mode).'Model');
	}

	public function view()
	{
		return $this->_view;
	}
}