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
 * Interface StrategyInterface
 *
 * Strategies are classes which implement a rate limiting policy.
 *
 * @author  Samantha Quiñones <samantha@tembies.com>
 * @package Burdette
 */
interface StrategyInterface
{
    /**
     * Obtain a new access token for the given IdentityInterface
     *
     * @param  IdentityInterface $identity
     * @return TokenInterface
     */
    public function getToken(IdentityInterface $identity);

    /**
     * Replenish the tokens for the given BucketInterface
     *
     * @param BucketInterface $bucket
     */
    public function replenishTokens(BucketInterface $bucket);
}
