<?php
/**
 * This file is part of the Burdette package.
 *
 * @copyright © Samantha Quiñones & Patryk Kruk, All Rights Reserved
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Burdette\Identities;

use Burdette\IdentityInterface;

/**
 * Class StringIdentity
 *
 * A simple identity that takes a string as its source
 *
 * @author Samantha Quiñones <samantha@tembies.com>
 * @package Burdette
 */
class StringIdentity implements IdentityInterface
{
    /** @var string */
    private $identity;

    /**
     * @param string $identity
     */
    public function __construct($identity)
    {
        $this->identity = $identity;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->identity;
    }
}
