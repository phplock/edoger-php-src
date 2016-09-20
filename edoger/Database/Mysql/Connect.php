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
	 * -------------------------------------------------------------------------
	 * [$pdo description]
	 * -------------------------------------------------------------------------
	 * 
	 * @var PDO
	 */
	private $pdo = null;

	/**
	 * -------------------------------------------------------------------------
	 * [$message description]
	 * -------------------------------------------------------------------------
	 * 
	 * @var  string
	 */
	private $message = '';

	/**
	 * -------------------------------------------------------------------------
	 * [$error description]
	 * -------------------------------------------------------------------------
	 * 
	 * @var  bool
	 */
	private $error = false;

	/**
	 * -------------------------------------------------------------------------
	 * [__construct description]
	 * -------------------------------------------------------------------------
	 * 
	 * @param  array  $config  [description]
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
	 * -------------------------------------------------------------------------
	 * [connected description]
	 * -------------------------------------------------------------------------
	 * 
	 * @return  [type]
	 */
	public function connected()
	{
		return $this -> pdo;
	}

	/**
	 * -------------------------------------------------------------------------
	 * [version description]
	 * -------------------------------------------------------------------------
	 * 
	 * @return  [type]
	 */
	public function version()
	{
		return $this -> pdo -> getAttribute(PDO::ATTR_SERVER_VERSION);
	}

	/**
	 * -------------------------------------------------------------------------
	 * [error description]
	 * -------------------------------------------------------------------------
	 * 
	 * @return  [type]
	 */
	public function error()
	{
		return $this -> error;
	}

	/**
	 * -------------------------------------------------------------------------
	 * [errorMessage description]
	 * -------------------------------------------------------------------------
	 * 
	 * @return  [type]
	 */
	public function errorMessage()
	{
		return $this -> message;
	}

	/**
	 * -------------------------------------------------------------------------
	 * [create description]
	 * -------------------------------------------------------------------------
	 * 
	 * @param   string  $sql      [description]
	 * @param   array   $params   [description]
	 * @param   array   $options  [description]
	 * @return  [type]
	 */
	public function create(string $sql, array $params = [], array $options = [])
	{
		$this -> message	= '';
		$this -> error		= false;

		$error		= false;
		$message	= '';

		try {
			$statement = $this -> pdo -> prepare($sql, $options);
			if ($statement) {
				if (!$statement -> execute($params)) {
					$error	= true;
					$info	= $statement -> errorInfo();
					
					if ($info && isset($info[2])) {
						$message = $info[2];
					} else {
						$message = 'Execute SQL statement error';
					}
				}
			} else {
				$error	= true;
				$info	= $this -> pdo -> errorInfo();

				if ($info && isset($info[2])) {
					$message = $info[2];
				} else {
					$message = 'Unable to create PDOStatement object';
				}
			}
		} catch(Exception $e) {
			$error		= true;
			$message	= $e -> getMessage();
		} catch(Error $e) {
			$error		= true;
			$message	= $e -> getMessage();
		}
		
		if ($error) {
			$paramsInfo			= json_encode($params);
			$this -> error		= true;
			$this -> message	= "{$message}; With SQL: {$sql}; With params: {$paramsInfo}";
			return false;
		} else {
			return $statement;
		}
	}

	/**
	 * -------------------------------------------------------------------------
	 * [inTransaction description]
	 * -------------------------------------------------------------------------
	 * 
	 * @return  [type]
	 */
	public function inTransaction()
	{
		return $this -> pdo -> inTransaction();
	}

	/**
	 * -------------------------------------------------------------------------
	 * [beginTransaction description]
	 * -------------------------------------------------------------------------
	 * 
	 * @return  [type]
	 */
	public function beginTransaction()
	{
		if ($this -> pdo -> inTransaction()) {
			return true;
		} else {
			return $this -> pdo -> beginTransaction();
		}
	}

	/**
	 * -------------------------------------------------------------------------
	 * [sendTransaction description]
	 * -------------------------------------------------------------------------
	 * 
	 * @return  [type]
	 */
	public function sendTransaction()
	{
		if ($this -> pdo -> inTransaction()) {
			if ($this -> pdo -> commit()) {
				return true;
			} else {
				if ($this -> pdo -> inTransaction()) {
					$this -> pdo -> rollBack();
				}
				return false;
			}
		} else {
			return true;
		}
	}

	/**
	 * -------------------------------------------------------------------------
	 * [stopTransaction description]
	 * -------------------------------------------------------------------------
	 * 
	 * @return  [type]
	 */
	public function stopTransaction()
	{
		if ($this -> pdo -> inTransaction()) {
			return $this -> pdo -> rollBack();
		} else {
			return true;
		}
	}
}