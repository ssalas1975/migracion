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

namespace Vocento\Infrastructure\Client\Http;

use Http\Client\HttpAsyncClient;
use Http\Discovery\HttpAsyncClientDiscovery;
use Http\Discovery\MessageFactoryDiscovery;
use Http\Message\MessageFactory;
use Http\Promise\FulfilledPromise;
use Http\Promise\Promise;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;
use Psr\SimpleCache\CacheInterface;
use Vocento\Infrastructure\Client\Http\Exceptions\ObjectNotFoundException;
use Vocento\Infrastructure\Client\Http\Exceptions\ServiceAccessException;
use Vocento\Infrastructure\Client\Http\Exceptions\UnexpectedServiceResponseException;
use Vocento\Infrastructure\Client\Http\Exceptions\UnreachableServiceException;
use Vocento\RequestId;

/**
 * Class ServiceClient.
 */
abstract class ServiceClient
{
    /** @var string */
    private $host;

    /** @var HttpAsyncClient */
    private $client;

    /** @var MessageFactory */
    private $messageFactory;

    /** @var string|null */
    private $serviceName;

    /** @var string|null */
    private $serviceVersion;

    /** @var RequestId|null */
    private $globalRequestId;

    /** @var array */
    private $globalHeaders;

    /** @var LoggerInterface|null */
    protected $logger;

    /** @var CacheInterface|null */
    private $cache;

    /**
     * ServiceClient constructor.
     *
     * @param string               $host
     * @param string|null          $serviceName
     * @param string|null          $serviceVersion
     * @param HttpAsyncClient|null $client
     * @param MessageFactory|null  $messageFactory
     * @param RequestId|null       $globalRequestId
     * @param array                $globalHeaders
     * @param LoggerInterface|null $logger
     * @param CacheInterface|null  $cache
     */
    public function __construct(
        string $host,
        ?string $serviceName = null,
        ?string $serviceVersion = null,
        HttpAsyncClient $client = null,
        MessageFactory $messageFactory = null,
        RequestId $globalRequestId = null,
        array $globalHeaders = [],
        LoggerInterface $logger = null,
        CacheInterface $cache = null
    ) {
        $this->host = $host;
        $this->serviceName = $serviceName;
        $this->serviceVersion = $serviceVersion;

        $this->client = $client ?: HttpAsyncClientDiscovery::find();
        $this->messageFactory = $messageFactory ?: MessageFactoryDiscovery::find();

        $this->globalRequestId = $globalRequestId;
        $this->globalHeaders = $globalHeaders;

        $this->logger = $logger;
        $this->cache = $cache;
    }

    /**
     * @param RequestInterface       $request
     * @param bool                   $async
     * @param bool                   $cached
     * @param int|\DateInterval|null $cacheTtl
     *
     * @return Promise|ResponseInterface
     */
    public function execute(RequestInterface $request, bool $async = false, bool $cached = true, $cacheTtl = null)
    {
        $context = [
            'method' => $request->getMethod(),
            'uri' => $request->getUri(),
            'header' => $request->getHeaders(),
            'async' => $async,
        ];

        $this->info('Service client execute request.', $context);

        $isRequestCacheable = $this->isRequestCacheable($request);
        $hash = '';

        if ($isRequestCacheable && $cached) {
            $hash = $this->createRequestHash($request);
            $cachedResponse = $this->getCachedResponse($hash);

            if (null !== $cachedResponse) {
                return $async ? new FulfilledPromise($cachedResponse) : $cachedResponse;
            }
        }

        $validate = $this->validateCallback($request);
        $reject = $this->rejectCallback($request);
        $cache = $this->cacheCallback($hash, $cacheTtl);

        $responsePromise = $this->client
            ->sendAsyncRequest($request)
            ->then($validate, $reject)
            ->then($cache);

        return $async ? $responsePromise : $responsePromise->wait();
    }

    /**
     * @param RequestInterface $request
     *
     * @return \Closure
     */
    private function validateCallback(RequestInterface $request): \Closure
    {
        return $this->createCallback([$this, 'validateResponse'], $request);
    }

    /**
     * @param string                 $hash
     * @param int|\DateInterval|null $cacheTtl
     *
     * @return \Closure
     */
    private function cacheCallback(string $hash, $cacheTtl): \Closure
    {
        return $this->createCallback([$this, 'cacheResponse'], $hash, $cacheTtl);
    }

    /**
     * @param RequestInterface $request
     *
     * @return \Closure
     */
    private function rejectCallback(RequestInterface $request): \Closure
    {
        return $this->createCallback([$this, 'rejectRequest'], $request);
    }

    /**
     * @param callable $func
     * @param array    ...$params
     *
     * @return \Closure
     */
    private function createCallback(callable $func, ...$params): callable
    {
        /**
         * @param mixed $element
         *
         * @return mixed
         */
        $callback = static function ($element) use ($func, $params) {
            return $func($element, ...$params);
        };

        return $callback;
    }

    /**
     * @param \Exception       $exception
     * @param RequestInterface $request
     *
     * @throws UnreachableServiceException
     */
    private function rejectRequest(\Exception $exception, RequestInterface $request): void
    {
        $context = ['exception', $exception];
        $this->error('An error occurred before getting a response.', $context);

        $message = $exception->getMessage();

        throw new UnreachableServiceException($message, $request, $exception);
    }

    /**
     * @param string $hash
     *
     * @return ResponseInterface|null
     */
    private function getCachedResponse(string $hash): ?ResponseInterface
    {
        $cache = $this->cache();

        if (empty($hash) || null === $cache || !$cache->has($hash)) {
            return null;
        }

        $messageFactory = $this->messageFactory();
        $cachedContent = $cache->get($hash);

        return $messageFactory->createResponse(200, null, [], $cachedContent);
    }

