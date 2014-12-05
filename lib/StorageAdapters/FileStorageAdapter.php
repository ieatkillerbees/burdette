<?php
/**
 * This file is part of the Burdette package.
 *
 * @copyright © Samantha Quiñones & Patryk Kruk, All Rights Reserved
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Burdette\StorageAdapters;

use Burdette\BucketInterface;
use Burdette\IdentityInterface;
use Burdette\StorageAdapterInterface;

/**
 * Class FileStorageAdapter
 *
 * Adapter for storing rate limit buckets in files. Probably not performant or secure. Think several million times
 * before using in production!
 *
 * @author Samantha Quiñones <samantha@tembies.com>
 * @package Burdette\StorageAdapters
 * @codeCoverageIgnore
 */
class FileStorageAdapter implements StorageAdapterInterface
{
    /** @var string A writable directory where buckets will be stored */
    private $path;

    /**
     * @param string $path
     */
    public function __construct($path)
    {
        $this->setPath($path);
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param string $path
     */
    public function setPath($path)
    {
        if (!(is_dir($path) && is_writable($path))) {
            throw new \RuntimeException("$path is not a directory or is not writable");
        }
        $this->path = $path;
    }

    /**
     * @param IdentityInterface $identity
     * @return BucketInterface|false
     */
    public function get(IdentityInterface $identity)
    {
        $file = $this->path . DIRECTORY_SEPARATOR . (string)$identity;
        if (!file_exists($file)) {
            return false;
        }
        $bucket = unserialize(file_get_contents($file));
        if (!$bucket instanceof BucketInterface) {
            throw new \RuntimeException("Stored $bucket instance is invalid or corrupt");
        }
        return $bucket;
    }

    /**
     * @param  BucketInterface $bucket
     * @return bool
     */
    public function save(BucketInterface $bucket)
    {
        $file = $this->path . DIRECTORY_SEPARATOR . (string)$bucket->getIdentity();

        file_put_contents($file, serialize($bucket));

        return true;
    }

    /**
     * @param  BucketInterface $bucket
     * @return bool
     */
    public function delete(BucketInterface $bucket)
    {
        $file = $this->path . DIRECTORY_SEPARATOR . (string)$bucket->getIdentity();
        unlink($file);
        return true;
    }
}
