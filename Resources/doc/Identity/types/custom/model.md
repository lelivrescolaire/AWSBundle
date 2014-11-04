# Custom Identity Model - AWSBundle

Defines how your data are exposed at configuration and authentication.

## Model class

```php
<?php
# Acme/Bundle/AWSExtensionBundle/Model/MyCustomIdentity.php

namespace Acme\Bundle\AWSExtensionBundle\Model;

use LLS\Bundle\AWSBundle\Annotation\Identity;
use LLS\Bundle\AWSBundle\Interfaces\IdentityInterface;

class MyCustomIdentity implements IdentityInterface
{
    /**
     * @Identity()
     */
    protected $myFirstParameter;

    /**
     * @Identity()
     */
    protected $mySecondParameter;

    public function getMyFirstParameter()
    {
        return $this->myFirstParameter;
    }

    public function getMySecondParameter()
    {
        return $this->mySecondParameter;
    }
}
```

## Requirements

* Every identity model must implements [IdentityInterface](../../../../../Interfaces/IdentityInterface.php)
* Use Identity annotation to expose model data at configuration/authentication. [Annotation Reference](../../../Annotation/@Identity.md)