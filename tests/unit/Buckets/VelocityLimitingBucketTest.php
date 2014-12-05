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

use Burdette\Buckets\VelocityLimitingBucket;
use Burdette\Identities\StringIdentity;

class VelocityLimitingBucketTest extends \PHPUnit_Framework_TestCase
{
    public function testConstructionAndSetters()
    {
        $identity = new StringIdentity("foo");
        $bucket = new VelocityLimitingBucket($identity, 10, 20);
        $identity2 = new StringIdentity("bar");
        $bucket->setIdentity($identity2);
        $this->assertEquals("bar", (string) $bucket->getIdentity());
    }
}
