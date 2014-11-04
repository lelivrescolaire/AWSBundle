# @Identity Annotation - AWS Bundle

Use property/method to identify an identity parameter.

## Property Annotation

Use this property as an authentication element. (use Symfony's PropertyAccess to get/set data)

Arguments:

* `name`: Explicitely set the  exposed name (Optional, Default: __Variable Label__)

```php
<?php
namespace Acme\MyBundle\Model;

use LLS\Bundle\AWSBundle\Annotation\Identity;
use LLS\Bundle\AWSBundle\Interfaces\IdentityInterface;

class MyCustomIdentity implements IdentityInterface
{
    /**
     * @Identity
     */
    public    $myPublic;

    /**
     * @Identity
     */
    protected $myPrivate;

    /**
     * @Identity(name="myKey")
     */
    protected    $myRenamed;

    public function getMyPrivate()
    {
        return $this->myPrivate;
    }

    public function setMyPrivate($value)
    {
        $this->myPrivate = $value;

        return $this;
    }

    public function getMyRenamed()
    {
        return $this->myRenamed;
    }

    public function setMyRenamed($value)
    {
        $this->myRenamed = $value;

        return $this;
    }
}

```

| Property  | Exposed name | Accessor     | Mutator      |
|-----------|--------------|--------------|--------------|
| myPublic  | myPublic     |              |              |
| myPrivate | myPrivate    | getMyPrivate | setMyPrivate |
| myRenamed | myKey        | getMyRenamed | setMyRenamed |

## Method Annotation

Use this method to get/set a virtual authentication element.

Arguments:

* `name`: Explicitely set the  exposed name (Required)
* `type`: `getter` or `setter` (Optional, Default: `getter`)

```php
<?php
namespace Acme\MyBundle\Model;

use LLS\Bundle\AWSBundle\Annotation\Identity;
use LLS\Bundle\AWSBundle\Interfaces\IdentityInterface;

class MyCustomIdentity implements IdentityInterface
{
    /**
     * @Identity
     */
    public    $myPublic;

    /**
     * @Identity
     */
    protected $myPrivate;

    /**
     * @Identity(name="myKey")
     */
    protected $myRenamed;

    protected $foo;

    public function getMyPrivate()
    {
        return $this->myPrivate;
    }

    public function setMyPrivate($value)
    {
        $this->myPrivate = $value;

        return $this;
    }

    public function getMyRenamed()
    {
        return $this->myRenamed;
    }

    public function setMyRenamed($value)
    {
        $this->myRenamed = $value;

        return $this;
    }

    /**
     * @Identity(name="myVirtual", type="setter")
     */
    public function foo(value)
    {
        $this->foo = $value;

        return $this;
    }

    /**
     * @Identity(name="myVirtual", type="getter")
     */
    public function bar(value)
    {
        return $this->foo;
    }
}

```

| Property  | Exposed name | Accessor     | Mutator      |
|-----------|--------------|--------------|--------------|
| myPublic  | myPublic     |              |              |
| myPrivate | myPrivate    | getMyPrivate | setMyPrivate |
| myRenamed | myKey        | getMyRenamed | setMyRenamed |
|           | myVirtual    | bar          | foo          |