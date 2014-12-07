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
    const HOURLY = 3600;
    const SEMI_HOURLY = 1800;
    const DAILY = 86400;
    const SEMI_DAILY = 43200;
    const PER_MINUTE = 60;

    /** @var BucketRepositoryInterface */
    private $bucketRepository;

    /** @var integer */
    private $lastReplenishment;

    /**
     * @return int
     */
    public function getLastReplenishment()
    {
        if (!isset($this->lastReplenishment)) {
            $this->lastReplenishment = time();
        }
        return $this->lastReplenishment;
    }

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
        $this->period = $period;
    }

    public function getReplenishmentSize()
    {
        return $this->maxTokens;
    }

    public function getReplenishmentPeriod()
    {
        return $this->period;
    }

    public function getNextReplenishmentTime($dateTime = true)
    {
        $lastReplenishment = $this->getLastReplenishment();
        $nextReplenishment = $this->getNextReplenishment($lastReplenishment, $this->period);

        if (!$dateTime) {
            return $nextReplenishment;
        }

        $dateTime = new \DateTime();
        $dateTime->setTimestamp($nextReplenishment);
        return $dateTime;
    }

    /**
     * Obtain a new access token for the given IdentityInterface
     *
     * @param  IdentityInterface $identity
     * @return TokenInterface
     */
    public function getToken(IdentityInterface $identity)
    {
        $bucket = $this->getBucket($identity, $this->bucketRepository, $this->maxTokens);
        $this->replenishTokens($bucket);
        $token = $bucket->newToken(
            \DateTime::createFromFormat("U", $this->getNextReplenishment($this->lastReplenishment, $this->period))
        );
        $this->saveBucket($this->bucketRepository, $bucket);
        return $token;
    }

    /**
     * @param $time
     * @param $period
     * @return mixed
     */
    private function getPreviousReplenishment($time, $period)
    {
        return $time - ($time % $period);
    }

    /**
     * @param $time
     * @param $period
     * @return mixed
     */
    private function getNextReplenishment($time, $period)
    {
        return $this->getPreviousReplenishment($time, $period) + $period;
    }

    /**
     * Replenish the tokens for the given BucketInterface
     *
     * @param BucketInterface $bucket
     */
    public function replenishTokens(BucketInterface $bucket)
    {
        $lastReplenishment = $this->getLastReplenishment();

        $nextReplenishment = $this->getNextReplenishment($lastReplenishment, $this->period);
        if (time() < $nextReplenishment) {
            return;
        }

        $bucket->setTokens($this->maxTokens);
        $this->lastReplenishment = time();
    }
}
