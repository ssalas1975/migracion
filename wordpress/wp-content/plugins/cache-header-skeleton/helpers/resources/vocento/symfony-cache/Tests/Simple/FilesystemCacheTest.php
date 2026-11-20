<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Vocento\Symfony\Component\Cache\Tests\Simple;

use Vocento\Symfony\Component\Cache\Simple\FilesystemCache;

/**
 * @group time-sensitive
 */
class FilesystemCacheTest extends CacheTestCase
{
    public function createSimpleCache($defaultLifetime = 0)
    {
        return new FilesystemCache('', $defaultLifetime);
    }
}
