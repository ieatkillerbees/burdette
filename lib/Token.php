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
 * Class Token
 *
 * @author Samantha Quiñones <samantha@tembies.com>
 * @package Burdette
 */
class Token implements TokenInterface
{
    /** @var IdentityInterface */
    private $identity;

    /** @var integer */
    private $availableTokens;

    /** @var integer */
    private $nextReplenish;

    /** @var bool */
    private $allowed;

    /**
     * @param IdentityInterface $identity
     * @param $allowed
     * @param $availableTokens
     * @param $nextReplenish
     */
    public function __construct(IdentityInterface $identity, $allowed, $availableTokens, $nextReplenish)
    {
        $this->identity = $identity;
        $this->allowed = $allowed;
        $this->availableTokens = $availableTokens;
        $this->nextReplenish = $nextReplenish;
    }

    /**
     * @return int
     */
    public function getAvailableTokens()
    {
        return $this->availableTokens;
    }

    /**
     * @return IdentityInterface
     */
    public function getIdentity()
    {
        return $this->identity;
    }

    /**
     * @return int
     */
    public function getNextReplenish()
    {
        return $this->nextReplenish;
    }

    /**
     * @return bool
     */
    public function isAllowed()
    {
        return $this->allowed;
    }
}
