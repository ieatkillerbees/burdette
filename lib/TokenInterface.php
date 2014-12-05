<?php
/**
 * This file is part of the Burdette package.
 *
 * @copyright © Samantha Quiñones & Patryk Kruk, All Rights Reserved
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Burdette;

/**
 * Interface TokenInterface
 *
 * @author Samantha Quiñones <samantha@tembies.com>
 * @package Burdette
 */
interface TokenInterface
{
    /**
     * @return integer
     */
    public function getAvailableTokens();

    /**
     * @return IdentityInterface
     */
    public function getIdentity();

    /**
     * @return int
     */
    public function getNextReplenish();

    /**
     * @return bool
     */
    public function isAllowed();
}
