# Custom Identity Factory - AWSBundle

Factory which converts configuration to identity objects and objects to authentication hashes.

Most of the time, the [GenericIdentityFactory](../../../../../Factory/GenericIdentityFactory.php) will be able to handle your models. (view [service definition](./service.md))

## Factory Class

```php
<?php
namespace Acme\Bundle\AWSExtensionBundle\Factory;

use LLS\Bundle\AWSBundle\Interfaces\IdentityFactoryInterface;

class MyCustomFactory implements IdentityFactoryInterface
{
    public function create(array $definition = null)
    {
        // Your logic
    }
    
    public function export(IdentityInterface $identity)
    {
        // Your logic
    }

    public function handle(IdentityInterface $identity)
    {
        // Your logic
    }
}
```

## Requirements

* Every identity factory must implements [IdentityFactoryInterface](../../../../../Interfaces/IdentityFactoryInterface.php)