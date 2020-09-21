<?php

namespace PhpSync\Generic;

use PhpSync\Core\LockInterface;

/**
 * Interface LockSyncDriverInterface
 *
 * Provides an interface for implementing an underlying mechanisms for Locks.
 *
 * Meaning of all the methods is exactly the same as in LockInterface
 *
 * @package PhpSync\Interfaces
 * @see LockInterface
 */
interface LockSyncDriverInterface
{
    public function lock(string $key);
    public function unlock(string $key): bool;
    public function wait(string $key);
    public function exists(string $key);
}