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

use Burdette\Bucket;
use Burdette\Identities\StringIdentity;

class BucketTest extends \PHPUnit_Framework_TestCase
{
    /** @var \Mockery\MockInterface */
    private $token_factory;

    public function testConstructionAndSetters()
    {
        $factory = \Mockery::mock('Burdette\\TokenFactoryInterface');
        $identity = new StringIdentity("foo");
        $bucket = new Bucket($identity, $factory);
        $identity2 = new StringIdentity("bar");
        $bucket->setIdentity($identity2);
        $this->assertEquals("bar", (string) $bucket->getIdentity());
    }

    public function testTokensAndRealTokens()
    {
        $bucket = new Bucket(new StringIdentity('foo'), $this->token_factory);
        $bucket->setTokens(42.5);
        $this->assertEquals(42.5, $bucket->getRealTokens());
        $this->assertEquals(42, $bucket->getTokens());
    }

    public function testTokenGeneration()
    {
        $identity = new StringIdentity('foo');
        $next     = new \DateTime(" +10 minutes ");

        $this->token_factory->shouldReceive('newInstance')->with($identity, true, 9, $next);
        $bucket = new Bucket($identity, $this->token_factory);
        $bucket->setTokens(10);
        $bucket->newToken($next);
    }


    public function setUp()
    {
        $this->token_factory = \Mockery::mock('Burdette\\TokenFactoryInterface');
    }
}
