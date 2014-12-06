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
 * Bucket repositories provide a layer of abstraction between buckets and and persistence layer. Bucket repositories
 * allow for high-level CRUD operations on Bucket objects.
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
    public function find(IdentityInterface $identity);

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

    /**
     * @param IdentityInterface $identityInterface
     * @return BucketInterface
     */
    public function create(IdentityInterface $identityInterface);
}
