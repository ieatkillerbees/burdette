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

use Burdette\BucketRepositoryInterface;
use Burdette\Buckets\VelocityLimitingBucket;
use Burdette\IdentityInterface;

/**
 * Class VelocityLimitingBucketStrategy
 *
 * This strategy allows limiting to a number of requests per a shifting period of time. For example, you can set a max
 * rate (velocity) of 7 requests per 10 seconds.
 *
 * @package Burdette\Strategies
 * @author  Samantha Quiñones <samantha@tembies.com>
 */
class VelocityLimitingBucketStrategy
{
    /** @var BucketRepositoryInterface */
    private $bucketRepository;

    /** @var integer */
    private $tokens;

    /** @var integer */
    private $period;

    /**
     * @param BucketRepositoryInterface $bucketRepository
     */
    public function __construct(BucketRepositoryInterface $bucketRepository)
    {
        $this->bucketRepository = $bucketRepository;
    }

    /**
     * Set the maximum velocity for the strategy in terms of $tokens per $period seconds.
     *
     * For example, let $tokens == 5 and $period == 10 (seconds)
     * This bucket will replenish 5 tokens every 10 seconds or 1 token every 2 seconds. Technically, the bucket will
     * generate half a token each second, but fractional token counters are ignored when issuing new tokens. That is,
     * if a bucket has 2.5 tokens, it will behave as though it has only 2 tokens.
     *
     * @param $tokens
     * @param $period
     */
    public function setVelocity($tokens, $period)
    {
        $this->tokens = $tokens;
        $this->period = $period;
    }

    /**
     * @param IdentityInterface $identity
     * @return \Burdette\Token|\Burdette\TokenInterface
     */
    public function getToken(IdentityInterface $identity)
    {
        $bucket = $this->bucketRepository->findByIdentity($identity);
        if (!$bucket) {
            $bucket = new VelocityLimitingBucket($identity, $this->tokens, $this->period);
        }
        $bucket->replenish();
        $token = $bucket->getToken();
        $this->bucketRepository->persist($bucket);
        return $token;
    }
}
