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
namespace Edoger\Loader;

/**
 * Edoger automatic loader.
 * Automatically load class files with the PSR-4 specification.
 */
class Autoloader
{
    protected static $rules      = [];
    protected static $missed     = [];
    protected static $registered = false;

    public static function loader($class)
    {
        if (isset(self::$missed[$class])) {
            return;
        }

        foreach (self::$rules as $rule) {
            if (preg_match($rule[0], $class, $match)) {
                $file = $rule[1] . $match[1] . '.php';
                if (file_exists($file)) {
                    require $file;
                    return;
                }
            }
        }

        self::$missed[$class] = true;
    }

    public static function addRule($namespace, $rootpath)
    {
        $rootpath  = str_replace('\\', '/', $rootpath);
        $namespace = trim($namespace, '\\') . '\\';

        if (substr($rootpath, -1) !== '/') {
            $rootpath = $rootpath . '/';
        }

        self::$rules[] = ['/^' . preg_quote($namespace) . '(.*)/', $rootpath];
    }

    public static function register()
    {
        if (!self::$registered) {
            self::$registered = spl_autoload_register(
                [
                    __CLASS__,
                    'loader',
                ],
                true,
                true
            );
        }

        return self::$registered;
    }
}
