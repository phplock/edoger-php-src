<?php
/**
 * Edoger PHP Framework (EdogerPHP)
 * 
 * A simple and efficient PHP framework.
 *
 * By REENT (Qingshan Luo)
 * Version 1.0.0
 *
 * http://www.edoger.com/
 *
 * The MIT License (MIT)
 * Copyright (c) 2016 REENT (Qingshan Luo)
 * Permission is hereby granted, free of charge, to any person obtaining a 
 * copy of this software and associated documentation files (the “Software”), 
 * to deal in the Software without restriction, including without limitation 
 * the rights to use, copy, modify, merge, publish, distribute, sublicense, 
 * and/or sell copies of the Software, and to permit persons to whom the 
 * Software is furnished to do so, subject to the following conditions:
 * 
 * The above copyright notice and this permission notice shall be included in 
 * all copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED “AS IS”, WITHOUT WARRANTY OF ANY KIND, 
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF 
 * MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. 
 * IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, 
 * DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR 
 * OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE 
 * USE OR OTHER DEALINGS IN THE SOFTWARE.
 */
namespace Edoger\Core;

use Edoger\Core\App\App;
use Edoger\Core\Log\Logger;
use Edoger\Core\Log\Handlers\FileHandler;
use Edoger\Exceptions\EdogerException;

/**
 * ================================================================================
 * Some Description.
 *
 * 
 * ================================================================================
 */
final class Application
{
	/**
	 * ----------------------------------------------------------------------------
	 * 配置管理组件实例
	 * ----------------------------------------------------------------------------
	 * 
	 * @var Edoger\Core\Config
	 */
	private $config;

	/**
	 * ----------------------------------------------------------------------------
	 * 核心对象
	 * ----------------------------------------------------------------------------
	 *
	 * @var Edoger\Core\Kernel
	 */
	private $kernel;

	/**
	 * ----------------------------------------------------------------------------
	 * 应用程序根目录绝对路径
	 * ----------------------------------------------------------------------------
	 *
	 * @var string
	 */
	private $root;
	
	/**
	 * ----------------------------------------------------------------------------
	 * What is it ?
	 * ----------------------------------------------------------------------------
	 * 
	 * @param  Kernel 	$kernel 	核心对象的引用
	 * @param  Config 	$config  	应用程序自身的配置管理组件实例
	 * @return void
	 */
	public function __construct(Kernel &$kernel, Config $config)
	{
		$this -> config = $config;
		$this -> kernel = &$kernel;

		$this -> root = $kernel -> root($config -> get('root', 'application'));
	}

	/**
	 * ----------------------------------------------------------------------------
	 * 获取应用程序的配置管理组件实例
	 * ----------------------------------------------------------------------------
	 *
	 * @return Edoger\Core\Config
	 */
	public function config()
	{
		return $this -> config;
	}

	/**
	 * ----------------------------------------------------------------------------
	 * 计算应用程序根目录为基准的绝对路径
	 * ----------------------------------------------------------------------------
	 *
	 * @return string
	 */
	public function root(string $uri = '')
	{
		if ($uri) {
			return $this -> root . '/' . ltrim($uri, '/');
		} else {
			return $this -> root;
		}
	}

	/**
	 * ----------------------------------------------------------------------------
	 * 运行应用程序
	 * ----------------------------------------------------------------------------
	 *
	 * @return void
	 */
	public function run()
	{
		require $this -> root . '/routes.php';
	}
}