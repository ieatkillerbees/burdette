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
use Burdette\TokenFactory;

class TokenFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testNewInstance()
    {
        $factory = new TokenFactory();
        $identity = new StringIdentity('foo');
        $date = new \DateTime("+10 minutes");
        $token   = $factory->newInstance($identity, true, 42, $date);
        $this->assertInstanceOf('Burdette\\Token', $token);
        $this->assertEquals($identity, $token->getIdentity());
        $this->assertEquals($date, $token->getNextReplenish());
        $this->assertEquals(42, $token->getAvailable());
        $this->assertTrue($token->isAllowed());
    }
}
