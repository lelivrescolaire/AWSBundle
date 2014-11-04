<?php
namespace LLS\Bundle\AWSBundle\Model\Identity;

use LLS\Bundle\AWSBundle\Annotation\Identity;
use LLS\Bundle\AWSBundle\Interfaces\Identity\UserInterface;
use LLS\Bundle\AWSBundle\Model\AbstractIdentity;

/**
 * Identity class able to perform AWS API requests
 *
 * @author Jérémy Jourdin <jeremy.jourdin@lelivrescolaire.fr>
 */
class User extends AbstractIdentity
{
    /**
     * @var string AWS API Key
     *
     * @Identity()
     */
    protected $key;

    /**
     * @var string AWS API Secret
     *
     * @Identity()
     */
    protected $secret;

    /**
     * @var string AWS API Region
     *
     * @Identity()
     */
    protected $region;

    /**
     * Set AWS API Key
     *
     * @param string $key AWS API Key
     *
     * @return {$this}
     */
    public function setKey($key)
    {
        $this->key = $key;

        return $this;
    }

    /**
     * Get AWS API Key
     *
     * @return string AWS API Key
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * Set AWS API Secret
     *
     * @param string $secret AWS API Secret
     *
     * @return {$this}
     */
    public function setSecret($secret)
    {
        $this->secret = $secret;

        return $this;
    }

    /**
     * Get AWS API secret
     *
     * @return string AWS API secret
     */
    public function getSecret()
    {
        return $this->secret;
    }

    /**
     * Set AWS API Region
     *
     * @param string $region AWS API Region
     *
     * @return {$this}
     */
    public function setRegion($region)
    {
        $this->region = $region;

        return $this;
    }

    /**
     * Get AWS API region
     *
     * @return string AWS API region
     */
    public function getRegion()
    {
        return $this->region;
    }
}