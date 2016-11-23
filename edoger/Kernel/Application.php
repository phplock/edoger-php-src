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

use Edoger\Foundation\Config\ConfigInterface;
use Edoger\Foundation\Http\Application as Kernel;
use Edoger\Foundation\Kernel\ApplicationInterface;

class Application extends Kernel implements ApplicationInterface
{

    public function __construct(ConfigInterface $config)
    {
        static::$application   = &$this;
        static::$configuration = $config;
    }
}
