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
 * Class BucketRepository
 *
 * @author Samantha Quiñones <samantha@tembies.com>
 * @package Burdette
 */
class BucketRepository implements BucketRepositoryInterface
{
    /** @var StorageAdapterInterface */
    private $storage;

    /**
     * @param StorageAdapterInterface $storage
     */
    public function __construct(StorageAdapterInterface $storage)
    {
        $this->storage = $storage;
    }

    /**
     * @param  IdentityInterface $identity
     * @return BucketInterface|null
     */
    public function findByIdentity(IdentityInterface $identity)
    {
        return $this->storage->get($identity);
    }

    /**
     * @param  BucketInterface $bucket
     * @return bool
     */
    public function persist(BucketInterface $bucket)
    {
        return $this->storage->save($bucket);
    }

    /**
     * @param BucketInterface $bucket
     * @return bool
     */
    public function remove(BucketInterface $bucket)
    {
        return $this->storage->delete($bucket);
    }
}
