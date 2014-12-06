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


use Burdette\BucketFactory;
use Burdette\Identities\StringIdentity;

class BucketFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testConstruction()
    {
        $token_factory = \Mockery::mock('Burdette\\TokenFactoryInterface');
        $identity      = new StringIdentity('foo');
        $factory = new BucketFactory($token_factory);
        $bucket = $factory->newInstance($identity);
        $this->assertInstanceOf('Burdette\\BucketInterface', $bucket);
        $this->assertEquals($identity, $bucket->getIdentity());
        $this->assertEquals(0, $bucket->getTokens());
    }

}
