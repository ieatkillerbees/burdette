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
 * Class BucketFactory
 *
 * The basic BucketFactory creates instances of Bucket for a givent IdentityInterface
 *
 * @author  Samantha Quiñones <samantha@tembies.com>
 * @package Burdette
 */
class BucketFactory implements BucketFactoryInterface
{
    /** @var TokenFactoryInterface  */
    private $tokenFactory;

    public function __construct(TokenFactoryInterface $tokenFactory)
    {
        $this->tokenFactory = $tokenFactory;
    }

    /**
     * @param IdentityInterface $identity
     * @return BucketInterface
     */
    public function newInstance(IdentityInterface $identity)
    {
        return new Bucket($identity, $this->tokenFactory);
    }
}
