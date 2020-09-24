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
    /**
     * @param string $key
     * @see LockInterface::lock()
     */
    public function lock(string $key);

    /**
     * @param string $key
     * @return bool
     * @see LockInterface::unlock()
     */
    public function unlock(string $key): bool;

    /**
     * @param string $key
     * @return void
     * @see LockInterface::wait()
     */
    public function wait(string $key);

    /**
     * @param string $key
     * @return mixed
     * @see LockInterface::exists()
     */
    public function exists(string $key): bool;
}