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

    /** @var \DateTime */
    private $nextReplenishment;

    /** @var bool */
    private $allowed;

    /**
     * @param IdentityInterface $identity
     * @param bool              $allowed
     * @param integer           $available
     * @param \DateTime|null    $nextReplenishment
     */
    public function __construct(IdentityInterface $identity, $allowed, $available, \DateTime $nextReplenishment = null)
    {
        $this->identity = $identity;
        $this->allowed = $allowed;
        $this->available = $available;
        $this->nextReplenishment = $nextReplenishment;
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
     * @return \DateTime
     */
    public function getNextReplenishment()
    {
        return $this->nextReplenishment;
    }

    /**
     * @return bool
     */
    public function isAllowed()
    {
        return $this->allowed;
    }
}
