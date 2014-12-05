<?php
/**
 * This file is part of the Burdette package.
 *
 * @copyright © Samantha Quiñones & Patryk Kruk, All Rights Reserved
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Burdette\Buckets;

use Burdette\BucketInterface;
use Burdette\IdentityInterface;
use Burdette\Token;

/**
 * Class VelocityLimitingBucket
 *
 * A rate limiting bucket used to limit against velocity, allowing n requests per p seconds
 *
 * @package Burdette\Buckets
 * @author Samantha Quiñones <samantha@tembies.com>
 */
class VelocityLimitingBucket implements BucketInterface
{
    /** @var IdentityInterface Identity of this bucket */
    private $identity;

    /** @var float Current number of tokens in the bucket */
    private $tokens;

    /** @var integer Number of tokens to generate per period */
    private $tokensPerPeriod;

    /** @var integer Number of seconds per period */
    private $period;

    /** @var float Number of tokens to generate each second */
    private $tokensPerSecond;

    /** @var integer Timestamp of last replenishment */
    private $lastReplenish;

    /**
     * @param IdentityInterface $identity
     * @param $tokens
     * @param $period
     */
    public function __construct(IdentityInterface $identity, $tokens, $period)
    {
        $this->identity = $identity;
        $this->tokensPerPeriod = $tokens;
        $this->period = $period;
        $this->tokens = (float)$tokens;
        $this->tokensPerSecond = $tokens / $period;
    }

    /**
     * @inheritdoc
     */
    public function getIdentity()
    {
        return $this->identity;
    }

    /**
     * @inheritdoc
     */
    public function setIdentity($identity)
    {
        $this->identity = $identity;
    }

    /**
     * @inheritdoc
     */
    public function getToken()
    {
        $allowed = ($this->getAvailableTokens() > 0);
        $token = new Token($this->identity, $allowed, $this->getAvailableTokens(), 0);

        if ($allowed) {
            $this->tokens--;
        }
        return $token;
    }

    /**
     * @inheritdoc
     */
    public function getAvailableTokens()
    {
        return (int)floor($this->tokens);
    }

    /**
     * @inheritdoc
     */
    public function replenish()
    {
        $time = time();
        $secs = $time - $this->lastReplenish;
        $tokens = $secs * $this->tokensPerSecond;
        $this->lastReplenish = $time;
        if (($this->tokens + $tokens) >= $this->tokensPerPeriod) {
            $this->tokens = $this->tokensPerPeriod;
            return;
        }
        $this->tokens += $tokens;
    }
}
