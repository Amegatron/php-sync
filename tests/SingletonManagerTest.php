<?php

use PhpSync\Generic\SingletonManager;
use PhpSync\Generic\SingletonManagerInterface;
use PHPUnit\Framework\TestCase;

class SingletonManagerTest extends TestCase
{
    /** @var SingletonManagerInterface */
    private SingletonManagerInterface $manager;
    private string $className;

    public function setUp(): void
    {
        $this->manager = new SingletonManager();
        $this->className = "ClassName";
    }

    public function testManagerReturnsSameInstance()
    {
        $obj = new stdClass();
        $key = $this->getRandomKey();
        $this->manager->set($key, $this->className, $obj);
        $testObj = $this->manager->get($key, $this->className);

        $this->assertSame($obj, $testObj);
    }

    public function testManagerTakesClassNameIntoAccount()
    {
        $obj = new stdClass();
        $key = $this->getRandomKey();
        $this->manager->set($key, $this->className, $obj);
        $this->assertFalse($this->manager->has($key, $this->className . "ButAnother"));
    }

    public function testManagerReturnsNullForNonExistentInstance()
    {
        $this->assertNull($this->manager->get($this->getRandomKey(), $this->className));
    }

    public function testManagerHasInstance()
    {
        $obj = new stdClass();
        $key = $this->getRandomKey();
        $this->manager->set($key, $this->className, $obj);
        $this->assertTrue($this->manager->has($key, $this->className));
        $key2 = $this->getRandomKey();
        while ($key == $key2) {
            $key2 = $this->getRandomKey();
        }
        $this->assertFalse($this->manager->has($key2, $this->className));
    }

    public function testManagerRemovesInstance()
    {
        $obj = new stdClass();
        $key = $this->getRandomKey();
        $this->manager->set($key, $this->className, $obj);
        $this->assertTrue($this->manager->has($key, $this->className));
        $this->manager->remove($key, $this->className);
        $this->assertFalse($this->manager->has($key, $this->className));
    }

    private function getRandomKey()
    {
        return md5(mt_rand(0, 10000));
    }
}