    /**
     * @param RequestInterface $request
     *
     * @return bool
     */
    private function isRequestCacheable(RequestInterface $request): bool
    {
        $cache = $this->cache();

        if (null === $cache) {
            return false;
        }

        $requestMethod = $request->getMethod();

        return 'GET' === \strtoupper($requestMethod);
    }

    /**
     * @param ResponseInterface      $response
     * @param string                 $hash
     * @param int|\DateInterval|null $cacheTtl
     *
     * @return ResponseInterface
     */
    private function cacheResponse(ResponseInterface $response, string $hash, $cacheTtl): ResponseInterface
    {
        $cache = $this->cache();

        if (empty($hash) || null === $cache) {
            return $response;
        }

        $body = $response->getBody();
        $contents = $body->getContents();

        $cache->set($hash, $contents, $cacheTtl);

        $body->rewind();

        return $response;
    }

    /**
     * @param string $message
     * @param array  $context
     */
    protected function info(string $message, array $context = []): void
    {
        if ($this->logger) {
            $this->logger->info($message, $context);
        }
    }

    /**
     * @return CacheInterface|null
     */
    protected function cache(): ?CacheInterface
    {
        return $this->cache;
    }

    /**
     * @return MessageFactory
     */
    protected function messageFactory(): MessageFactory
    {
        return $this->messageFactory;
    }

    /**
     * @param ResponseInterface $response
     * @param RequestInterface  $request
     *
     * @return ResponseInterface
     *
     * @throws ObjectNotFoundException
     * @throws ServiceAccessException
     * @throws UnexpectedServiceResponseException
     */
    protected function validateResponse(ResponseInterface $response, RequestInterface $request): ResponseInterface
    {
        $statusCode = $response->getStatusCode();

        if (404 === $statusCode) {
            throw new ObjectNotFoundException($request, $response);
        }

        if (401 === $statusCode) {
            throw new ServiceAccessException($request, $response);
        }

        if ($statusCode < 200 || $statusCode >= 400) {
            throw new UnexpectedServiceResponseException('Unexpected service response exception', $request, $response);
        }

        return $response;
    }

    /**
     * @param string $message
     * @param array  $context
     */
    protected function error(string $message, array $context = []): void
    {
        if ($this->logger) {
            $this->logger->error($message, $context);
        }
    }

    /**
     * @param string         $method
     * @param string         $uri
     * @param array          $headers
     * @param string|null    $body
     * @param string         $protocolVersion
     * @param RequestId|null $requestId
     *
     * @return RequestInterface
     */
    public function createRequest(
        string $method,
        string $uri,
        array $headers = [],
        ?string $body = null,
        string $protocolVersion = '1.1',
        RequestId $requestId = null
    ): RequestInterface {
        $messageFactory = $this->messageFactory();
        $headers = $this->buildHeaders($headers, $requestId);

        return $messageFactory->createRequest($method, $uri, $headers, $body, $protocolVersion);
    }

    /**
     * @param array          $headers
     * @param RequestId|null $requestId
     *
     * @return array
     */
    private function buildHeaders(array $headers = [], RequestId $requestId = null): array
    {
        $globalHeader = $this->globalHeaders();

        $defaultHeaders = [
            'Content-Type' => 'application/json',
        ];

        $acceptHeader = $this->buildAcceptHeader();
        $requestIdHeader = $this->buildRequestIdHeader($requestId);

        return \array_merge($globalHeader, $defaultHeaders, $acceptHeader, $requestIdHeader, $headers);
    }

    /**
     * @return array
     */
    public function globalHeaders(): array
    {
        return $this->globalHeaders;
    }

    /**
     * @return array
     */
    private function buildAcceptHeader(): array
    {
        $isService = $this->serviceName && $this->serviceVersion;
        $variant = $isService ? 'vnd.'.$this->serviceName.'.v'.$this->serviceVersion.'+' : '';
        $accept = 'application/'.$variant.'json';

        return [
            'Accept' => $accept,
        ];
    }

    /**
     * @param RequestId|null $requestId
     *
     * @return array<string, string>
     */
    private function buildRequestIdHeader(RequestId $requestId = null): array
    {
        /** @var RequestId|null $requestId */
        $requestId = $requestId ?: $this->globalRequestId();

        return [
            'request-id' => $requestId ? $requestId->getId() : '',
        ];
    }

    /**
     * @return RequestId|null
     */
    public function globalRequestId(): ?RequestId
    {
        return $this->globalRequestId;
    }

    /**
     * @return string|null
     */
    public function serviceName(): ?string
    {
        return $this->serviceName;
    }

    /**
     * @return string|null
     */
    public function serviceVersion(): ?string
    {
        return $this->serviceVersion;
    }

    /**
     * @return HttpAsyncClient
     */
    protected function client(): HttpAsyncClient
    {
        return $this->client;
    }

    /**
     * @param string $path
     * @param array  $parameters
     *
     * @return string
     */
    protected function url(string $path, array $parameters = []): string
    {
        $host = $this->host();

        $queryString = \http_build_query($parameters);
        $query = $queryString ? '?'.$queryString : '';

        return $host.$path.$query;
    }

    /**
     * @return string
     */
    protected function host(): string
    {
        return $this->host;
    }

    /**
     * @param RequestInterface $request
     *
     * @return string
     */
    protected function createRequestHash(RequestInterface $request): string
    {
        $method = $request->getMethod();

        $uri = (string) $request->getUri();

        $headers = $request->getHeaders();
        $hostHeader = $headers['Host'][0] ?? '';
        $data = $method.':'.$uri.':'.$hostHeader;

        return \hash('sha256', $data);
    }
}
