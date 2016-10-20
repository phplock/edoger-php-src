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
	public function getConnected();
	public function getErrorCode();
	public function getErrorState();
	public function getErrorMessage();

	public function inTransaction();
	public function beginTransaction();
	public function commit();
	public function rollback();

	public function execute($statement, array $params = []);
	public function query($statement, array $params = []);

	public function getLastInsertId();
}