<?php
/**
 * This file is part of the Burdette package.
 *
 * @copyright © Samantha Quiñones & Patryk Kruk, All Rights Reserved
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Burdette\Tests\Functional;


use Burdette\BucketRepository;
use Burdette\Identities\StringIdentity;
use Burdette\StorageAdapters\FileStorageAdapter;
use Burdette\Strategies\VelocityLimitingBucketStrategy;

class VelocityLimitingBucketStrategyTest extends \PHPUnit_Framework_TestCase
{
    public function testStrategy()
    {
        $path = __DIR__ . '/_storage/';
        if (!is_dir($path)) {
            mkdir($path);
        }
        $storage  = new FileStorageAdapter($path);
        $repo     = new BucketRepository($storage);
        $strategy = new VelocityLimitingBucketStrategy($repo);
        $strategy->setVelocity(5, 10);
        $identity = new StringIdentity("foo");
        if (file_exists($path . (string) $identity)) {
            unlink($path . (string) $identity);
        }
        $count = 0;
        while ($count < 5) {
            $token = $strategy->getToken($identity);
            $this->assertEquals(true, $token->isAllowed());
            $count++;
        }
        $token = $strategy->getToken($identity);
        $this->assertEquals(false, $token->isAllowed());

        sleep(2);
        $token = $strategy->getToken($identity);
        $this->assertEquals(true, $token->isAllowed());
        $this->assertEquals(1, $token->getAvailableTokens());
        unlink($path . (string) $identity);
        rmdir($path);
    }
}
