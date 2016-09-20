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
$attr = [
	PDO::ATTR_AUTOCOMMIT,
	PDO::ATTR_CASE,
	PDO::ATTR_CLIENT_VERSION,
	PDO::ATTR_CONNECTION_STATUS,
	PDO::ATTR_DRIVER_NAME,
	PDO::ATTR_ERRMODE,
	PDO::ATTR_ORACLE_NULLS,
	PDO::ATTR_PERSISTENT,
	// PDO::ATTR_PREFETCH,
	PDO::ATTR_SERVER_INFO,
	PDO::ATTR_SERVER_VERSION,
	// PDO::ATTR_TIMEOUT
];

$pdo = new PDO('mysql:host=127.0.0.1;dbname=test;port=3306;charset=utf8', 'dev', '123456');

// echo $pdo->getAttribute(PDO::ATTR_SERVER_INFO);
// echo $pdo ->quote(':a');
$sql = "INSERT INTO users (name,age) VALUES ('Ahuas', 12)";
$row = $pdo -> exec($sql);
$id = $pdo->lastInsertId('name');
var_dump($row);
var_dump($id);

die;
/**
 *+----------------------------------------------------------------------------+
 *| 启动框架核心                                                               |
 *+----------------------------------------------------------------------------+
 */
$kernel = require __DIR__ . '/../Edoger/launcher.php';

/**
 *+----------------------------------------------------------------------------+
 *| 创建应用服务                                                               |
 *+----------------------------------------------------------------------------+
 */
$service = require __DIR__ . '/../Application/bootstrap.php';

/**
 *+----------------------------------------------------------------------------+
 *| 绑定服务运行环境                                                           |
 *+----------------------------------------------------------------------------+
 */
$service -> make($kernel);

/**
 *+----------------------------------------------------------------------------+
 *| 启动服务                                                                   |
 *+----------------------------------------------------------------------------+
 */
$service -> start();

/**
 *+----------------------------------------------------------------------------+
 *| 输出框架捕获到的服务输出                                                   |
 *+----------------------------------------------------------------------------+
 */
$kernel -> flush();
