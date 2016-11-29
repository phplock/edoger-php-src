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

use App\Bootstrap;
use Edoger\Config\Config;
use Edoger\Exceptions\RuntimeException;
use Edoger\Http\Response;

class Application extends Kernel
{
    const VERSION = '1.0.0';

    protected static $booted = false;

    public function __construct(Config $config)
    {
        static::$application   = &$this;
        static::$configuration = $config;
    }

    public function singleton($abstract, $alias)
    {
        $alias = strtolower($alias);
        $name  = ltrim($abstract, '\\');
        if (isset(static::$instances[$name])) {
            return $this->alias($alias, $name)->get($alias);
        } else {
            return $this->alias($alias, $name)->set($name, new $abstract($this));
        }
    }

    public function bootstrap()
    {
        if (static::$booted) {
            return $this;
        }

        static::$booted = true;

        $methods = get_class_methods(Bootstrap::class);
        if (!empty($methods)) {
            $bootstrap = new Bootstrap();
            foreach ($methods as $method) {
                if (substr($method, 0, 4) === 'init') {
                    $bootstrap->$method($this);
                }
            }
        }

        return $this;
    }

    public function capture($abstract, $dependent)
    {
        $object = new $abstract($this);
        if ($object instanceof Response) {
            $object->collect($dependent);
        } else {
            throw new RuntimeException(
                'The Application capture targets must be integrated with the Response class.'
            );
        }

        return $object;
    }

    public static function version()
    {
        return static::VERSION;
    }
}
