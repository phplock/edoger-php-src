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
namespace Edoger\Database\Mysql;

use PDO;
use Error;
use PDOException;
use Edoger\Database\Mysql\Exceptions\ConnectException;

/**
 * ================================================================================
 * Some Description.
 *
 * 
 * ================================================================================
 */
final class Connect
{
	/**
	 * ----------------------------------------------------------------------------
	 * [$pdo description]
	 * ----------------------------------------------------------------------------
	 * 
	 * @var PDO
	 */
	private $pdo = null;

	/**
	 * ----------------------------------------------------------------------------
	 * [__construct description]
	 * ----------------------------------------------------------------------------
	 * 
	 * @param string $dsn      [description]
	 * @param string $user     [description]
	 * @param string $password [description]
	 * @param array  $options  [description]
	 */
	public function __construct(string $dsn, string $user, string $password, array $options = [])
	{
		try {
			$this -> pdo = new PDO($dsn, $user, $password, $options);
		} catch (PDOException $e) {
			// throw new ConnectException();
		} catch (Error $e) {
			// throw new ConnectException();
		}
	}

	/**
	 * ----------------------------------------------------------------------------
	 * [query description]
	 * ----------------------------------------------------------------------------
	 * 
	 * @return [type] [description]
	 */
	public function getConnected()
	{
		return $this -> pdo;
	}

	
}