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
use Burdette\Strategies\TimeBlockStrategy;

require __DIR__ . '/BaseStrategyTestCase.php';

class TimeBlockStrategyTest extends BaseStrategyTestCase
{
    /**
     * @param $tokens
     * @param $period
     * @param $expected
     * @dataProvider rateProvider
     */
    public function testSettingReplenishmentPeriod($tokens, $period, $expected)
    {
        $strategy = new TimeBlockStrategy($this->repo);

        if ($expected instanceof \Exception) {
            $this->setExpectedException(get_class($expected), $expected->getMessage());
        }

        $strategy->setReplenishmentRate($tokens, $period);


        $this->assertEquals($tokens, $strategy->getReplenishmentSize());
        $this->assertEquals($period, $strategy->getReplenishmentPeriod());
        $nextReplenishment = $expected;
        $nextReplenishment = new \DateTime();
        $nextReplenishment->setTimestamp($expected);
    }

    public function rateProvider()
    {
        return [
            [ 10, 3600, $this->getNextReplenishmentTime($this->getCanonicalTime(), 3600) ],
            [ 'foo', 3600, new \InvalidArgumentException("Tokens and period must be integers") ],
            [ 10, 'foo', new \InvalidArgumentException("Tokens and period must be integers") ],
        ];
    }

    /**
     * @param $tokens
     * @dataProvider tokensProvider
     */
    public function testReplenishment($tokens, $replenish, \DateTime $lastReplenishment)
    {
        $this->repo->shouldReceive('persist')->withAnyArgs();
        $strategy = new TimeBlockStrategy($this->repo);
        $strategy->setReplenishmentRate($replenish, 1);
        $rClass = new \ReflectionClass($strategy);
        $rProp = $rClass->getProperty('lastReplenishment');
        $rProp->setAccessible(true);
        $rProp->setValue($strategy, $lastReplenishment->getTimestamp());
        $bucket   = \Mockery::mock('Burdette\\BucketInterface');
        $bucket->shouldReceive('getTokens')->once()->withNoArgs()->andReturn($tokens);
        $bucket->shouldReceive('setTokens')->once()->with($replenish)->andReturnNull();
        $bucket->shouldReceive('getLastReplenishment')->withNoArgs()->andReturn($lastReplenishment->getTimestamp());
        $bucket->shouldReceive('setLastReplenishment')->with(\Mockery::type('int'))->andReturnNull();
        $strategy->replenishTokens($bucket);
    }

    public function tokensProvider()
    {
        return [
            [42, 10, new \DateTime("-1 seconds")],
            [5, 8, new \DateTime("-1 seconds")],
            [5, 8, new \DateTime("+1 seconds")]
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
        $strategy = new TimeBlockStrategy($this->repo);
        $token = $strategy->getToken($identity);
    }

    public function tokenGenerationProvider()
    {
        return [
            [ false ]
        ];
    }

    private function getNextReplenishmentTime($time, $period)
    {
        $prev = $time - ($time % $period);
        return $prev + $period;
    }

}
