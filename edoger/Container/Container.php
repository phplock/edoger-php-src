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
namespace Edoger\Container;

use ReflectionClass;

class Container
{
    protected static $instances = []; // class --> object
    protected static $aliases   = []; // string --> class
    protected static $shared    = []; // interface/calss -> class
    protected static $mapping   = []; // interface --> class

    public function make($abstract, array $arguments = [])
    {
        if (isset(static::$shared[$abstract])) {
            return static::$instances[static::$shared[$abstract]];
        }

        $reflection = new ReflectionClass($abstract);
        if (!$reflection->isInstantiable()) {
            if (isset(static::$mapping[$abstract])) {
                return $this->make(static::$mapping[$abstract]);
            }

            throw new BuildClassException(
                "Class {$class} is not instantiable"
            );
        }

        $constructor = $reflection->getConstructor();
        if (is_null($constructor)) {
            return new $abstract;
        }

        $parameters = $constructor->getParameters();
        if (empty($parameters)) {
            return new $abstract();
        }

        $dependent = [];
        foreach ($parameters as $parameter) {
            $abstract = $parameter->getClass();
            if (is_null($abstract)) {
                if (isset($arguments[$parameter->name])) {
                    $dependent[] = $arguments[$parameter->name];
                } elseif ($parameter->isOptional()) {
                    $dependent[] = $parameter->getDefaultValue();
                } else {
                    throw new BuildClassException(
                        "Unable to create an instance of {$target}, missing parameter {$parameter->name}"
                    );
                }
            } else {
                if (isset(static::$instances[$abstract])) {
                    $dependent[] = static::$instances[$abstract];
                } else {
                    try {
                        $instance    = $this->make($abstract, $arguments);
                        $dependent[] = $instance;
                    } catch (BuildClassException $exception) {
                        if ($parameter->isOptional()) {
                            $dependent[] = $parameter->getDefaultValue();
                        } else {
                            throw $exception;
                        }
                    }
                }
            }
        }

        return $reflection->newInstanceArgs($dependent);
    }

    public function singleton($abstract, $implementor = null)
    {
        if (is_null($implementor)) {
            if (isset(static::$mapping[$abstract])) {
                $implementor = static::$mapping[$abstract];

                static::$shared[$abstract]    = $implementor;
                static::$shared[$implementor] = $implementor;
                if (!isset(static::$instances[$implementor])) {
                    static::$instances[$implementor] = $this->make($implementor);
                }
            } else {
                static::$shared[$abstract] = $abstract;
                if (!isset(static::$instances[$abstract])) {
                    static::$instances[$abstract] = $this->make($abstract);
                }
            }
        } else {
            static::$mapping[$abstract]   = $implementor;
            static::$shared[$abstract]    = $implementor;
            static::$shared[$implementor] = $implementor;
            if (!isset(static::$instances[$implementor])) {
                static::$instances[$implementor] = $this->make($implementor);
            }
        }

        return $this;
    }

    public function instance($abstract, $instance)
    {
        if (isset(static::$mapping[$abstract])) {
            $implementor = static::$mapping[$abstract];
            if (isset($this->instances[$implementor])) {
                if (!isset(static::$shared[$abstract])) {
                    $this->instances[$abstract] = $instance;
                }
            } else {
                $this->instances[$implementor] = $instance;
            }
        } else {

        }

    }
}
