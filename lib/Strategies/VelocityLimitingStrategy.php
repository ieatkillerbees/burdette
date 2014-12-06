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
use Burdette\StrategyInterface;
use Burdette\TokenInterface;

/**
 * Class VelocityLimitingBucketStrategy
 *
 * This strategy allows limiting to a number of requests per a shifting period of time. For example, you can set a max
 * rate (velocity) of 7 requests per 10 seconds.
 *
 * @package Burdette\Strategies
 * @author  Samantha Quiñones <samantha@tembies.com>
 */
class VelocityLimitingStrategy implements StrategyInterface
{
    /** @var BucketRepositoryInterface */
    private $bucketRepository;

    /** @var integer */
    private $lastReplenishment;

    /** @var float */
    private $replenishRate = 1;

    /** @var integer */
    private $maxTokens = 10;

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
     * If the optional third parameter is set to true, the velocity will be taken as an absolute value,
     * ie, setVelocity(10, 1, true) will set the replenishment rate to 1 token per second with a max of
     * 10 tokens.
     *
     * @param integer       $tokens
     * @param integer|float $period
     * @param bool          $absolute   Period in absolute mode
     */
    public function setVelocity($tokens, $period, $absolute = false)
    {
        if (!$absolute && $period === 0) {
            throw new \LogicException("Period cannot be 0");
        }

        if ($absolute && $period > $tokens) {
            throw new \LogicException("Absolute rate cannot be greater than total max tokens");
        }

        $this->maxTokens = $tokens;
        if ($absolute) {
            $this->replenishRate = $period;
            return;
        }
        $this->replenishRate = (float) ($tokens / $period);
    }

    /**
     * Returns the actual replenishment rate
     *
     * @return float
     */
    public function getVelocity()
    {
        return $this->replenishRate;
    }

    /**
     * @param IdentityInterface $identity
     * @return TokenInterface
     */
    public function getToken(IdentityInterface $identity)
    {
        $bucket = $this->bucketRepository->find($identity);
        if (!$bucket) {
            $bucket = $this->bucketRepository->create($identity);
            $bucket->setTokens($this->maxTokens);
        }
        $this->replenishTokens($bucket);
        $token = $bucket->newToken();
        $this->bucketRepository->persist($bucket);
        return $token;
    }

    /**
     * @param BucketInterface $bucket
     */
    public function replenishTokens(BucketInterface $bucket)
    {
        $time = time();
        if (!isset($this->lastReplenishment)) {
            $this->lastReplenishment = $time;
        }
        $secs = $time - $this->lastReplenishment;
        $tokens = $secs * $this->replenishRate;
        $this->lastReplenishment = $time;
        if (($bucket->getTokens() + $tokens) >= $this->maxTokens) {
            $bucket->setTokens($this->maxTokens);
            return;
        }
        $bucket->setTokens($bucket->getTokens() + $tokens);
    }
}
