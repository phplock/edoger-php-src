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

use Edoger\Exceptions\RuntimeException;

class Kernel
{
    protected static $application;
    protected static $configuration;
    protected static $instances = [];
    protected static $aliases   = [];
    protected static $helpers   = [];

    protected function has($abstract)
    {
        return isset(static::$aliases[$abstract]);
    }

    protected function get($abstract)
    {
        if ($this->has($abstract)) {
            return static::$instances[
                static::$aliases[
                    $abstract
                ]
            ];
        } else {
            throw new RuntimeException(
                "The kernel extension instance {$abstract} does not exist or is not loaded."
            );
        }
    }

    protected function set($abstract, $instance)
    {
        static::$instances[$abstract] = $instance;
        return $instance;
    }

    protected function alias($alias, $abstract)
    {
        static::$aliases[$alias] = $abstract;
        return $this;
    }

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
        } else {
            throw new RuntimeException(
                "The kernel helper library {$helper} does not exist."
            );
        }
    }

    public function __get($extension)
    {
        return $this->get(strtolower($extension));
    }

    public function __isset($extension)
    {
        return $this->has(strtolower($extension));
    }
}
