<?php
namespace LLS\Bundle\AWSBundle\Tests\Fixtures;

use LLS\Bundle\AWSBundle\Interfaces\ClientFactoryInterface;
use LLS\Bundle\AWSBundle\Interfaces\IdentityInterface;
use LLS\Bundle\AWSBundle\Interfaces\ServiceInterface;

class FakeService implements ServiceInterface
{
    protected $identity;
    protected $clientFactory;
    protected $something;

    /**
     * {@inheritDoc}
     */
    public function __construct(IdentityInterface $identity, ClientFactoryInterface $clientFactory)
    {
        $this->identity      = $identity;
        $this->clientFactory = $clientFactory;
    }

    public function getidentity()
    {
        return $this->identity;
    }

    public function getClientFactory()
    {
        return $this->clientFactory;
    }

    public function setSomething($something)
    {
        $this->something = $something;

        return $this;
    }

    public function getSomething()
    {
        return $this->something;
    }
}