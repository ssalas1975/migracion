<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Vocento\Symfony\Component\Cache\Tests\Adapter;

use Vocento\Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Vocento\Symfony\Component\Cache\Adapter\ArrayAdapter;
use Vocento\Symfony\Component\Cache\Adapter\ChainAdapter;
use Vocento\Symfony\Component\Cache\Tests\Fixtures\ExternalAdapter;

/**
 * @author Kévin Dunglas <dunglas@gmail.com>
 * @group time-sensitive
 */
class ChainAdapterTest extends AdapterTestCase
{
    public function createCachePool($defaultLifetime = 0)
    {
        return new ChainAdapter(array(new ArrayAdapter($defaultLifetime), new ExternalAdapter(), new FilesystemAdapter('', $defaultLifetime)), $defaultLifetime);
    }

    /**
     * @expectedException \Symfony\Component\Cache\Exception\InvalidArgumentException
     * @expectedExceptionMessage At least one adapter must be specified.
     */
    public function testEmptyAdaptersException()
    {
        new ChainAdapter(array());
    }

    /**
     * @expectedException \Symfony\Component\Cache\Exception\InvalidArgumentException
     * @expectedExceptionMessage The class "stdClass" does not implement
     */
    public function testInvalidAdapterException()
    {
        new ChainAdapter(array(new \stdClass()));
    }
}
