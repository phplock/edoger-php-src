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

use mysqli;
use Edoger\Core\Kernel;
use Edoger\Exception\EdogerException;

class MysqliDriver implements DriverInterface
{
	private $_mysqli = null;
	private $_host;
	private $_port;
	private $_socket;
	private $_dbname;
	private $_charset;
	private $_username;
	private $_password;

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
			$this->_host = '';
			$this->_port = 0;
		}

		$this->_mysqli = new mysqli(
			$this->_host,
			$this->_username,
			$this->_password,
			$this->_dbname,
			$this->_port,
			$this->_socket
			);

		if ($this->_mysqli->connect_errno) {
			throw new EdogerException($this->_mysqli->connect_error, EDOGER_ERROR_CONNECT);
		}

		if ($this->_charset) {
			$this->_mysqli->set_charset($this->_charset);
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
		return $this->_mysqli->server_info;
	}

	public function getClientVersion()
	{
		return $this->_mysqli->get_client_info();
	}

	public function getConnected()
	{
		return $this->_mysqli;
	}

	public function getErrorCode()
	{
		return $this->_mysqli->errno;
	}

	public function getErrorState()
	{
		return $this->_mysqli->sqlstate;
	}

	public function getErrorMessage()
	{
		return $this->_mysqli->error;
	}

	public function inTransaction()
	{
		$reslut = $this->_mysqli->query('SELECT @@autocommit AS inTransaction');
		if ($reslut) {
			$row = $reslut->fetch_row();
			return !$row['inTransaction'];
		} else {
			return false;
		}
	}

	public function beginTransaction()
	{
		return $this->_mysqli->autocommit(false);
	}

	public function commit()
	{
		return $this->_mysqli->commit();
	}

	public function rollback()
	{
		return $this->_mysqli->rollback();
	}

	public function execute($statement, array $params = [])
	{

	}

	public function query($statement, array $params = [])
	{
		
	}

	public function getLastInsertId()
	{
		return $this->_mysqli->insert_id;
	}
}