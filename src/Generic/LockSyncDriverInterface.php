<?php

namespace PhpSync\Generic;

/**
 * Interface LockSyncDriverInterface
 *
 * Provides an interface for implementing an underlying mechanisms for Locks.
 *
 * Consider interpreting these methods the same way as described in LockInterface
 *
 * @package PhpSync\Interfaces
 */
interface LockSyncDriverInterface
{
    public function lock(string $key);
    public function unlock(string $key): bool;
    public function wait(string $key);
    public function exists(string $key);
}