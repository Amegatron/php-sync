<?php

namespace PhpSync\Generic;

use Exception;
use PhpSync\Core\Exceptions\SyncOperationException;
use PhpSync\Core\IntegerInterface;
use Throwable;

/**
 * Class Integer
 *
 * Provides implementation for arbitrary Integers as described in IntegerInterface
 *
 * @package PhpSync
 */
class Integer implements IntegerInterface
{
    /** @var SingletonManagerInterface */
    private static $instanceManager;

    /** @var string */
    private $key;

    /**
     * Holds a KNOWN VALUE for this instance of an Integer as described in IntegerInterface
     *
     * @var int
     */
    private $knownValue;

    /**
     * @var IntegerSyncDriverInterface
     */
    private $driver;

    /**
     * Integer constructor. Private for Singleton.
     *
     * @param string $key
     * @param int $value
     */
    private function __construct(string $key, int $value)
    {
        $this->key = $key;
        $this->knownValue = $value;
    }

    /**
     * Singleton
     *
     * @throws Exception
     */
    private function __clone()
    {
        throw new Exception("Integer can not be cloned");
    }

    /**
     * Singleton
     *
     * @throws Exception
     */
    private function __wakeup()
    {
        throw new Exception("Integer can not be unserialized");
    }

    /**
     * Gets an instance of an Integer representing the corresponding Integer in the system.
     *
     * Either loads the ACTUAL VALUE of an existing Integer, or inits the KNOWN VALUE of this instance with 0
     * without persisting it.
     *
     * @param $key
     * @param IntegerSyncDriverInterface $driver
     * @param SingletonManagerInterface|null $singletonManager
     * @return Integer
     * @throws SyncOperationException
     */
    public static function getInstance($key, IntegerSyncDriverInterface $driver, ?SingletonManagerInterface $singletonManager = null)
    {
        // If SingleManager was not provided, use internal one for global Singleton management
        $manager = $singletonManager;
        if (!$manager) {
            if (!self::$instanceManager) {
                self::$instanceManager = new SingletonManager();
            }
            $manager = self::$instanceManager;
        }

        if ($manager->has($key, self::class)) {
            return $manager->get($key, self::class);
        } else {
            $value = 0;
            if ($driver->hasValue($key)) {
                try {
                    $value = $driver->getValue($key);
                } catch (Throwable $e) {
                    throw new SyncOperationException();
                }
            }
            $instance = new self($key, $value);
            $instance->driver = $driver;
            $manager->set($key, self::class, $instance);
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
    public function getValue(): int
    {
        return $this->knownValue;
    }

    /**
     * @inheritDoc
     */
    public function setValue(int $value)
    {
        try {
            $this->knownValue = $this->driver->setValue($this->getKey(), $value);
        } catch (Throwable $e) {
            throw new SyncOperationException($e->getMessage(), 0, $e);
        }
    }

    /**
     * @inheritDoc
     */
    public function increment($by = 1): int
    {
        try {
            $this->knownValue = $this->driver->increment($this->getKey(), $by);
            return $this->knownValue;
        } catch (Throwable $e) {
            throw new SyncOperationException($e->getMessage(), 0, $e);
        }
    }

    /**
     * @inheritDoc
     */
    public function decrement($by = 1): int
    {
        try {
            $this->knownValue = $this->driver->increment($this->getKey(), -$by);
            return $this->knownValue;
        } catch (Throwable $e) {
            throw new SyncOperationException($e->getMessage(), 0, $e);
        }
    }

    /**
     * @inheritDoc
     */
    public function refresh(): int
    {
        try {
            $this->knownValue = $this->driver->getValue($this->getKey());
            return $this->knownValue;
        } catch (Throwable $e) {
            throw new SyncOperationException($e->getMessage(), 0, $e);
        }
    }

    /**
     * @inheritdoc
     */
    public function delete()
    {
        try {
            $this->driver->delete($this->getKey());
        } catch (Throwable $e) {
            throw new SyncOperationException($e->getMessage(), 0, $e);
        }
    }

    /**
     * @inheritdoc
     */
    public function exists(): bool
    {
        return $this->driver->hasValue($this->getKey());
    }
}