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


use Burdette\Identities\StringIdentity;
use Burdette\Strategies\VelocityLimitingStrategy;

class VelocityLimitingStrategyTest extends \PHPUnit_Framework_TestCase
{
    /** @var \Mockery\MockInterface */
    private $repo;

    /** @var \Mockery\MockInterface */
    private $bucket;

    /**
     * @param $tokens
     * @param $period
     * @param $absolute
     * @param $expected
     * @dataProvider velocityProvider
     */
    public function testSettingVelocity($tokens, $period, $absolute, $expected)
    {
        $strategy = new VelocityLimitingStrategy($this->repo);

        if ($expected instanceof \Exception) {
            $this->setExpectedException(get_class($expected), $expected->getMessage());
        }
        $strategy->setVelocity($tokens, $period, $absolute);
        $this->assertEquals($expected, $strategy->getVelocity());
    }

    public function velocityProvider()
    {
        return [
            [ 1, 10, false, 0.1 ],
            [ 10, 10, false, 1 ],
            [ 5, 10, false, 0.5 ],
            [ 10, 5, false, 2 ],
            [ 1, 0, false, new \LogicException("Period cannot be 0") ],
            [ 10, 1, true, 1 ],
            [ 10, 20, true, new \LogicException("Absolute rate cannot be greater than total max tokens") ],
        ];
    }

    /**
     * @param $tokens
     * @dataProvider tokensProvider
     */
    public function testReplenishment($tokens, $replenish, \DateTime $lastReplenishment)
    {
        $strategy = new VelocityLimitingStrategy($this->repo);
        $rClass = new \ReflectionClass($strategy);
        $rProp = $rClass->getProperty('lastReplenishment');
        $rProp->setAccessible(true);
        $rProp->setValue($strategy, $lastReplenishment->getTimestamp());
        $bucket   = \Mockery::mock('Burdette\\BucketInterface');
        $bucket->shouldReceive('getTokens')->once()->withNoArgs()->andReturn($tokens);
        $bucket->shouldReceive('setTokens')->once()->with(\Hamcrest\Matchers::atLeast($replenish-2) && \Hamcrest\Matchers::atMost($replenish+2))->andReturnNull();
        $strategy->replenishTokens($bucket);
    }

    public function tokensProvider()
    {
        return [
            [42, 10, new \DateTime("-2 seconds")],
            [5, 8, new \DateTime("-2 seconds")]
        ];
    }

    /**
     * @param $found
     * @dataProvider tokenGenerationProvider
     */
    public function testGetToken($found)
    {
        $identity = new StringIdentity('foo');
        $this->repo->shouldReceive('find')->once()->with($identity)->andReturn($found);
        $this->bucket->shouldReceive('newToken')->once()->withNoArgs()->andReturn(\Mockery::mock('Burdette\\TokenInterface'));
        $this->repo->shouldReceive('persist')->once()->with($this->bucket)->andReturn(true);
        $this->bucket->shouldIgnoreMissing();
        if ($found === false) {
            $this->bucket->shouldReceive('setTokens')->once()->with(10)->andReturnNull();
            $this->repo->shouldReceive('create')->once()->with($identity)->andReturn($this->bucket);
        }
        $strategy = new VelocityLimitingStrategy($this->repo);
        $token = $strategy->getToken($identity);
    }

    public function tokenGenerationProvider()
    {
        return [
            [ false ]
        ];
    }

    public function setUp()
    {
        $this->repo = \Mockery::mock('Burdette\\BucketRepositoryInterface');
        $this->bucket = \Mockery::mock('Burdette\\BucketInterface');
    }
}
