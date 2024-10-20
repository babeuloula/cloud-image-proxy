# CloudImage Proxy

Installation
============

Make sure Composer is installed globally, as explained in the
[installation chapter](https://getcomposer.org/doc/00-intro.md)
of the Composer documentation.

### Step 1: Download the Bundle

Open a command console, enter your project directory and execute the
following command to download the latest stable version of this bundle:

```console
composer require babeuloula/cloud-image-proxy
```

### Step 2: Enable the Bundle

Then, enable the bundle by adding it to the list of registered bundles
in the `config/bundles.php` file of your project:

```php
// config/bundles.php

return [
    // ...
    BaBeuloula\CloudImageProxy\CloudImageProxyBundle::class => ['all' => true],
];
```

Contributing
============

### Build and install dependencies

You can use the existing docker stack with the command `make install` to build the Dockerfile and install the composer
dependencies.

If you want to execute some commands through Docker, just use `docker/exec your_command`.

### Run testing stack

```bash
# Run all tests
make check

# Execute PHPCS
make lint

# Execute PHPCS fixer
make fixer

# Execute PHPStan
make analyse
```
