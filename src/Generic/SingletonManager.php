<?php

namespace PhpSync\Generic;

class SingletonManager implements SingletonManagerInterface
{
    protected $instances = [];

    public function get(string $key, string $className)
    {
        return $this->instances[$className][$key] ?? null;
    }

    public function set(string $key, string $className, $instance)
    {
        if (!isset($this->instances[$className])) {
            $this->instances[$className] = [];
        }

        $this->instances[$className][$key] = $instance;
    }

    public function remove(string $key, string $className)
    {
        unset($this->instances[$className][$key]);
    }

    public function has(string $key, string $className): bool
    {
        return isset($this->instances[$className][$key]);
    }
}