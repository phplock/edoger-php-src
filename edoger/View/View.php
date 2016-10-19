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
namespace Edoger\View;

use Edoger\Core\Kernel;
use Edoger\Exception\EdogerException;

class View
{
	private $_variables = [];
	private $_viewFile = '';
	private $_directory;
	private $_extensionName;
	private $_defaultView;

	public function __construct()
	{
		$config = Kernel::singleton()->config();

		$this->_directory = $config->get('view_directory', '');
		$this->_extensionName = $config->get('view_extension_name', 'phtml');
		$this->_defaultView = $config->get('default_view', 'index');
	}

	public function assign($name, $value = null)
	{
		if (is_array($name)) {
			foreach ($name as $key => $value) {
				$this->_variables[$key] = $value;
			}
		} else {
			$this->_variables[$name] = $value;
		}
		
		return $this;
	}

	public function clean()
	{
		$this->_variables = [];
		return $this;
	}

	public function remove($name)
	{
		if (array_key_exists($name, $this->_variables)) {
			unset($this->_variables[$name]);
		}

		return $this;
	}

	public function display($name = '')
	{
		if ($name === '') {
			$name = $this->_defaultView;
		}

		$controller = ucfirst(strtolower(Kernel::singleton()->router()->getControllerName()));
		$this->_viewFile = $this->_directory.'/'.$controller.'/'.$name.'.'.$this->_extensionName;

		unset($name, $controller);

		if (file_exists($this->_viewFile)) {
			if (!empty($this->_variables)) {
				extract($this->_variables, EXTR_OVERWRITE);
			}
			ob_start();
			require $this->_viewFile;
			Kernel::singleton()->app()->response()->send(ob_get_clean());
		} else {
			throw new EdogerException("View {$this->_viewFile} is not found", EDOGER_ERROR_NOTFOUND_VIEW);
		}
	}
}