<?php

namespace PhpSync\Generic;

use Exception;
use PhpSync\Core\Exceptions\SyncOperationException;
use PhpSync\Core\LockInterface;
use Throwable;

/**
 * Class Lock
 *
 * This class provides an implementation for arbitrary Locks
 *
 * TODO: provide more detailed description
 *
 * @package PhpSync
 */
class Lock implements LockInterface
{
    /**
     * @var string
     */
    private $key;

    /**
     * @var LockSyncDriverInterface
     */
    private $driver;

    /**
     * Lock constructor and other magic methods are made private to provide Singleton-like behaviour to this class.
     *
     * @param string $key
     */
    private function __construct(string $key)
    {
        $this->key = $key;
    }

    /**
     * Singleton
     *
     * @throws Exception
     */
    private function __clone()
    {
        throw new Exception("Locks can not be cloned");
    }

    /**
     * Singleton
     *
     * @throws Exception
     */
    private function __wakeup()
    {
        throw new Exception("Locks can not be unserialized");
    }

    /**
     * Gets an instance of Lock representing a real Lock in the system.
     *
     * @param string $key
     * @param SingletonManagerInterface|null $manager
     * @param LockSyncDriverInterface $driver
     * @return Lock
     */
    public static function getInstance(string $key, ?SingletonManagerInterface $manager, LockSyncDriverInterface $driver)
    {
        if ($manager && $manager->has($key, self::class)) {
            return $manager->get($key, self::class);
        } else {
            $instance = new self($key);
            $instance->driver = $driver;
            if ($manager) {
                $manager->set($key, self::class, $instance);
            }
            return $instance;
        }
    }

    /**
     * @inheritDoc
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * @inheritDoc
     */
    public function lock()
    {
        try {
            $this->driver->lock($this->getKey());
        } catch (Throwable $e) {
            throw new SyncOperationException($e->getMessage());
        }
    }

    /**
     * @inheritDoc
     */
    public function unlock(): bool
    {
        try {
            return $this->driver->unlock($this->getKey());
        } catch (Throwable $e) {
            throw new SyncOperationException($e->getMessage(), 0, $e);
        }
    }

    /**
     * @inheritDoc
     */
    public function wait()
    {
        $this->driver->wait($this->getKey());
    }

    /**
     * @inheritDoc
     */
    public function exists(): bool
    {
        return $this->driver->exists($this->getKey());
    }
}