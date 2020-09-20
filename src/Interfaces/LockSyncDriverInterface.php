<?php

namespace PhpSync\Interfaces;

/**
 * Interface LockSyncDriverInterface
 *
 * Provides an interface for implementing an underlying mechanisms for Locks.
 *
 * All methods correspond to the same ones of LockInterface
 *
 * @package PhpSync\Interfaces
 */
interface LockSyncDriverInterface
{
    public function lock(string $key);
    public function unlock(string $key);
    public function wait(string $key);
    public function exists(string $key);
}