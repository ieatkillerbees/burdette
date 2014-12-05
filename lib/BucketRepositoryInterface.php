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
 * Interface BucketRepositoryInterface
 *
 * @author Samantha Quiñones <samantha@tembies.com>
 * @package Burdette
 */
interface BucketRepositoryInterface
{
    /**
     * @param IdentityInterface $identity
     * @return BucketInterface|null
     */
    public function findByIdentity(IdentityInterface $identity);

    /**
     * @param  BucketInterface $bucket
     * @return bool
     */
    public function persist(BucketInterface $bucket);

    /**
     * @param BucketInterface $bucket
     * @return bool
     */
    public function remove(BucketInterface $bucket);
}
