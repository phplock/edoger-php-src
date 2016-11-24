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
namespace Edoger\Kernel;

use Edoger\Container\Container;
use Edoger\Exceptions\RuntimeException;

class Application extends Container
{
    protected static $application;
    protected static $configuration;

    protected static $helpers = [];

    public static function app()
    {
        return static::$application;
    }

    public static function config()
    {
        return static::$configuration;
    }

    public function helper($helper)
    {
        $helper = strtolower($helper);
        if (isset(static::$helpers[$helper])) {
            return true;
        }

        $file = ROOT_DIR . '/edoger/Helpers/' . $helper . '.php';
        if (file_exists($file)) {
            static::$helpers[$helper] = true;
            require $file;
            return true;
        }

        throw new RuntimeException(
            "Helper library {$helper} does not exist."
        );
    }
}
