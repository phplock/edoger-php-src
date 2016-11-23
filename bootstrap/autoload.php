<?php
/**
 *+------------------------------------------------------------------------------------------------+
 *| Edoger PHP Framework                                                                           |
 *+------------------------------------------------------------------------------------------------+
 *| A simple and efficient PHP framework.                                                          |
 *+------------------------------------------------------------------------------------------------+
 *| @license   MIT                                                                                 |
 *| @link      https://www.edoger.com/                                                             |
 *| @copyright Copyright (c) 2014 - 2016, QingShan Luo                                             |
 *+------------------------------------------------------------------------------------------------+
 *| @author    Qingshan Luo <shanshan.lqs@gmail.com>                                               |
 *+------------------------------------------------------------------------------------------------+
 */

// ----------------------------------------------
// Use the built-in automatic loader.
// You can change it completely.
require __DIR__ . '/../edoger/Loader/Autoloader.php';

// ----------------------------------------------
// Add the 'Edoger' and 'App' namespace loading rule.
Edoger\Loader\Autoloader::addRule('Edoger', realpath(__DIR__ . '/../edoger'));
Edoger\Loader\Autoloader::addRule('App', realpath(__DIR__ . '/../application'));

// ----------------------------------------------
// Registered loader.
Edoger\Loader\Autoloader::register();
