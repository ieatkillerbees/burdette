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
 * Interface TokenFactoryInterface
 *
 * Token factories are objects which generate TokenInterface objects
 *
 * @author  Samantha Quiñones <samantha@tembies.com>
 * @package Burdette
 */
interface TokenFactoryInterface
{
    /**
     * @param IdentityInterface $identity
     * @param bool              $allowed
     * @param integer           $tokens
     * @param \DateTime|null    $nextReplenishment
     * @return TokenInterface
     */
    public function newInstance(IdentityInterface $identity, $allowed, $tokens, \DateTime $nextReplenishment = null);
}