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
namespace Edoegr\Controller;

use Edoegr\Exception\EdogerException;

class Controller
{
	private $_modelNamespace = '\\';
	private $_model = [];

	public function __construct()
	{

	}

	public function setModelNamespace($namespace)
	{
		$this->_modelNamespace = $namespace;
		return $this;
	}

	public function model($model)
	{
		$model = ucfirst(strtolower($model)).'Model';
		if (!isset($this->_model[$model])) {
			$className = $this->_modelNamespace.$model;
			if (class_exists($className, true)) {
				$this->_model[$model] = new $className();
			} else {
				throw new EdogerException("Model {$model} is not found", EDOGER_ERROR_NOTFOUND_MODEL);
			}
		}

		return $this->_model[$model];
	}

	
}