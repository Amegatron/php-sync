<?php

namespace PhpSync\Generic;

use PhpSync\Core\Exceptions\SyncOperationException;

/**
 * Class IntegerSyncDriverInterface
 *
 * Provides interface for implementing the underlying mechanisms for Sync-ed Integers.
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
     * @return int
     * @throws SyncOperationException
     */
    public function setValue(string $key, int $value): int;

    /**
     * Loads an ACTUAL VALUE of an Integer
     *
     * @param string $key
     * @return int
     * @throws IntegerDoesNotExistException
     */
    public function getValue(string $key): int;

    /**
     * Increments an ACTUAL VALUE of an Integer
     *
     * @param string $key
     * @param int $by
     * @return mixed
     * @throws SyncOperationException
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
     * @throws SyncOperationException
     */
    public function delete(string $key);
}