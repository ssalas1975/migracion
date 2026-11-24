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

namespace Vocento\Tests\Infrastructure\Client\Http;

use Http\Client\HttpAsyncClient;
use Http\Client\HttpClient;
use Http\Message\MessageFactory;
use Http\Promise\FulfilledPromise;
use Http\Promise\Promise;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;
use Psr\SimpleCache\CacheInterface;
use Vocento\Infrastructure\Client\Http\ServiceClient;

/**
 * Class ServiceClientTest.
 *
 * @covers \Vocento\Infrastructure\Client\Http\ServiceClient
 * @covers \Vocento\Infrastructure\Client\Http\Exceptions\ObjectNotFoundException
 * @covers \Vocento\Infrastructure\Client\Http\Exceptions\ServiceAccessException
 * @covers \Vocento\Infrastructure\Client\Http\Exceptions\UnexpectedServiceResponseException
 * @covers \Vocento\Infrastructure\Client\Http\Exceptions\UnreachableServiceException
 */
class ServiceClientTest extends TestCase
{
    /**
     * test.
     */
    public function validDataShouldCreateServiceClient(): void
    {
        $this->getServiceClientMock('http://localhost', 'service', '1');
    }

    /**
     * @param string                     $host
     * @param string                     $serviceName
     * @param string                     $serviceVersion
     * @param HttpAsyncClient|MockObject $client
     * @param MessageFactory|MockObject  $messageFactory
     * @param LoggerInterface|MockObject $logger
     * @param CacheInterface|MockObject  $cache
     *
     * @return MockObject
     *
     * @throws \ReflectionException
     */
    private function getServiceClientMock(
        string $host,
        string $serviceName,
        string $serviceVersion,
        $client = null,
        $messageFactory = null,
        $logger = null,
        $cache = null
    ): MockObject {
        return $this->getMockForAbstractClass(
            ServiceClient::class,
            [$host, $serviceName, $serviceVersion, $client, $messageFactory, null, [], $logger, $cache]
        );
    }

    /**
     * @test
     */
    public function getServiceData(): void
    {
        /** @var ServiceClient $serviceClient */
        $serviceClient = $this->getServiceClientMock('http://localhost', 'service-name', '2');

        self::assertSame('service-name', $serviceClient->serviceName());
        self::assertSame('2', $serviceClient->serviceVersion());
    }

    /**
     * @test
     * @expectedException \Vocento\Infrastructure\Client\Http\Exceptions\ObjectNotFoundException
     */
    public function validateResponseShouldThrowExceptionWithStatusCode404(): void
    {
        $requestMock = $this->getRequestMock();

        $responseMock = $this->getResponseMock();
        $responseMock->expects(self::any())
            ->method('getStatusCode')
            ->willReturn(404);

        $serviceClient = $this->getServiceClientMock('http://localhost', 'service-name', '2');

        $method = new \ReflectionMethod(ServiceClient::class, 'validateResponse');
        $method->setAccessible(true);
        $method->invoke($serviceClient, $responseMock, $requestMock);
    }

    /**
     * @return MockObject
     */
    private function getRequestMock(): MockObject
    {
        return $this->createMock(RequestInterface::class);
    }

    /**
     * @return MockObject
     */
    private function getResponseMock(): MockObject
    {
        return $this->createMock(ResponseInterface::class);
    }

    /**
     * @test
     */
    public function infoAndErrorMessageShouldBeCalledWhenLoggerIsAvailable(): void
    {
        $loggerMock = $this->createMock(LoggerInterface::class);
        $loggerMock->expects(self::once())
            ->method('info');
        $loggerMock->expects(self::once())
            ->method('error');

        $serviceClient = $this->getServiceClientMock(
            'http://localhost',
            'service-name',
            '2',
            null,
            null,
            $loggerMock
        );

        $method = new \ReflectionMethod(ServiceClient::class, 'info');
        $method->setAccessible(true);
        $method->invoke($serviceClient, 'Message');

        $method = new \ReflectionMethod(ServiceClient::class, 'error');
        $method->setAccessible(true);
        $method->invoke($serviceClient, 'Message');
    }

    /**
     * test.
     */
    public function validateResponseShouldNotThrowExceptionWithStatusCode200(): void
    {
        $requestMock = $this->getRequestMock();

        $responseMock = $this->getResponseMock();
        $responseMock->expects(self::any())
            ->method('getStatusCode')
            ->willReturn(200);

        $serviceClient = $this->getServiceClientMock('http://localhost', 'service-name', '2');

        $method = new \ReflectionMethod(ServiceClient::class, 'validateResponse');
        $method->setAccessible(true);
        $method->invoke($serviceClient, $responseMock, $requestMock);
    }

