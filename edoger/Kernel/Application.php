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

use Edoger\Config\Config;

class Application extends Kernel
{
    const VERSION = '1.0.0';

    public function __construct(Config $config)
    {
        static::$application   = &$this;
        static::$configuration = $config;

        $this->instance(Application::class, $this);
        $this->instance(get_class($config), $config);
    }

    public function version()
    {
        return static::VERSION;
    }
}
