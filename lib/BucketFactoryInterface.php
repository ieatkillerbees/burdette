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
 * Interface BucketFactoryInterface
 *
 * Bucket Factories create instances of BucketInterface
 *
 * @author  Samantha Quiñones <samantha@tembies.com>
 * @package Burdette
 */
interface BucketFactoryInterface
{
    /**
     * @param IdentityInterface $identity
     * @return BucketInterface
     */
    public function newInstance(IdentityInterface $identity);
}
 