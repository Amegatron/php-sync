<?php

namespace PhpSync\Generic;

/**
 * Class IntegerSyncDriverInterface
 *
 * Provides interface for implementing the underlying mechanisms for Sync-ed Integers.
 * Consider this as a Driver for persisting the state of an Integer
 *
 * @package PhpSync\Interfaces
 */
interface IntegerSyncDriverInterface
{
    /**
     * Writes a direct value of an Integer
     *
     * @param string $key
     * @param int $value
     * @return mixed
     */
    public function setValue(string $key, int $value);

    /**
     * Loads an ACTUAL VALUE of an Integer
     *
     * @param string $key
     * @return int
     */
    public function getValue(string $key): int;

    /**
     * Increments an ACTUAL VALUE of an Integer
     *
     * @param string $key
     * @param int $by
     * @return mixed
     */
    public function increment(string $key, int $by);

    /**
     * Checks if an Integer with specified key already exists
     *
     * @param string $key
     * @return bool
     */
    public function hasValue(string $key): bool;

    /**
     * Deletes Integer from underlying sync mechanism
     *
     * @param string $key
     * @return mixed
     */
    public function delete(string $key);
}