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

### Step 3: Configure the Bundle

```yaml
# config/packages/cloud_image_proxy.yaml

cloud_image_proxy:
    proxy:
        assets_path: 'mandatory'
        url: 'mandatory'
        check_assets: true # if the bundle need to check if you have the file on the server before fetch from CloudImage
        encrypted_parameters: false # if you need to hide the query parameters on your application
    encrypter:
        secret_key: null # the key encrypting and decrypting the query parameters (required if proxy.encrypted_parameters is true)
    twig:
        route_name: 'mandatory' # the route to the controller that displays the assets
        route_parameter: 'mandatory' # the route parameter name
```

Using a fallback handler
========================

If you don't have access to CloudImage or if you want to use it on local development, you can set up a fallback handler.

Actually, I only support [Intervention Image v3](https://image.intervention.io/v3).

```yaml
Intervention\Image\Drivers\Imagick\Driver: ~

BaBeuloula\CloudImageProxy\FallbackHandler\InterventionImageFallbackHandler:
    arguments:
        $assetsPath: '_your_path_'
        $driver: '@Intervention\Image\Drivers\Imagick\Driver'
        $cache: '_your_cache_instance_'

BaBeuloula\CloudImageProxy\FallbackHandler\FallbackHandlerInterface: '@BaBeuloula\CloudImageProxy\FallbackHandler\InterventionImageFallbackHandler'
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
