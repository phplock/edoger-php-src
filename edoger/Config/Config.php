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
namespace Edoger\Config;

class Config
{
    protected $items;

    public function __construct(array $items)
    {
        $this->items = $items;
    }

    public function get($key, $default = null)
    {
        if (isset($this->items[$key])) {
            return $this->items[$key];
        } else {
            return $default;
        }
    }

    public function set($key, $value, $cover = true)
    {
        if ($cover || !isset($this->items[$key])) {
            $this->items[$key] = $value;
            return true;
        } else {
            return false;
        }
    }

    public function has($key)
    {
        return isset($this->items[$key]);
    }

    public function all()
    {
        return $this->items;
    }
}
