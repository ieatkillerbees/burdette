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


class BaseStrategyTestCase extends \PHPUnit_Framework_TestCase
{
    /** @var \Mockery\MockInterface */
    protected $repo;

    /** @var \Mockery\MockInterface */
    protected $bucket;

    /** @var integer */
    public $canonicalTime;

    public function setUp()
    {
        $this->repo = \Mockery::mock('Burdette\\BucketRepositoryInterface');
        $this->bucket = \Mockery::mock('Burdette\\BucketInterface');
    }

    protected function getCanonicalTime()
    {
        if (!isset($this->canonicalTime)) {
            $this->canonicalTime = time();
        }

        return $this->canonicalTime;
    }
}
