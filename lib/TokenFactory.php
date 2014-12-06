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
 * Basic TokenFactory
 *
 * @{inheritdoc}
 *
 * @author  Samantha Quiñones <samantha@tembies.com>
 * @package Burdette
 */
class TokenFactory implements TokenFactoryInterface
{

    /**
     * @param IdentityInterface $identity
     * @param bool $allowed
     * @param integer $tokens
     * @param \DateTime|null $nextReplenishment
     * @return TokenInterface
     */
    public function newInstance(IdentityInterface $identity, $allowed, $tokens, \DateTime $nextReplenishment = null)
    {
        return new Token($identity, $allowed, $tokens, $nextReplenishment);
    }
}
 