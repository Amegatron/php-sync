==PHP-SYNC==

This package provides a set of interfaces which should ease the process of synchronizing different PHP scripts
running in parallel.

=====Locks=====
LockInterface is meant to provide an interface which would allow the parallel process to wait for each other
or any other circumstances.

=====Integers=====
IntegerInterface provides a common interface to work atomically with integers within parallel scripts. A common
use case for this is tracking progress of background tasks.