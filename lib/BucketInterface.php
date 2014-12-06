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
 * Buckets are the containers of state for a given Identity.
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
     * @param IdentityInterface $identity
     * @return BucketInterface
     */
    public function setIdentity(IdentityInterface $identity);

    /**
     * @return integer
     */
    public function getTokens();

    /**
     * @param  integer $tokens
     * @return BucketInterface
     */
    public function setTokens($tokens);

    public function newToken($nextReplenishment = null);
}
