<?php
/**
 * This file is part of the Burdette package.
 *
 * @copyright © Samantha Quiñones & Patryk Kruk, All Rights Reserved
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Burdette\Tests\Unit;


use Burdette\BucketRepository;
use Burdette\Identities\StringIdentity;

class BucketRepositoryTest extends \PHPUnit_Framework_TestCase
{
    /** @var \Mockery\MockInterface */
    private $storage;

    /** @var \Mockery\MockInterface */
    private $bucket_factory;


    public function testFind()
    {
        $identity = \Mockery::mock('Burdette\\IdentityInterface');
        $bucket   = \Mockery::mock('Burdette\\BucketInterface');

        $this->storage->shouldReceive('get')->once()->with($identity)->andReturn($bucket);
        $repo = new BucketRepository($this->storage, $this->bucket_factory);
        $this->assertEquals($bucket, $repo->find($identity));
    }

    public function testPersist()
    {
        $bucket   = \Mockery::mock('Burdette\\BucketInterface');

        $this->storage->shouldReceive('save')->once()->with($bucket)->andReturn(true);
        $repo = new BucketRepository($this->storage, $this->bucket_factory);
        $repo->persist($bucket);
    }

    public function testRemove()
    {
        $bucket   = \Mockery::mock('Burdette\\BucketInterface');

        $this->storage->shouldReceive('delete')->once()->with($bucket)->andReturn(true);
        $repo = new BucketRepository($this->storage, $this->bucket_factory);
        $repo->remove($bucket);
    }

    public function testCreate()
    {
        $bucket   = \Mockery::mock('Burdette\\BucketInterface');
        $identity = new StringIdentity('foo');
        $this->bucket_factory->shouldReceive('newInstance')->once()->with($identity);
        $repo = new BucketRepository($this->storage, $this->bucket_factory);
        $repo->create($identity);
    }

    public function setUp()
    {
        $this->storage = \Mockery::mock('Burdette\\StorageAdapterInterface');
        $this->bucket_factory = \Mockery::mock('Burdette\\BucketFactoryInterface');

    }
}
