<?php
namespace LLS\Bundle\AWSBundle\Tests\Fixtures;

use LLS\Bundle\AWSBundle\Interfaces\ClientFactoryInterface;
use LLS\Bundle\AWSBundle\Interfaces\IdentityInterface;
use LLS\Bundle\AWSBundle\Interfaces\ServiceInterface;

class FakeService implements ServiceInterface
{
    protected $identity;
    protected $clientFactory;

    /**
     * {@inheritDoc}
     */
    public function __construct(IdentityInterface $identity, ClientFactoryInterface $clientFactory, \Countable $test = null)
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
}