    /**
     * @test
     * @expectedException \Vocento\Infrastructure\Client\Http\Exceptions\ServiceAccessException
     */
    public function validateResponseShouldThrowExceptionWithStatusCode401(): void
    {
        $requestMock = $this->getRequestMock();

        $responseMock = $this->getResponseMock();
        $responseMock->expects(self::any())
            ->method('getStatusCode')
            ->willReturn(401);

        $serviceClient = $this->getServiceClientMock('http://localhost', 'service-name', '2');

        $method = new \ReflectionMethod(ServiceClient::class, 'validateResponse');
        $method->setAccessible(true);
        $method->invoke($serviceClient, $responseMock, $requestMock);
    }

    /**
     * @test
     * @expectedException \Vocento\Infrastructure\Client\Http\Exceptions\UnexpectedServiceResponseException
     */
    public function validateResponseShouldThrowExceptionWithUnknownStatus(): void
    {
        $requestMock = $this->getRequestMock();

        $responseMock = $this->getResponseMock();
        $responseMock->expects(self::any())
            ->method('getStatusCode')
            ->willReturn(500);

        $serviceClient = $this->getServiceClientMock('http://localhost', 'service-name', '2');

        $method = new \ReflectionMethod(ServiceClient::class, 'validateResponse');
        $method->setAccessible(true);
        $method->invoke($serviceClient, $responseMock, $requestMock);
    }

    /**
     * @test
     * @dataProvider buildHeadersData
     *
     * @param string $serviceName
     * @param string $serviceVersion
     * @param array  $expectedHeaders
     *
     * @throws \ReflectionException
     */
    public function buildHeadersShouldReturnArrayWithHeaders(string $serviceName, string $serviceVersion, array $expectedHeaders): void
    {
        $client = $this->getServiceClientMock('http://localhost', $serviceName, $serviceVersion);

        $method = new \ReflectionMethod(ServiceClient::class, 'buildHeaders');
        $method->setAccessible(true);

        $result = $method->invoke($client);

        self::assertIsArray($result);

        \ksort($result);

        self::assertSame($expectedHeaders, $result);
    }

    /**
     * @test
     */
    public function createRequestShouldReturnCallMessageFactoryMethod(): void
    {
        $messageFactory = $this->getMockBuilder(MessageFactory::class)
            ->getMock();
        $messageFactory->expects(self::once())
            ->method('createRequest')
            ->willReturn($this->getMockBuilder(RequestInterface::class)->getMock());

        $client = $this->getServiceClientMock('http://localhost', '', '1', null, $messageFactory);

        $method = new \ReflectionMethod(
            ServiceClient::class, 'createRequest'
        );
        $method->setAccessible(true);

        self::assertInstanceOf(RequestInterface::class, $method->invoke($client, 'GET', ''));
    }

    /**
     * @test
     */
    public function clientShouldReturnHttpClient(): void
    {
        $client = $this->getServiceClientMock('http://localhost', '', '1');

        $method = new \ReflectionMethod(
            ServiceClient::class, 'client'
        );
        $method->setAccessible(true);

        self::assertInstanceOf(HttpClient::class, $method->invoke($client));
    }

    /**
     * @test
     * @dataProvider urlData
     *
     * @param string $host
     * @param string $path
     * @param array  $parameters
     * @param string $expectedUrl
     */
    public function urlShouldReturnValidUrl(string $host, string $path, array $parameters, string $expectedUrl): void
    {
        $client = $this->getServiceClientMock($host, '', '1');

        $method = new \ReflectionMethod(
            ServiceClient::class, 'url'
        );
        $method->setAccessible(true);

        self::assertSame($expectedUrl, $method->invoke($client, $path, $parameters));
    }

    /**
     * @test
     */
    public function messageFactoryShouldReturnMessageFactory(): void
    {
        $client = $this->getServiceClientMock('http://localhost', '', '1');

        $method = new \ReflectionMethod(
            ServiceClient::class, 'messageFactory'
        );
        $method->setAccessible(true);

        self::assertInstanceOf(MessageFactory::class, $method->invoke($client));
    }

