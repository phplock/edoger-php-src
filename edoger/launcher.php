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
 *+------------------------------------------------------------------------------------------------+
 *| @author    Qingshan Luo <shanshan.lqs@gmail.com>                                               |
 *+------------------------------------------------------------------------------------------------+
 */
define('ROOT_PATH', dirname(str_replace('\\', '/', __DIR__)));

// Load automatic loader.
require ROOT_PATH . '/edoger/Loader/Autoloader.php';

Edoger\Loader\Autoloader::addRule('Edoger', ROOT_PATH . '/edoger');
Edoger\Loader\Autoloader::addRule('App', ROOT_PATH . '/application');
Edoger\Loader\Autoloader::register();

$app = new Edoger\Foundation\Application(

    // Application root directory.
    ROOT_PATH . '/application'
);

return $app;
