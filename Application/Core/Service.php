<?php
/**
 *+----------------------------------------------------------------------------+
 *| Edoger PHP Framework (Edoger)                                              |
 *+----------------------------------------------------------------------------+
 *| Copyright (c) 2014 - 2016 QingShan Luo (Reent)                             |
 *+----------------------------------------------------------------------------+
 *| The MIT License (MIT)                                                      |
 *|                                                                            |
 *| Permission is hereby granted, free of charge, to any person obtaining a    |
 *| copy of this software and associated documentation files (the “Software”), |
 *| to deal in the Software without restriction, including without limitation  |
 *| the rights to use, copy, modify, merge, publish, distribute, sublicense,   |
 *| and/or sell copies of the Software, and to permit persons to whom the      |
 *| Software is furnished to do so, subject to the following conditions:       |
 *|                                                                            |
 *| The above copyright notice and this permission notice shall be included in |
 *| all copies or substantial portions of the Software.                        |
 *|                                                                            |
 *| THE SOFTWARE IS PROVIDED “AS IS”, WITHOUT WARRANTY OF ANY KIND,            |
 *| EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF         |
 *| MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.     |
 *| IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM,|
 *| DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR      |
 *| OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE  |
 *| USE OR OTHER DEALINGS IN THE SOFTWARE.                                     |
 *+----------------------------------------------------------------------------+
 *| License: MIT                                                               |
 *+----------------------------------------------------------------------------+
 *| Authors: QingShan Luo <shanshan.lqs@gmail.com>                             |
 *+----------------------------------------------------------------------------+
 *| Link: https://www.edoger.com/                                              |
 *+----------------------------------------------------------------------------+
 */
namespace Application\Core;

use Closure;
use Edoger\Core\Config;
use Edoger\Core\Kernel;

/**
 *==============================================================================
 * 应用程序服务的核心类。
 *==============================================================================
 */
final class Service
{
	/**
	 * -------------------------------------------------------------------------
	 * [$config description]
	 * -------------------------------------------------------------------------
	 * 
	 * @var [type]
	 */
	private $config;
	
	/**
	 * -------------------------------------------------------------------------
	 * [__construct description]
	 * -------------------------------------------------------------------------
	 * 
	 * @param Config $config [description]
	 */
	public function __construct(Config $config)
	{
		$this -> config = $config;
	}

	/**
	 * -------------------------------------------------------------------------
	 * [config description]
	 * -------------------------------------------------------------------------
	 * 
	 * @return [type] [description]
	 */
	public function config()
	{
		return $this -> config;
	}

	/**
	 * -------------------------------------------------------------------------
	 * [make description]
	 * -------------------------------------------------------------------------
	 * 
	 * @param  Kernel $kernel [description]
	 * @return [type]         [description]
	 */
	public function make(Kernel $kernel)
	{
		$callable = function($app){
			self::$application = $app;
		};
		
		$callable -> call($kernel, $this);
		$callable -> call($kernel -> request(), $this);
		$callable -> call($kernel -> response(), $this);
	}

	/**
	 * -------------------------------------------------------------------------
	 * [start description]
	 * -------------------------------------------------------------------------
	 * 
	 * @return [type] [description]
	 */
	public function start()
	{

	}
}