    /**
     * test.
     */
    public function executeShouldReturnPromiseWhenUsingAsync(): void
    {
        $request = $this->getRequestMock();
        $response = $this->getResponseMock();
        $response->expects(self::any())
            ->method('getStatusCode')
            ->willReturn(200);
        $promise = $this->getPromiseMock($response);

        $clientMock = $this->getHttpAsyncClientMock();
        $clientMock->expects(self::once())
            ->method('sendAsyncRequest')
            ->willReturn($promise);

        $serviceClient = $this->getServiceClientMock('http://localhost', '', '1', $clientMock);

        $method = new \ReflectionMethod(ServiceClient::class, 'execute');
        $method->setAccessible(true);

        $methodResult = $method->invoke($serviceClient, $request);

        self::assertInstanceOf(Promise::class, $methodResult);
    }

    /**
     * @param MockObject|null $result
     *
     * @return FulfilledPromise
     */
    private function getPromiseMock(?MockObject $result = null): FulfilledPromise
    {
        return new FulfilledPromise($result);
    }

    /**
     * @return MockObject
     */
    private function getHttpAsyncClientMock(): MockObject
    {
        return $this->createMock(HttpAsyncClient::class);
    }

    /**
     * @test
     */
    public function executeShouldReturnResponseInterfaceWhenUsingSync(): void
    {
        $request = $this->getRequestMock();
        $response = $this->getResponseMock();
        $response->expects(self::any())
            ->method('getStatusCode')
            ->willReturn(200);
        $promise = $this->getPromiseMock($response);

        $clientMock = $this->getHttpAsyncClientMock();
        $clientMock->expects(self::once())
            ->method('sendAsyncRequest')
            ->willReturn($promise);

        $serviceClient = $this->getServiceClientMock('http://localhost', '', '1', $clientMock);

        $method = new \ReflectionMethod(ServiceClient::class, 'execute');
        $method->setAccessible(true);

        self::assertInstanceOf(ResponseInterface::class, $method->invoke($serviceClient, $request));
    }

    /**
     * @test
     */
    public function executeShouldReturnCachedResponse(): void
    {
        $request = $this->getRequestMock();
        $request->expects(self::any())
            ->method('getMethod')
            ->willReturn('GET');
        $clientMock = $this->getHttpAsyncClientMock();
        $cacheMock = $this->getCacheMock();
        $cacheMock->expects(self::once())
            ->method('has')
            ->willReturn(true);
        $cacheMock->expects(self::once())
            ->method('get')
            ->willReturn('some content');

        $serviceClient = $this->getServiceClientMock('http://localhost', '', '1', $clientMock, null, null, $cacheMock);

        $method = new \ReflectionMethod(ServiceClient::class, 'execute');
        $method->setAccessible(true);

        $response = $method->invoke($serviceClient, $request);

        self::assertInstanceOf(ResponseInterface::class, $response);
        self::assertSame('some content', $response->getBody()->getContents());
    }

    /**
     * test.
     */
    public function executeAsyncShouldReturnCachedResponse(): void
    {
        $request = $this->getRequestMock();
        $request->expects(self::any())
            ->method('getMethod')
            ->willReturn('GET');
        $clientMock = $this->getHttpAsyncClientMock();
        $cacheMock = $this->getCacheMock();
        $cacheMock->expects(self::once())
            ->method('has')
            ->willReturn(true);
        $cacheMock->expects(self::once())
            ->method('get')
            ->willReturn('some content');

        $serviceClient = $this->getServiceClientMock('http://localhost', '', '1', $clientMock, null, null, $cacheMock);

        $method = new \ReflectionMethod(ServiceClient::class, 'execute');
        $method->setAccessible(true);

        $promise = $method->invoke($serviceClient, $request);

        self::assertInstanceOf(Promise::class, $promise);
        self::assertInstanceOf(ResponseInterface::class, $promise->wait());
        self::assertSame('some content', $promise->wait()->getBody()->getContents());
    }

    /**
     * @return MockObject
     */
    private function getCacheMock(): MockObject
    {
        return $this->createMock(CacheInterface::class);
    }

    /**
     * @return array
     */
    public function urlData(): array
    {
        return [
            ['http://localhost', '/test', [], 'http://localhost/test'],
            [
                'http://localhost',
                '/test',
                ['param1' => 'value1', 'param2' => 'value2'],
                'http://localhost/test?param1=value1&param2=value2',
            ],
        ];
    }

    /**
     * @return array
     */
    public function buildHeadersData(): array
    {
        return [
            [
                'service',
                1,
                [
                    'Accept' => 'application/vnd.service.v1+json',
                    'Content-Type' => 'application/json',
                    'request-id' => '',
                ],
            ],
            [
                '',
                2,
                [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                    'request-id' => '',
                ],
            ],
        ];
    }
}
