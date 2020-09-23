<?php

use PhpSync\Generic\IntegerDoesNotExistException;

class InMemoryIntegerSyncDriver implements \PhpSync\Generic\IntegerSyncDriverInterface
{
    /** @var array */
    private $values = [];
    
    /**
     * @inheritDoc
     */
    public function setValue(string $key, int $value): int
    {
        $this->values[$key] = $value;
        return $value;
    }

    /**
     * @inheritDoc
     */
    public function getValue(string $key): int
    {
        if (isset($this->values[$key])) {
            return $this->values[$key];
        }

        throw new IntegerDoesNotExistException();
    }

    /**
     * @inheritDoc
     */
    public function increment(string $key, int $by): int
    {
        $value = $this->values[$key] ?? 0;
        $value += $by;
        $this->values[$key] = $value;
        return $value;
    }

    /**
     * @inheritDoc
     */
    public function hasValue(string $key): bool
    {
        return isset($this->values[$key]);
    }

    /**
     * @inheritDoc
     */
    public function delete(string $key)
    {
        unset($this->values[$key]);
    }
}