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

class BucketRepositoryTest extends \PHPUnit_Framework_TestCase
{
    /** @var \Mockery\MockInterface */
    private $storage;

    public function testFind()
    {
        $identity = \Mockery::mock('Burdette\\IdentityInterface');
        $bucket   = \Mockery::mock('Burdette\\BucketInterface');

        $this->storage->shouldReceive('get')->once()->with($identity)->andReturn($bucket);
        $repo = new BucketRepository($this->storage);
        $this->assertEquals($bucket, $repo->findByIdentity($identity));
    }

    public function testPersist()
    {
        $bucket   = \Mockery::mock('Burdette\\BucketInterface');

        $this->storage->shouldReceive('save')->once()->with($bucket)->andReturn(true);
        $repo = new BucketRepository($this->storage);
        $repo->persist($bucket);
    }

    public function testRemove()
    {
        $bucket   = \Mockery::mock('Burdette\\BucketInterface');

        $this->storage->shouldReceive('remove')->once()->with($bucket)->andReturn(true);
        $repo = new BucketRepository($this->storage);
        $repo->remove($bucket);
    }

    public function setUp()
    {
        $this->storage = \Mockery::mock('Burdette\\StorageAdapterInterface');

    }
}
