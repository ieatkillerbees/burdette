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
 * Basic Token implementation
 *
 * @{inheritdoc}
 *
 * @author Samantha Quiñones <samantha@tembies.com>
 * @package Burdette
 */
class Token implements TokenInterface
{
    /** @var IdentityInterface */
    private $identity;

    /** @var integer */
    private $available;

    /** @var integer */
    private $nextReplenish;

    /** @var bool */
    private $allowed;

    /**
     * @param IdentityInterface $identity
     * @param bool              $allowed
     * @param integer           $available
     * @param \DateTime|null    $nextReplenish
     */
    public function __construct(IdentityInterface $identity, $allowed, $available, \DateTime $nextReplenish = null)
    {
        $this->identity = $identity;
        $this->allowed = $allowed;
        $this->available = $available;
        $this->nextReplenish = $nextReplenish;
    }

    /**
     * @return int
     */
    public function getAvailable()
    {
        return $this->available;
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
