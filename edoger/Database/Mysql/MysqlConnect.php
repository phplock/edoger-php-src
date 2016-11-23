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
namespace Edoger\Database\Mysql;

use PDO;
use PDOException;
use Edoger\Core\Kernel;
use Edoger\Exception\EdogerException;

class MysqlConnect
{
	private $_pdo;

	public function __construct()
	{
		$config		= Kernel::singleton()->config();
		
		$host		= $config->get('mysql_host');
		$port		= $config->get('mysql_port');
		$socket		= $config->get('mysql_socket');
		$dbname		= $config->get('mysql_dbname');
		$charset	= $config->get('mysql_charset');
		$username	= $config->get('mysql_username');
		$password	= $config->get('mysql_password');

		if ($socket) {
			$dsn = 'mysql:unix_socket='.$socket;
		} else {
			$dsn = 'mysql:host='.$host.';port='.$port;
		}

		$dsn .= ';dbname='.$dbname.';charset='.$charset;

		try {
			$this->_pdo = new PDO($dsn, $username, $password);
		} catch(PDOException $e) {
			throw new EdogerException($e->getMessage(), EDOGER_ERROR_CONNECT, $e);
		}
	}

	public function getConnected()
	{
		return $this->_pdo;
	}
}