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
namespace Edoger\Database\Mysql\Driver;

use PDO;
use PDOException;
use Edoger\Core\Kernel;
use Edoger\Exception\EdogerException;

class PdoDriver implements DriverInterface
{
	private $_pdo = null;
	private $_host;
	private $_port;
	private $_socket;
	private $_dbname;
	private $_charset;
	private $_username;
	private $_password;
	private $_errorCode = 0;
	private $_errorMessage = '';

	public function __construct()
	{
		$config = Kernel::singleton()->config();
		
		$this->_host		= $config->get('mysql_host');
		$this->_port		= $config->get('mysql_port');
		$this->_socket		= $config->get('mysql_socket');
		$this->_dbname		= $config->get('mysql_dbname');
		$this->_charset		= $config->get('mysql_charset');
		$this->_username	= $config->get('mysql_username');
		$this->_password	= $config->get('mysql_password');

		if ($this->_socket) {
			$dsn = 'mysql:unix_socket='.$this->_socket.';dbname='.$this->_dbname;
		} else {
			$dsn = 'mysql:host='.$this->_host.';port='.$this->_port.';dbname='.$this->_dbname;
		}

		if ($this->_charset) {
			$dsn .= ';charset='.$charset;
		}

		try {
			$this->_pdo = new PDO($this->_dsn, $this->_username, $this->_password);
		} catch(PDOException $e) {
			throw new EdogerException($e->getMessage(), EDOGER_ERROR_CONNECT, $e);
		}
	}

	public function getHost()
	{
		return $this->_host;
	}

	public function getPort()
	{
		return $this->_port;
	}

	public function getSocket()
	{
		return $this->_socket;
	}

	public function getDbname()
	{
		return $this->_dbname;
	}

	public function getCharset()
	{
		return $this->_charset;
	}

	public function getUser()
	{
		return $this->_username;
	}

	public function getServerVersion()
	{
		return $this->_pdo->getAttribute(PDO::ATTR_SERVER_VERSION);
	}

	public function getClientVersion()
	{
		return $this->_pdo->getAttribute(PDO::ATTR_CLIENT_VERSION);
	}

	public function getConnected()
	{
		return $this->_pdo;
	}

	public function getErrorCode()
	{
		return $this->_errorCode;
	}

	public function getErrorMessage()
	{
		return $this->_errorMessage;
	}

	public function errorClean()
	{
		$this->_errorCode = 0;
		$this->_errorMessage = '';
		return $this;
	}
}