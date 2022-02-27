<?php

namespace App\Util;

trait PropertyTrait
{
    protected static $_properties = [];
    protected static $properties = [];

    public function __construct(array $data = [])
    {
        foreach ($data as $key => $value) {
            $this->setProperties($key, $value);
        }
    }

    public function __get(string $name)
    {
        if ($descriptor = static::getPropertyDescriptor($name)) {
            $get = $descriptor["get"];

            if (is_string($get)) {
                $get = [$this, $get];
            } elseif ($get instanceof \Closure) {
                $get = $get->bindTo($this, $this);
            }

            return call_user_func($get);
        } else {
            trigger_error(sprintf('Undefined property: %s::$%s', __CLASS__, $name), E_USER_NOTICE);
        }
    }

    public function setProperties(string $name, $value): void
    {
        if ($descriptor = static::getPropertyDescriptor($name)) {
            $set = $descriptor["set"];

            if (is_string($set)) {
                $set = [$this, $set];
            } elseif ($set instanceof \Closure) {
                $set = $set->bindTo($this, $this);
            }

            if (is_callable($set)) {
                call_user_func($set, $value);
            } elseif ($set === true) {
                $this->$name = $value;
            }
        } else {
            $this->$name = $value;
        }
    }

    public function __clone()
    {
        foreach (static::$_properties as $name => $value) {
            $this->$name = $this->__get($name);
        }
    }

    public function __isset(string $name)
    {
        return isset(static::$_properties[$name]);
    }

    public static function getProperties($object): array
    {
        $properties = get_object_vars($object);

        foreach (array_diff_key(static::$_properties, $properties) as $name => $value) {
            $properties[$name] = $object->$name;
        }

        if (isset(static::$properties)) {
            foreach (array_diff_key(static::$properties, $properties) as $name => $value) {
                $properties[$name] = $object->$name;
            }
        }

        return $properties;
    }

    public static function defineProperty(string $name, $get, $set = null): array
    {
        $descriptor = is_array($get) ? $get : compact("get", "set");

        if (isset($descriptor[0])) {
            $descriptor["get"] = $descriptor[0];
        }

        if (isset($descriptor[1])) {
            $descriptor["set"] = $descriptor[1];
        }

        unset($descriptor[0], $descriptor[1]);

        return static::$_properties[$name] = $descriptor;
    }

    protected static function getPropertyDescriptor(string $name)
    {
        if (isset(static::$_properties[$name])) {
            return static::$_properties[$name];
        }

        if (isset(static::$properties, static::$properties[$name])) {
            return static::defineProperty($name, static::$properties[$name]);
        }
    }

    public function toArray(array $data = [], array $ignore = []): array
    {
        foreach (static::getProperties($this) as $name => $value) {
            if (isset($data[$name])) {
                continue;
            }
            $data[$name] = $value;
        }

        return array_diff_key($data, array_flip($ignore));
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}