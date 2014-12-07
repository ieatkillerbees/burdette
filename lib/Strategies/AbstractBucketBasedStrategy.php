<?php
/**
 * This file is part of the Burdette package.
 *
 * @copyright © Samantha Quiñones & Patryk Kruk, All Rights Reserved
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Burdette\Strategies;


use Burdette\BucketInterface;
use Burdette\BucketRepositoryInterface;
use Burdette\IdentityInterface;

/**
 * Class AbstractStrategy
 *
 * @author  Samantha Quiñones <samantha@tembies.com>
 * @package Burdette\Strategies
 */
class AbstractBucketBasedStrategy
{
    protected function getBucket(IdentityInterface $identity, BucketRepositoryInterface $repo, $maxTokens)
    {
        $bucket = $repo->find($identity);
        if (!$bucket) {
            $bucket = $repo->create($identity);
            $bucket->setTokens($maxTokens);
        }
        return $bucket;
    }

    protected function saveBucket(BucketRepositoryInterface $repo, BucketInterface $bucket)
    {
        return $repo->persist($bucket);
    }
}
