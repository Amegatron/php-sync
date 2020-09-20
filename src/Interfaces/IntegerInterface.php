<?php

namespace PhpSync\Interfaces;

use PhpSync\Exceptions\SyncOperationException;

/**
 * Interface CounterInterface
 *
 * Defines an interface for working with integers in parallel processes.
 *
 * Capitalized words are to be interpreted as described in RFC2119 with addition of these terms:
 *
 *      Integer (with capitalized I) means an entity representing an integer number which parallel processes work with.
 *
 *      ACTUAL VALUE - A real value of an Integer shared across different processes.
 *          At any given moment in time there can be only one ACTUAL VALUE.
 *      KNOWN VALUE - A value of an Integer known by an individual process/instance of this interface.
 *          It MAY be always equal to an ACTUAL VALUE, but does not necessarily have to.
 *          When using any implementation of IntegerInterface it is RECOMMENDED to assume
 *          they are not equal if it is crucial to know the ACTUAL VALUE of an Integer.
 *
 *      If a word "value" in relation to Integer is used by itself, it is either not important to distinguish between
 *      ACTUAL and KNOWN values, or the meaning is obvious from the context. Otherwise an ACTUAL VALUE is meant.
 *
 *      "Ghost" means a Lock which still exists but is no longer relevant for the consuming application and was not
 *      released by the initiator for whatever reasons, for example a crash.
 *
 * Every method which is meant to modify the ACTUAL VALUE of an Integer MUST be atomic, meaning that all subsequent
 * operations concerning the ACTUAL VALUE, including parallel processes, MUST work with the new ACTUAL VALUE.
 *
 * Every method which is meant to modify the ACTUAL VALUE of an Integer MUST update the KNOWN VALUE of it's instance
 * to a new ACTUAL VALUE got as a result of directly this operation. @see increment() for description and examples.
 *
 * If not stated otherwise, any operation which is meant to modify or read an ACTUAL VALUE MUST throw SyncOperationException
 * provided by this package if it fails due to technical reasons.
 *
 * When it is said that a specific Exception MUST/SHOULD/MAY be thrown, it MAY be inheritor of the mentioned Exception of any level.
 *
 * @package PhpSync
 * @see https://www.ietf.org/rfc/rfc2119.txt
 */
interface IntegerInterface
{
    /**
     * Gets a key of this Integer which uniquely identifies it among others in the same space
     *
     * @return string
     */
    public function getKey(): string;

    /**
     * Gets a KNOWN VALUE of this Integer.
     *
     * This method SHOULD NOT perform a refresh operation. Use refresh() method separately to get an ACTUAL VALUE.
     *
     * @return int
     * @see refresh
     */
    public function getValue(): int;

    /**
     * This method forcibly sets a new ACTUAL VALUE of this Integer.
     *
     * It is RECOMMENDED to use this method only for setting an initial value of an Integer.
     *
     * @param int $value
     * @return mixed
     * @throws SyncOperationException
     */
    public function setValue(int $value);

    /**
     * Increments the ACTUAL VALUE by a specified amount and returns the new value.
     *
     * This increment MUST be atomic, meaning that after any N amount of subsequent increment attempts by 1
     * an Integer MUST have a final ACTUAL VALUE increased by N, assuming there were no intermediate decrements.
     *
     * This method MUST return a direct result of exactly this increment operation, not taking into
     * account any parallel operations which could take place in the small period of time after the increment itself
     * and the actual return from the function.
     *
     * This method MUST be capable to work with negative numbers, making it an other way to decrement an Integer.
     *
     * If 0 was passed as an argument, this method MUST still refresh the KNOWN VALUE, as if it truly tried to
     * increment.
     *
     * Example #1
     * A specific Integer currently has an ACTUAL VALUE of 0. Any amount of N concurrent increments of
     * this Integer by 1 (without intermediate decrements) MUST result in the final ACTUAL VALUE of an Integer being N,
     * and each of the method calls MUST return a unique value in the [1, N] range without any duplicates.
     *
     * Example #2
     *      1) current ACTUAL and KNOWN values are 0
     *      2) increment by 1 was invoked, which changes the ACTUAL VALUE value to 1. KNOWN VALUE also becomes 1
     *      3) ... some small duration of time has passed before return,
     *         during which a parallel request has also incremented
     *         this integer by 1, changing the ACTUAL VALUE to 2 ...
     *      4) in this case this increment operation MUST still return 1 as it is a KNOWN VALUE for this instance
     *         and a direct result of this operation.
     *
     * Example #3:
     *      1) current KNOWN VALUE is 0
     *      2) ... some parallel request(s) have changed the ACTUAL VALUE to 10 ...
     *      3) this instance increments the value by 1, changing the ACTUAL VALUE
     *         to 11 due to atomicity. 11 also becomes a KNOWN VALUE for this instance.
     *      4) this method MUST return 11 as a result of directly this increment operation
     *
     * @param int $by
     * @return int
     * @throws SyncOperationException
     * @see decrement
     */
    public function increment($by = 1): int;

    /**
     * Decrements the ACTUAL VALUE of an Integer by specified amount and returns the new value.
     *
     * Same rules apply as for increment.
     *
     * @param int $by
     * @return int
     * @throws SyncOperationException
     * @see increment
     */
    public function decrement($by = 1): int;

    /**
     * This method refreshes the KNOWN VALUE, making it equal to an ACTUAL VALUE.
     *
     * This is for cases when an Integer has been changed by a parallel process and it is crucial for consumer of this
     * instance to know the ACTUAL VALUE.
     *
     * This method MUST return the ACTUAL VALUE of an Integer
     *
     * @return int
     * @throws SyncOperationException
     * @see getValue
     */
    public function refresh(): int;

    /**
     * This method deletes the Integer
     *
     * @return mixed
     * @throws SyncOperationException
     */
    public function delete();
}