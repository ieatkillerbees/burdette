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

use Burdette\Token;

class TokenTest extends \PHPUnit_Framework_TestCase
{
    public function testTokenGetters()
    {
        $identity = $this->getMockBuilder('Burdette\\IdentityInterface')->getMock();
        $time = time();
        $token = new Token($identity, true, 1, \DateTime::createFromFormat("U", $time+10));
        $this->assertEquals($identity, $token->getIdentity());
        $this->assertEquals(true, $token->isAllowed());
        $this->assertEquals(1, $token->getAvailable());
        $this->assertEquals(\DateTime::createFromFormat("U", $time+10), $token->getNextReplenish());
    }
}
