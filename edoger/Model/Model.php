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
namespace Edoger\Model;

use Edoger\Core\Kernel;
use Edoger\Exception\EdogerException;

class Model
{
	private $_model = [];
	private $_namespace;

	public function __construct()
	{
		$this->_namespace = Kernel::singleton()->config()->get('model_namespace', '\\');
	}

	public function load($model)
	{
		if (!isset($this->_model[$model])) {
			$className = $this->_namespace.$model;
			if (class_exists($className, true)) {
				$this->_model[$model] = new $className();
			} else {
				throw new EdogerException("Model {$model} is not found", EDOGER_ERROR_NOTFOUND_MODEL);
			}
		}

		return $this->_model[$model];
	}
}