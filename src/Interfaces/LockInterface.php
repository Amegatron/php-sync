<?php

namespace PhpSync\Interfaces;

use PhpSync\Exceptions\SyncOperationException;

/**
 * Interface LockInterface
 *
 * Defines an interface for locking parallel processes.
 *
 * Capitalized words are to be interpreted as described in RFC2119.
 * When it is said that a lock exists, it is meant the same as the lock is acquired or just locked.
 * Releasing the lock means the same as unlocking it.
 *
 * @package PhpSync
 * @see https://www.ietf.org/rfc/rfc2119.txt
 */
interface LockInterface
{
    /**
     * Returns a key which uniquely identifies this lock among others in the same space
     *
     * @return string
     */
    public function getKey(): string;

    /**
     * Acquires a lock.
     *
     * This method MUST be blocking, meaning if a corresponding Lock already exists, it blocks further execution
     * until the lock is released and then acquires it itself.
     *
     * If there is no such lock yet - just acquires it and continues execution.
     *
     * If it is impossible to acquire a lock for technical reasons (failure of underlying services), though it is not
     * yet acquired by somebody else, the method MUST throw a SyncOperationException.
     *
     * @return void
     * @throws SyncOperationException
     */
    public function lock();

    /**
     * Releases the lock.
     *
     * This method MUST release the lock no matter if it was acquired by another process.
     * This is to prevent ghost locks, though the logic to determine them lies beyond this interface.
     *
     * In case a lock could not be released cause of technical reasons (including situations when the underlying
     * locking mechanism does not allow unlocking from anyone other than the initiator), this method MUST throw
     * SyncOperationException.
     *
     * If an existing Lock was released, the method returns true. If there ws no such Lock - returns false.
     *
     * @return bool
     * @throws SyncOperationException
     */
    public function unlock(): bool;

    /**
     * Waits until the corresponding Lock is released.
     *
     * The method MUST be blocking, meaning it blocks further execution until the Lock is released, or if there is
     * already no such Lock. Implementations of this method SHOULD use blocking methods internally. Otherwise, an
     * infinite loop with micro-sleeps checking the existence of a Lock is allowed. In the latter case such
     * implementations SHOULD provide additional interface for adjusting the sleep intervals.
     *
     * Also, implementations of this interface MAY provide additional methods for waiting with timeout. Otherwise, such
     * functionality may be implemented by the consuming side itself as described above.
     *
     * @return void
     * @see exists()
     */
    public function wait();

    /**
     * Checks if corresponding lock is acquired.
     *
     * If a corresponding lock exists, it MUST return TRUE no matter if the lock is still actual or ghost.
     * Returns FALSE otherwise.
     *
     * @return bool
     */
    public function exists(): bool;
}