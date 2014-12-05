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

class StringIdentityTest extends \PHPUnit_Framework_TestCase
{
    public function testStringIdentity()
    {
        $id = new StringIdentity("foo");
        $this->assertEquals("foo", (string) $id);
    }
}
