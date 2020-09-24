## PHP-SYNC ##

This package provides a set of interfaces and generic implementations which should ease synchronization between different PHP scripts
running in parallel.

## Interfaces ##

In general any consuming side should depend on just two interfaces: `LockInterface` and `IntegerInterface` 

#### LockInterface ####
`LockInterface` is meant to provide an interface which would allow the parallel process to block or wait for each other
or any other circumstances. Consider taking the following example into account:

You have some scripts running in background (let's call them `workers`) which, for example, take new messages (tasks) 
from any MessageQueue engine and constantly start and finish with help of `supervisor`, for example. If you need to
temporarily pause them, you could introduce a lock to the system which all the workers would wait for before taking new
tasks. In general, this could look like this:

1. Before taking a new task, all workers `wait` for a `Lock` named, for example, `pause`:

```php
// $lock is an instance of LockInterface which is associated with a specific Lock called 'pause'
$lock->wait(); 
// further execution is blocked until the Lock is released
```
2. Wherever you would like to control those workers (from web admin-panel, for example), you could just `acquire`
      that same Lock to pause the workers:      
```php
// $lock is an instance of LockInterface which is associated with a specific Lock called 'pause'
$lock->lock();
``` 
And all the workers would pause, waiting until the lock is `released` from the controlling side:     
```php
$lock->release();
```

See the `Generic implementation` for more info.
      
#### IntegerInterface ####
`IntegerInterface` provides a common interface to work atomically with integers from parallel scripts. One of the most common
use case for that is tracking the progress of any of the background tasks, either for just decorational purposes (just to
show the progress to user), or even to implement some different logic for synchronizing those background tasks.

As an example for the first use-case, imagine you put N new tasks to the queue. You could also use an `IntegerInterface` for storing that amount. Let's call this Integer `total_tasks`. At the same time, you could also introduce a new Integer, called `progress` for example, which would ack as a counter. Upon completion of an individual task, each worker would `increment` that `progress`. And any time you need to display a total progress, you could just take those two integers and calculate the progress in percents and display it somehow.

Also see `Generic implementation` for more info.

#### Specification ####
See the source code for both of those interfaces under `/src/Core` path for a detailed specification about how exactly implementations of them must behave.

## Generic implementation ##
This package also provides a generic implementation of the interfaces mentioned above. This implementation does not work by
itself, meaning, it does not provide any exact implementation for making atomic Locks and Integers. Instead, it just
relies on underlying `Drivers` which do the actual job.

This implementation utilizes a Singleton pattern so that Locks and Integers which represent the same objects are
presented by a single object during the run of a script.

Imagine you want to get a Lock object which represents a lock named `some_lock`. In this case you would do the following:
```php
$driver = // get a specific driver somehow, see further
$lock = \PhpSync\Generic\Lock::getInstance('some_lock', $driver);
```
Later on, if you try to get in instance of Lock for the same `name` (or `key`) `some_lock` you'll get exactly the same instance:
```php
$lock2 = \PhpSync\Generic\Lock::getInstance('some_lock', $driver);
// $lock === $lock2
```
Singletone management by `key` is done internally inside the `Lock` class, but you can still provide your own `SingletonManagerInterface` as a third parameter in case you need to utilize some "namespacing" for your Locks. You could even provide a `new SingletonManager()` with each call in case you don't need any "singletoning" at all for some reasons.

Everything stated above is also actual for a generic `Integer` implementation.

#### Drivers ####
This generic implementation uses `Drivers` for actual realization of the main functionality. Specifically, there are two kinds of drivers: `LockSyncDriverInterface` and `IntegerSyncDriverInterface`, which provide corresponding interfaces for making some atomic actions. Those drivers are NOT shipped with package and MUST be installed additionally.

For example, consider looking at a neighbor package [amegatron/php-sync-fs](https://github.com/Amegatron/php-sync-fs), which provide drivers for implementing the functionality based on a local file system.