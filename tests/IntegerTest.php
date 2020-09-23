<?php

require_once __DIR__ . "/InMemoryIntegerSyncDriver.php";

use PhpSync\Generic\Integer;
use PhpSync\Generic\IntegerSyncDriverInterface;
use PhpSync\Generic\SingletonManager;
use PhpSync\Generic\SingletonManagerInterface;
use PHPUnit\Framework\TestCase;

class IntegerTest extends TestCase
{
    /** @var SingletonManagerInterface */
    private $manager;

    /** @var IntegerSyncDriverInterface */
    private $inMemoryIntegerSyncDriver;

    public function setUp(): void
    {
        $this->inMemoryIntegerSyncDriver = new InMemoryIntegerSyncDriver();
        $this->manager = new SingletonManager();
    }

    public function testIntegerHasDefaultValueZeroForNonExistentInteger()
    {
        $key = $this->getRandomKey();
        $integer = Integer::getInstance($key, $this->manager, $this->inMemoryIntegerSyncDriver);
        $this->assertEquals(0, $integer->getValue());
        return $integer;
    }

    public function testIntegerIncrementsValue()
    {
        $key = $this->getRandomKey();
        $integer = Integer::getInstance($key, $this->manager, $this->inMemoryIntegerSyncDriver);
        $by = mt_rand(1, 1000000);
        $integer->increment($by);
        $this->assertEquals($by, $integer->getValue());
    }

    public function testIntegerDecrementsValue()
    {
        $key = $this->getRandomKey();
        $integer = Integer::getInstance($key, $this->manager, $this->inMemoryIntegerSyncDriver);
        $by = mt_rand(1, 1000000);
        $integer->decrement($by);
        $this->assertEquals(-$by, $integer->getValue());
    }

    public function testIntegerCanIncrementByNegativeNumber()
    {
        $key = $this->getRandomKey();
        $integer = Integer::getInstance($key, $this->manager, $this->inMemoryIntegerSyncDriver);
        $by = mt_rand(1, 1000000);
        $by *= -1;
        $integer->increment($by);
        $this->assertEquals($by, $integer->getValue());
    }

    public function testIntegerCanDecrementByNegativeNumber()
    {
        $key = $this->getRandomKey();
        $integer = Integer::getInstance($key, $this->manager, $this->inMemoryIntegerSyncDriver);
        $by = mt_rand(1, 1000000);
        $by *= -1;
        $integer->decrement($by);
        $this->assertEquals(-$by, $integer->getValue());
    }

    public function testIntegerGetsSameInstance()
    {
        $key = $this->getRandomKey();
        $integer1 = Integer::getInstance($key, $this->manager, $this->inMemoryIntegerSyncDriver);
        $integer2 = Integer::getInstance($key, $this->manager, $this->inMemoryIntegerSyncDriver);
        $this->assertSame($integer1, $integer2);
    }

    public function testDifferentInstancesWorkWithSameInteger()
    {
        $manager2 = new SingletonManager();
        $key = $this->getRandomKey();
        $integer1 = Integer::getInstance($key, $this->manager, $this->inMemoryIntegerSyncDriver);
        $integer2 = Integer::getInstance($key, $manager2, $this->inMemoryIntegerSyncDriver);
        $by1 = mt_rand(1, 1000000);
        $integer1->increment($by1);
        $by2 = mt_rand(1, 1000000);
        $integer2->increment($by2);

        $this->assertEquals($by1 + $by2, $integer2->getValue());
        $integer1->refresh();
        $this->assertEquals($by1 + $by2, $integer1->getValue());
    }

    public function testIncrementByZeroRefreshesValue()
    {
        $manager2 = new SingletonManager();
        $key = $this->getRandomKey();
        $integer1 = Integer::getInstance($key, $this->manager, $this->inMemoryIntegerSyncDriver);
        $integer2 = Integer::getInstance($key, $manager2, $this->inMemoryIntegerSyncDriver);
        $by1 = mt_rand(1, 1000000);
        $integer1->increment($by1);
        $integer2->increment(0);

        $this->assertEquals($by1, $integer2->getValue());
    }

    private function getRandomKey()
    {
        return md5(mt_rand(0, 10000));
    }
}
