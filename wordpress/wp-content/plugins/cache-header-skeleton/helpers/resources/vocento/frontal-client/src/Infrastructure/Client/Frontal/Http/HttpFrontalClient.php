<?php
/*
 * This file is part of the Vocento Software.
 *
 * (c) Vocento S.A., <desarrollo.dts@vocento.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace Vocento\Infrastructure\Client\Frontal\Http;

use Http\Client\HttpAsyncClient;
use Http\Message\MessageFactory;
use Http\Promise\Promise;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;
use Psr\SimpleCache\CacheInterface;
use Vocento\Infrastructure\Client\Http\ServiceClient;
use Vocento\RequestId;

/**
 * @author David Zapatero <dzapatero@vocento.com>
 * @author Hector Prats <hprats@vocento.com>
 */
class HttpFrontalClient extends ServiceClient
{
    /**
     * HttpFrontalClient constructor.
     *
     * @param string               $host
     * @param HttpAsyncClient|null $client
     * @param MessageFactory|null  $messageFactory
     * @param RequestId|null       $globalRequestId
     * @param array                $globalHeaders
     * @param LoggerInterface|null $logger
     * @param CacheInterface|null  $cache
     */
    public function __construct(
        $host,
        HttpAsyncClient $client = null,
        MessageFactory $messageFactory = null,
        RequestId $globalRequestId = null,
        array $globalHeaders = [],
        LoggerInterface $logger = null,
        CacheInterface $cache = null
    ) {
        parent::__construct(
            $host,
            '',
            '',
            $client,
            $messageFactory,
            $globalRequestId,
            $globalHeaders,
            $logger,
            $cache
        );
    }

    /**
     * @param string         $host
     * @param string         $type
     * @param string         $section
     * @param array          $headers
     * @param RequestId|null $requestId
     * @param array          $params
     *
     * @return mixed
     *
     * @throws \Exception
     */
    public function getTwigLayout($host, $type = 'portal', $section = '/', array $headers = [], RequestId $requestId = null, array $params = [])
    {
        return $this->getAsyncTwigLayout($host, $type, $section, $headers, $requestId, $params)->wait();
    }

    /**
     * @param string         $host
     * @param string         $type
     * @param string         $section
     * @param array          $headers
     * @param RequestId|null $requestId
     * @param array          $params
     *
     * @return Promise|ResponseInterface
     *
     * @throws \Exception
     */
    public function getAsyncTwigLayout($host, $type = 'portal', $section = '/', array $headers = [], RequestId $requestId = null, array $params = [])
    {
        return $this->getAsyncLayout($host, '/api/headers/skeleton.twig', $type, $section, $headers, $requestId, $params);
    }

    /**
     * @param string         $host
     * @param string         $path
     * @param string         $type
     * @param string         $section
     * @param array          $headers
     * @param RequestId|null $requestId
     * @param array          $params
     *
     * @return Promise|ResponseInterface
     *
     * @throws \Exception
     */
    public function getAsyncLayout($host, $path, $type = 'portal', $section = '/', array $headers = [], RequestId $requestId = null, array $params = [])
    {
        $headers = \array_merge(
            [
                'Host' => $host,
                'Accept' => 'text/html',
                'Content-Type' => 'text/html',
                'User-agent' => 'PPLL frontal client',
            ],
            $headers
        );

        if (null !== $this->logger) {
            $this->logger->info('get layout '.$this->url($path, \array_merge(['type' => $type, 'section' => $section], $params)));
        }

        $request = $this->createRequest(
            'GET',
            $this->url($path, \array_merge(['type' => $type, 'section' => $section], $params)),
            $headers,
            null,
            '1.1',
            $requestId
        );
        $promise = $this->execute($request, true);

        return $promise;
    }

    /**
     * @param string         $host
     * @param string         $type
     * @param string         $section
     * @param array          $headers
     * @param RequestId|null $requestId
     * @param array          $params
     *
     * @return mixed
     *
     * @throws \Exception
     */
    public function getHtmlLayout($host, $type = 'portal', $section = '/', array $headers = [], RequestId $requestId = null, array $params = [])
    {
        return $this->getAsyncHtmlLayout($host, $type, $section, $headers, $requestId, $params)->wait();
    }

    /**
     * @param string         $host
     * @param string         $type
     * @param string         $section
     * @param array          $headers
     * @param RequestId|null $requestId
     * @param array          $params
     *
     * @return Promise|ResponseInterface
     *
     * @throws \Exception
     */
    public function getAsyncHtmlLayout($host, $type = 'portal', $section = '/', array $headers = [], RequestId $requestId = null, array $params = [])
    {
        return $this->getAsyncLayout($host, '/api/headers/skeleton.html', $type, $section, $headers, $requestId, $params);
    }

    /**
     * @param string         $host
     * @param string         $path
     * @param array          $headers
     * @param RequestId|null $requestId
     * @param array          $params
     *
     * @return Promise|ResponseInterface
     *
     * @throws \Exception
     */
    public function getHtmlFrontal($host, $path, array $headers = [], RequestId $requestId = null, array $params = [])
    {
        return $this->getAsyncFrontal($host, $path, $headers, $requestId, $params)->wait();
    }

    /**
     * @param string         $host
     * @param string         $path
     * @param array          $headers
     * @param RequestId|null $requestId
     * @param array          $params
     *
     * @return Promise|ResponseInterface
     *
     * @throws \Exception
     */
    public function getAsyncFrontal($host, $path = '/', array $headers = [], RequestId $requestId = null, array $params = [])
    {
        $headers = \array_merge(
            [
                'Host' => $host,
                'Accept' => 'text/html',
                'Content-Type' => 'text/html',
                'User-agent' => 'PPLL frontal client',
            ],
            $headers
        );

        if (null !== $this->logger) {
            $this->logger->info('get frontal '.$this->url($path, $params));
        }

        $request = $this->createRequest(
            'GET',
            $this->url($path, $params),
            $headers,
            null,
            '1.1',
            $requestId
        );

        return $this->execute($request, true);
    }
}
