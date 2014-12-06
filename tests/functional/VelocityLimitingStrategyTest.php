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


use Burdette\BucketFactory;
use Burdette\BucketRepository;
use Burdette\Identities\StringIdentity;
use Burdette\StorageAdapters\FileStorageAdapter;
use Burdette\Strategies\VelocityLimitingStrategy;
use Burdette\TokenFactory;

class VelocityLimitingStrategyTest extends \PHPUnit_Framework_TestCase
{
    public function testStrategy()
    {
        $path = __DIR__ . '/_storage/';
        if (!is_dir($path)) {
            mkdir($path);
        }
        $storage  = new FileStorageAdapter($path);
        $bucketFactory = new BucketFactory(new TokenFactory());
        $repo     = new BucketRepository($storage, $bucketFactory);
        $strategy = new VelocityLimitingStrategy($repo);
        $strategy->setVelocity(5, 1, true);
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
        $this->assertEquals(1, $token->getAvailable());

        unlink($path . (string) $identity);
        rmdir($path);
    }
}
