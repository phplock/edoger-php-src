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
use Edoegr\Http\Request;

// Routing compiler.
// Through the compiler, to obtain the matching resources related to the routing.
class Compiler
{
	private $_uri;
	private $_action;
	private $_weight = 0;
	private $_regex = '';
	
	public function __construct(&$uri, &$action)
	{
		$this->_uri		= &$uri;
		$this->_action	= &$action;
	}

	public function weight()
	{
		return $this->_weight;
	}

	public function parseAction()
	{
		if (is_callable($this->_action)) {
			return $this->_action;
		}

		if (is_string($this->_action)) {
			$temp = explode('@', $this->_action);
		}
	}

	public function getUriInfo(Request $request)
	{
		if ($this->_uri === '/') {
			return [];
		} else {
			$info = preg_split('/\//', $this->_uri, 0, PREG_SPLIT_NO_EMPTY);
			$temp = [];
			foreach ($info as $key => $value) {
				if (preg_match('/^\:(\w+)(\??)$/', $value, $m)) {
					$temp[] = [2, $m[1], (bool)$m[2]];
				} else {
					$temp[] = [1, $value];
				}
			}

			return $temp;
		}
	}

	public function callAction()
	{

	}
}