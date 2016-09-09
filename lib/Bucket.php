<?php
/**
 * This file is part of the Burdette package.
 *
 * @copyright © Samantha Quiñones & Patryk Kruk, All Rights Reserved
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Burdette;

/**
 * Class Bucket
 *
 * The basic Bucket implementation that stores rate limit state information for a given Identity
 *
 * @author  Samantha Quiñones <samantha@tembies.com>
 * @package Burdette
 */
class Bucket implements BucketInterface
{
    /** @var IdentityInterface */
    private $identity;

    /** @var float */
    private $tokens = 0;

    /** @var TokenFactoryInterface */
    private $tokenFactory;
    
    private $lastReplenish;

    /**
     * @return IdentityInterface
     */
    public function getIdentity()
    {
        return $this->identity;
    }

    /**
     * @param IdentityInterface $identity
     * @return BucketInterface
     */
    public function setIdentity(IdentityInterface $identity)
    {
        $this->identity = $identity;
        return $this;
    }

    /**
     * @return integer
     */
    public function getTokens()
    {
        return (int) floor($this->tokens);
    }

    /**
     * @return float
     */
    public function getRealTokens()
    {
        return $this->tokens;
    }

    /**
     * @param  integer $tokens
     * @return BucketInterface
     */
    public function setTokens($tokens)
    {
        $this->tokens = $tokens;
        return $this;
    }

    /**
     * @param IdentityInterface $identity
     * @param TokenFactoryInterface $tokenFactory
     */
    public function __construct(IdentityInterface $identity, TokenFactoryInterface $tokenFactory)
    {
        $this->identity = $identity;
        $this->tokenFactory = $tokenFactory;
    }

    /**
     * @param null $nextReplenishment
     * @return TokenInterface
     */
    public function newToken($nextReplenishment = null)
    {
        $allowed = ($this->getTokens() > 0);
        if ($allowed) {
            $this->tokens--;
        }
        $token = $this->tokenFactory->newInstance($this->identity, $allowed, $this->getTokens(), $nextReplenishment);
        return $token;
    }

    /**
     * @param \DateTime $dateTime
     */
    public function setLastReplenishment($dateTime)
    {
        $this->lastReplenish = $dateTime;
    }

    /**
     * @return \DateTime
     */
    public function getLastReplenishment()
    {
        return $this->lastReplenish;
    }
}
