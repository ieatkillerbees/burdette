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
 * Interface TokenBucketInterface
 *
 * @author Samantha Quiñones <samantha@tembies.com>
 * @package Burdette
 */
interface BucketInterface
{
    /**
     * @return IdentityInterface
     */
    public function getIdentity();

    /**
     * Get the number of available tokens
     *
     * @return integer
     */
    public function getAvailableTokens();

    /**
     * Obtain a token
     *
     * @return TokenInterface
     */
    public function getToken();

    /**
     * Replenish the bucket
     *
     * @return null
     */
    public function replenish();
}
