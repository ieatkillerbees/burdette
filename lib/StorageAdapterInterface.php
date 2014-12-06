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
 * Interface StorageAdapterInterface
 *
 * Storage adapters are objects which manage the persistence of Bucket objects to and from a persistence layer
 *
 * @author Samantha Quiñones <samantha@tembies.com>
 * @package Burdette
 */
interface StorageAdapterInterface
{
    /**
     * @param  IdentityInterface $identity
     * @return BucketInterface
     */
    public function get(IdentityInterface $identity);

    /**
     * @param  BucketInterface $bucket
     * @return bool
     */
    public function save(BucketInterface $bucket);

    /**
     * @param  BucketInterface $bucket
     * @return bool
     */
    public function delete(BucketInterface $bucket);
}
