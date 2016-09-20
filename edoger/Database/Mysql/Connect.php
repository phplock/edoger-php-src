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
namespace Edoger\Database\Mysql;

use PDO;
use Error;
use Exception;
use PDOStatement;
use Edoger\Database\Mysql\Exceptions\ConnectException;

/**
 *+----------------------------------------------------------------------------+
 *| 数据库连接组件。                                                           |
 *+----------------------------------------------------------------------------+
 */
final class Connect
{
	const ERR_DRIVE = 12000000;
	const ERR_CONNECT = 12000001;

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
	public function __construct(array $config)
	{
		if (!in_array('mysql', PDO::getAvailableDrivers())) {
			throw new ConnectException('Unsupported database type: mysql', self::ERR_DRIVE);
		}

		$socket		= $config['socket'];
		$host		= $config['host'];
		$port		= $config['port'];
		$dbname		= $config['dbname'];
		$charset	= $config['charset'];
		$username	= $config['username'];
		$password	= $config['password'];
		$options	= $config['options'];

		if ($socket) {
			$dsn = "mysql:unix_socket={$socket};dbname={$dbname};charset={$charset}";
		} else {
			$dsn = "mysql:host={$host};port={$port};dbname={$dbname};charset={$charset}";
		}

		try {
			$this -> pdo = new PDO($dsn, $username, $password, $options);
		} catch (Exception $e) {
			throw new ConnectException($e -> getMessage(), self::ERR_CONNECT);
		} catch (Error $e) {
			throw new ConnectException($e -> getMessage(), self::ERR_CONNECT);
		}
	}

	/**
	 * ----------------------------------------------------------------------------
	 * [query description]
	 * ----------------------------------------------------------------------------
	 * 
	 * @return [type] [description]
	 */
	public function connected()
	{
		return $this -> pdo;
	}

	public function version()
	{
		return $this -> pdo -> getAttribute(PDO::ATTR_SERVER_VERSION);
	}

	public function hasError(PDOStatement $statement = null)
	{
		if ($statement) {
			return PDO::ERR_NONE === $statement -> errorCode();
		} else {
			return PDO::ERR_NONE === $this -> pdo -> errorCode();
		}
	}

	public function errorMessage(PDOStatement $statement = null)
	{
		if ($statement) {
			$info = $statement -> errorInfo()[2];
		} else {
			$info = $this -> pdo -> errorCode()[2];
		}

		if ($info && isset($info[2])) {
			return $info[2];
		} else {
			return '';
		}
	}

	public function create(string $sql, array $options = [])
	{
		
	}
}