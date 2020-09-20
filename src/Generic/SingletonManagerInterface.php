<?php

namespace PhpSync\Generic;

/**
 * Interface SingletonManagerInterface
 *
 * Provides interface for managing key'd Singletons of different classes within application
 *
 * @package PhpSync\Singletons
 */
interface SingletonManagerInterface
{
    public function get(string $key, string $className);
    public function set(string $key, string $className, $instance);
    public function remove(string $key, string $className);
    public function has(string $key, string $className): bool;
}