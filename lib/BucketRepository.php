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
 * Basic BucketRepository
 *
 * The basic bucket repository that uses a StorageAdapter to manage persistent Bucket objects.
 *
 * @author Samantha Quiñones <samantha@tembies.com>
 * @package Burdette
 */
class BucketRepository implements BucketRepositoryInterface
{
    /** @var StorageAdapterInterface */
    private $storage;

    /** @var BucketFactoryInterface */
    private $factory;

    /**
     * @param StorageAdapterInterface $storage
     * @param BucketFactoryInterface $factory
     */
    public function __construct(StorageAdapterInterface $storage, BucketFactoryInterface $factory)
    {
        $this->storage = $storage;
        $this->factory = $factory;
    }

    /**
     * @param  IdentityInterface $identity
     * @return BucketInterface|null
     */
    public function find(IdentityInterface $identity)
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

    /**
     * @param IdentityInterface $identityInterface
     * @return BucketInterface
     */
    public function create(IdentityInterface $identity)
    {
        return $this->factory->newInstance($identity);
    }
}
