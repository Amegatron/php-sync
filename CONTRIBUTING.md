# CONTRIBUTING to PHP-SYNC

In context of contributing please keep in mind that there separate layers of the project:

#### Interface layer

This is a top layer and provides just the interfaces for synchronizing parallel tasks. You can also
consider it as a contract, or protocol, which strictly defines the rules. This layer is meant to be
the most stable part and ideally never change, provided that it suits all the needs when speaking about
synchronization. Keep in mind though, that it is an early version now and further adjustments can
certainly take place. Currently, everything under `\PhpSync\Core\` namespace defines this layer.

Contributions to this part may include discussions about what feature should or must be inside these
interfaces.

#### Generic implementation

This layer is just to provide a generic working implementation of the Interface Layer
mentioned above. Currently, everything inside the `\PhpSync\Generic\` namespace define this layer.
Any bugs or improvements to this part of the project are separate from the interface layer.

#### Drivers

Drivers are the "working units" specifically of the Generic Implementation described above.
They are completely separate from the Interface Layer and are meant only to provide concrete 
functionality for the Generic layer. Each driver (or set of drivers) should be a
separate package from the one you are currently in. Moreover, it is recommended that each driver lies
inside a separate namespace like `\PhpSync\Driver\<name_here>\`. Have a look at [amegatron\php-sync-fs](https://github.com/Amegatron/php-sync-fs)
package as an example of a specific driver for a Generic layer, based on local file system.

## Contributing

Assuming generic implementation is good enough for you, I encourage everybody to extend the possibilities
of it by implementing and publishing different kinds of Drivers which could involve any underlying
technology to provide the needed functionality, atomicity mainly. Otherwise, you are free to make
your own implementations of the Interface Layer capable of whatever you find useful.

When publishing new drivers, it is highly recommended that you cover them with tests.
For some cases multi-threaded testing is necessary to check locking mechanisms and/or atomicity of 
your implementation. You can have a look at the mentioned [amegatron\php-sync-fs](https://github.com/Amegatron/php-sync-fs)
to get an idea about one of the possible ways to achieve that by means of pure PHP (`proc_*` functions).
Though it is recommended that you stick to PHP as much as possible in tests, third-party tools or scripts
MAY still be involved, including, for example, scripts written in GoLang, Python, etc.