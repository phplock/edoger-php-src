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

interface DriverInterface
{
	public function getHost();
	public function getPort();
	public function getSocket();
	public function getDbname();
	public function getCharset();
	public function getUser();
	public function getServerVersion();
	public function getClientVersion();
	public function getConnected();
	public function getErrorCode();
	public function getErrorMessage();
	public function errorClean();

	public function inTransaction();
	public function beginTransaction();
	public function sendTransaction();
	public function stopTransaction();

	public function execute($statement, array $params = []);
}