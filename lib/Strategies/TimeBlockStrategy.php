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
class TimeBlockStrategy extends AbstractBucketBasedStrategy implements StrategyInterface
{
    const HOURLY      = 3600;
    const SEMI_HOURLY = 1800;
    const DAILY       = 86400;
    const SEMI_DAILY  = 43200;
    const PER_MINUTE  = 60;

    /** @var BucketRepositoryInterface */
    private $bucketRepository;

    /** @var integer */
    private $period = self::HOURLY;

    /** @var integer */
    private $maxTokens = 1;

    /**
     * @param BucketRepositoryInterface $bucketRepository
     */
    public function __construct(BucketRepositoryInterface $bucketRepository)
    {
        $this->bucketRepository = $bucketRepository;
    }

    /**
     * @param $tokens
     * @param $period
     */
    public function setReplenishmentRate($tokens, $period)
    {
        if (!is_int($tokens) || !is_int($period)) {
            throw new \InvalidArgumentException("Tokens and period must be integers");
        }

        $period = abs($period);
        $tokens = abs($tokens);

        $this->maxTokens = $tokens;
        $this->period    = $period;
    }

    public function getReplenishmentSize()
    {
        return $this->maxTokens;
    }

    public function getReplenishmentPeriod()
    {
        return $this->period;
    }

    /**
     * Obtain a new access token for the given IdentityInterface
     *
     * @param  IdentityInterface $identity
     *
     * @return TokenInterface
     */
    public function getToken(IdentityInterface $identity)
    {
        $bucket = $this->getBucket($identity, $this->bucketRepository, $this->maxTokens);
        $nextReplenishment = $this->replenishTokens($bucket);
        $token = $bucket->newToken($nextReplenishment);
        $this->saveBucket($this->bucketRepository, $bucket);

        return $token;
    }

    /**
     * Replenish the tokens for the given BucketInterface
     *
     * @param BucketInterface $bucket
     *
     * @return \DateTime
     */
    public function replenishTokens(BucketInterface $bucket)
    {
        $lastReplenishment = $bucket->getLastReplenishment();
        if (!$lastReplenishment instanceof \DateTime) {
            $lastReplenishment = new \DateTime('now');
            $bucket->setLastReplenishment($lastReplenishment);
        }

        $nextReplenishment = clone $lastReplenishment;
        $nextReplenishment->add(new \DateInterval("PT" . $this->period . "S"));

        $now = new \DateTime('now');
        if ($now >= $nextReplenishment) {
            $bucket->setTokens($this->maxTokens);
            $bucket->setLastReplenishment($now);
            $this->saveBucket($this->bucketRepository, $bucket);
        }

        return $nextReplenishment;
    }
}
