<?php
/*
 +-----------------------------------------------------------------------------+
 | Edoger PHP Framework (EdogerPHP)                                            |
 +-----------------------------------------------------------------------------+
 | Copyright (c) 2014 - 2016 QingShan Luo                                      |
 +-----------------------------------------------------------------------------+
 | The MIT License (MIT)                                                       |
 |                                                                             |
 | Permission is hereby granted, free of charge, to any person obtaining a     |
 | copy of this software and associated documentation files (the “Software”),  |
 | to deal in the Software without restriction, including without limitation   |
 | the rights to use, copy, modify, merge, publish, distribute, sublicense,    |
 | and/or sell copies of the Software, and to permit persons to whom the       |
 | Software is furnished to do so, subject to the following conditions:        |
 |                                                                             |
 | The above copyright notice and this permission notice shall be included in  |
 | all copies or substantial portions of the Software.                         |
 |                                                                             |
 | THE SOFTWARE IS PROVIDED “AS IS”, WITHOUT WARRANTY OF ANY KIND,             |
 | EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF          |
 | MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.      |
 | IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, |
 | DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR       |
 | OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE   |
 | USE OR OTHER DEALINGS IN THE SOFTWARE.                                      |
 +-----------------------------------------------------------------------------+
 |  License: MIT                                                               |
 +-----------------------------------------------------------------------------+
 |  Authors: QingShan Luo <shanshan.lqs@gmail.com>                             |
 +-----------------------------------------------------------------------------+
 */
namespace Edoger\Database\Mysql;


/**
 * 
 */
final class Mysql
{
	private static $connectedLoop = [];
	private static $defaultServerName = '';
	private static $currentServerName = '';
	private static $serverConfig = [];

	public static function handle(string $table, string $alias = '')
	{
		$table = self::parseTableName($table);

		return new Builder(self::getConnected(), $table, $alias);
	}

	/**
	 * -------------------------------------------------------------------------
	 * [connect description]
	 * -------------------------------------------------------------------------
	 * 
	 * @param  string $server [description]
	 * @return [type]         [description]
	 */
	public static function connect(string $server = '')
	{
		if (!isset(self::$connectedLoop[$server])) {
			if (empty(self::$serverConfig)) {
				self::$serverConfig = Config::get('mysql.server', []);
			}

			if (!isset(self::$serverConfig[$server])) {
				throw new ConnectException(
					'Undefined MySQL database server: ' . $server, 500
					);
			}

			self::$connectedLoop[$server] = new Connect(
				self::$serverConfig[$server]
				);
		}

		self::$currentServerName = $server;
		return true;
	}

	
	/**
	 * -------------------------------------------------------------------------
	 * [getConnected description]
	 * -------------------------------------------------------------------------
	 * 
	 * @param  string $server [description]
	 * @return [type]         [description]
	 */
	public static function getConnected(string $server  = '')
	{
		if ($server) {
			$server = strtolower($server);
		} else {
			if (self::$currentServerName) {
				$server = self::$currentServerName;
			} else {
				$server = self::getDefaultServerName();
			}
		}

		self::connect($server)

		return self::$connectedLoop[$server];
	}

	/**
	 * -------------------------------------------------------------------------
	 * [getCurrentServerName description]
	 * -------------------------------------------------------------------------
	 * 
	 * @return string
	 */
	public static function getCurrentServerName()
	{
		return self::$currentServerName;
	}

	/**
	 * -------------------------------------------------------------------------
	 * [getDefaultServerName description]
	 * -------------------------------------------------------------------------
	 * 
	 * @return string
	 */
	public static function getDefaultServerName()
	{
		if (!self::$defaultServerName) {
			self::$defaultServerName = Config::get('mysql.defaultServerName');
		}
		
		return self::$defaultServerName;
	}

	/**
	 * -------------------------------------------------------------------------
	 * [parseTableName description]
	 * -------------------------------------------------------------------------
	 * 
	 * @param  string $table [description]
	 * @return [type]        [description]
	 */
	private static function parseTableName(string $table)
	{

		return $table;
	}
}