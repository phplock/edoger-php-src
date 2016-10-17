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


class View implements ViewInterface
{
	private $_file;
	private $_variables = [];
	private $_view = '';
	public function __construct(string $file)
	{
		$this->_file = $file;
	}

	public function assign(array $values)
	{
		foreach ($values as $key => $value) {
			$this->_variables[$key] = $value;
		}
		
		return $this;
	}

	public function delete(string $key)
	{
		if (array_key_exists($key, $this->_variables)) {
			unset($this->_variables[$key]);
		}

		return $this;
	}

	public function set(string $key, $value)
	{
		$this->_variables[$key] = $value;
		return $this;
	}

	public function get(string $key)
	{
		return $this->_variables[$key] ?? null;
	}

	public function getAll()
	{
		return $this->_variables;
	}

	public function display(array $variables = [])
	{
		if (!file_exists($this->_file)) {
			
		}
		
		if (!empty($variables)) {
			
		}

		ob_start();
		require $this->_file;
		$this->_view = ob_get_clean();
	}

	public function flush()
	{

	}
}