<?php
/*
 +-----------------------------------------------------------------------------+
 | Edoger PHP Framework (EdogerPHP)                                            |
 +-----------------------------------------------------------------------------+
 | Copyright (c) 2014 - 2016 QingShan Luo                                      |
 +-----------------------------------------------------------------------------+
 | The MIT License (MIT)                                                       |
 |                                                                             |
 | Permission is hereby granted, free of charge, to any person obtaining a     |
 | copy of this software and associated documentation files (the “Software”),  |
 | to deal in the Software without restriction, including without limitation   |
 | the rights to use, copy, modify, merge, publish, distribute, sublicense,    |
 | and/or sell copies of the Software, and to permit persons to whom the       |
 | Software is furnished to do so, subject to the following conditions:        |
 |                                                                             |
 | The above copyright notice and this permission notice shall be included in  |
 | all copies or substantial portions of the Software.                         |
 |                                                                             |
 | THE SOFTWARE IS PROVIDED “AS IS”, WITHOUT WARRANTY OF ANY KIND,             |
 | EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF          |
 | MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.      |
 | IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, |
 | DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR       |
 | OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE   |
 | USE OR OTHER DEALINGS IN THE SOFTWARE.                                      |
 +-----------------------------------------------------------------------------+
 |  License: MIT                                                               |
 +-----------------------------------------------------------------------------+
 |  Authors: QingShan Luo <shanshan.lqs@gmail.com>                             |
 +-----------------------------------------------------------------------------+
 */
namespace Edoger\Core\Log\Handlers;

use Edoger\Core\Log\LoggerHandlerInterface;

/**
 * ================================================================================
 * Some Description.
 *
 * 
 * ================================================================================
 */
class FileHandler implements LoggerHandlerInterface 
{
	/**
	 * ----------------------------------------------------------------------------
	 * What is it ?
	 * ----------------------------------------------------------------------------
	 *
	 * @var string
	 */
	private $file;

	/**
	 * ----------------------------------------------------------------------------
	 * What is it ?
	 * ----------------------------------------------------------------------------
	 *
	 * @var integer
	 */
	private $level;
	
	/**
	 * ----------------------------------------------------------------------------
	 * What is it ?
	 * ----------------------------------------------------------------------------
	 *
	 * @return void
	 */
	public function __construct(int $level)
	{
		$this -> level 	= $level;
		$this -> file 	= EDOGER_ROOT . '/Data/logs/' . date('Ymd') . '.log';
	}

	/**
	 * ----------------------------------------------------------------------------
	 * What is it ?
	 * ----------------------------------------------------------------------------
	 *
	 * @return boolean
	 */
	public function save(int $level, string $log)
	{
		if ($level <= $this -> level) {
			return error_log($log . PHP_EOL, 3, $this -> file);
		} else {
			return false;
		}
	}
}