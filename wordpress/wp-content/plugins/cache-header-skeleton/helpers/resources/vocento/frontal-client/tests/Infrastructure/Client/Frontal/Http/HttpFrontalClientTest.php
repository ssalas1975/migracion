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
use Http\Message\MessageFactory;
use Http\Promise\Promise;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;
use Vocento\Http\Mock\Client;
use Vocento\Infrastructure\Client\Frontal\Http\HttpFrontalClient;
use Vocento\RequestId;

/**
 * @author David Zapatero <dzapatero@vocento.com>
 */
class HttpFrontalClientTest extends TestCase
{
    /**
     * @test
     * @dataProvider validConstructArguments
     *
     * @covers       \Vocento\Infrastructure\Client\Frontal\Http\HttpFrontalClient::__construct()
     *
     * @param $host
     * @param $client
     * @param $messageFactory
     * @param $globalRequestId
     * @param $globalHeaders
     */
    public function createShouldReturnHttpPPLLFrontalClient(
        $host,
        HttpAsyncClient $client = null,
        MessageFactory $messageFactory = null,
        RequestId $globalRequestId = null,
        array $globalHeaders = []
    ) {
        $client = new HttpFrontalClient($host, $client, $messageFactory, $globalRequestId, $globalHeaders);
        self::assertInstanceOf(HttpFrontalClient::class, $client);
    }

    /**
     * @return array
     */
    public function validConstructArguments()
    {
        $client = self::createMock(HttpAsyncClient::class);
        $messageFactory = self::createMock(MessageFactory::class);
        $globalRequestId = RequestId::create();

        return [
            ['http://des-api-frontal-ppll.srv.vocento.in'],
            ['http://des-api-frontal-ppll.srv.vocento.in', $client],
            ['http://des-api-frontal-ppll.srv.vocento.in', null, $messageFactory],
            ['http://des-api-frontal-ppll.srv.vocento.in', null, null, $globalRequestId],
            ['http://des-api-frontal-ppll.srv.vocento.in', null, null, null, []],
            ['http://des-api-frontal-ppll.srv.vocento.in', $client, $messageFactory, $globalRequestId, []],
        ];
    }

    /**
     * @test
     * @covers \Vocento\Infrastructure\Client\Frontal\Http\HttpFrontalClient::getAsyncLayout()
     */
    public function getAsyncLayoutShouldReturnPromise()
    {
        $mockClient = self::createMockClient();
        $mockClient->addResponse(self::createMockResponse(200));

        $client = new HttpFrontalClient('http://des-api-frontal-ppll.srv.vocento.in', $mockClient);
        $promise = $client->getAsyncLayout('des-www.laverdad.es', '/api/headers/skeleton.tiwg', 'portada', 'deportes');

        self::assertInstanceOf(Promise::class, $promise);
    }

    /**
     * @return Client
     */
    private function createMockClient()
    {
        return new Client();
    }

    /**
     * @param int $statusCode
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|ResponseInterface
     */
    private function createMockResponse($statusCode = 200)
    {
        $responseMock = self::createMock(ResponseInterface::class);
        $responseMock->expects(self::atLeastOnce())
            ->method('getStatusCode')
            ->willReturn($statusCode);

        return $responseMock;
    }

    /**
     * @test
     * @covers \Vocento\Infrastructure\Client\Frontal\Http\HttpFrontalClient::getTwigLayout()
     */
    public function getTwigLayoutShouldReturnResponseInterface()
    {
        $requestId = RequestId::create();

        $mockClient = self::createMockClient();
        $mockClient->addResponse(self::createMockResponse(200));

        $messageFactory = self::createMock(MessageFactory::class);
        $messageFactory->expects(self::once())
            ->method('createRequest')
            ->with(
                'GET',
                'http://des-api-frontal-ppll.srv.vocento.in/api/headers/skeleton.twig?type=portada&section=deportes',
                [
                    'Host' => 'des-www.laverdad.es',
                    'Accept' => 'text/html',
                    'Content-Type' => 'text/html',
                    'User-agent' => 'PPLL frontal client',
                    'request-id' => $requestId,
                ],
                '',
                '1.1'
            )
            ->willReturn(self::createMock(RequestInterface::class));

        $client = new HttpFrontalClient('http://des-api-frontal-ppll.srv.vocento.in', $mockClient, $messageFactory);

        $response = $client->getTwigLayout('des-www.laverdad.es', 'portada', 'deportes', [], $requestId);

        self::assertInstanceOf(ResponseInterface::class, $response);
    }

    /**
     * @test
     * @covers \Vocento\Infrastructure\Client\Frontal\Http\HttpFrontalClient::getTwigLayout()
     */
    public function getTwigLayoutWithGETParamsShouldReturnResponseInterface()
    {
        $requestId = RequestId::create();

        $mockClient = self::createMockClient();
        $mockClient->addResponse(self::createMockResponse(200));

        $messageFactory = self::createMock(MessageFactory::class);
        $messageFactory->expects(self::once())
            ->method('createRequest')
            ->with(
                'GET',
                'http://des-api-frontal-ppll.srv.vocento.in/api/headers/skeleton.twig?type=portada&section=deportes&paramGet1=parameter1',
                [
                    'Host' => 'des-www.laverdad.es',
                    'Accept' => 'text/html',
                    'Content-Type' => 'text/html',
                    'User-agent' => 'PPLL frontal client',
                    'request-id' => $requestId,
                ],
                '',
                '1.1'
            )
            ->willReturn(self::createMock(RequestInterface::class));

        $client = new HttpFrontalClient('http://des-api-frontal-ppll.srv.vocento.in', $mockClient, $messageFactory);

        $response = $client->getTwigLayout('des-www.laverdad.es', 'portada', 'deportes', [], $requestId, ['paramGet1' => 'parameter1']);

        self::assertInstanceOf(ResponseInterface::class, $response);
    }

    /**
     * @test
     * @expectedException \Vocento\Infrastructure\Client\Http\Exceptions\ObjectNotFoundException
     */
    public function getTwigLayoutShouldThrowExceptionOnError()
    {
        $mockClient = self::createMockClient();
        $mockClient->addResponse(self::createMockResponse(404));

        $client = new HttpFrontalClient('http://des-api-frontal-ppll.srv.vocento.in', $mockClient);
        $client->getTwigLayout('des-www.laverdad.es', 'portada', 'deportes');
    }

    /**
     * @test
     * @covers \Vocento\Infrastructure\Client\Frontal\Http\HttpFrontalClient::getHtmlLayout()
     */
    public function getHtmlLayoutShouldReturnResponseInterface()
    {
        $requestId = RequestId::create();

        $mockClient = self::createMockClient();
        $mockClient->addResponse(self::createMockResponse(200));

        $messageFactory = self::createMock(MessageFactory::class);
        $messageFactory->expects(self::once())
            ->method('createRequest')
            ->with(
                'GET',
                'http://des-api-frontal-ppll.srv.vocento.in/api/headers/skeleton.html?type=portada&section=deportes',
                [
                    'Host' => 'des-www.laverdad.es',
                    'Accept' => 'text/html',
                    'Content-Type' => 'text/html',
                    'User-agent' => 'PPLL frontal client',
                    'request-id' => $requestId,
                ],
                '',
                '1.1'
            )
            ->willReturn(self::createMock(RequestInterface::class));

        $client = new HttpFrontalClient('http://des-api-frontal-ppll.srv.vocento.in', $mockClient, $messageFactory);

        $response = $client->getHtmlLayout('des-www.laverdad.es', 'portada', 'deportes', [], $requestId);

        self::assertInstanceOf(ResponseInterface::class, $response);
    }

    /**
     * @test
     * @covers \Vocento\Infrastructure\Client\Frontal\Http\HttpFrontalClient::getHtmlLayout()
     */
    public function getHtmlLayoutWithGETParamsShouldReturnResponseInterface()
    {
        $requestId = RequestId::create();

        $mockClient = self::createMockClient();
        $mockClient->addResponse(self::createMockResponse(200));

        $messageFactory = self::createMock(MessageFactory::class);
        $messageFactory->expects(self::once())
            ->method('createRequest')
            ->with(
                'GET',
                'http://des-api-frontal-ppll.srv.vocento.in/api/headers/skeleton.html?type=portada&section=deportes&paramGet1=parameter1',
                [
                    'Host' => 'des-www.laverdad.es',
                    'Accept' => 'text/html',
                    'Content-Type' => 'text/html',
                    'User-agent' => 'PPLL frontal client',
                    'request-id' => $requestId,
                ],
                '',
                '1.1'
            )
            ->willReturn(self::createMock(RequestInterface::class));

        $logger = $this->getMockLogger();

        $client = new HttpFrontalClient('http://des-api-frontal-ppll.srv.vocento.in', $mockClient, $messageFactory, $requestId, [], $logger);

        $response = $client->getHtmlLayout('des-www.laverdad.es', 'portada', 'deportes', [], $requestId, ['paramGet1' => 'parameter1']);

        self::assertInstanceOf(ResponseInterface::class, $response);
    }

    /**
     * @test
     * @expectedException \Vocento\Infrastructure\Client\Http\Exceptions\ObjectNotFoundException
     */
    public function getHtmlLayoutShouldThrowExceptionOnError()
    {
        $mockClient = self::createMockClient();
        $mockClient->addResponse(self::createMockResponse(404));

        $client = new HttpFrontalClient('http://des-api-frontal-ppll.srv.vocento.in', $mockClient);
        $client->getHtmlLayout('des-www.laverdad.es', 'portada', 'deportes');
    }

    /**
     * @test
     * @covers \Vocento\Infrastructure\Client\Frontal\Http\HttpFrontalClient::getHtmlFrontal()
     */
    public function getHtmlFrontalShouldReturnResponseInterface()
    {
        $requestId = RequestId::create();

        $mockClient = self::createMockClient();
        $mockClient->addResponse(self::createMockResponse(200));

        $messageFactory = self::createMock(MessageFactory::class);
        $messageFactory->expects(self::once())
           ->method('createRequest')
           ->with(
               'GET',
               'http://des-api-frontal-ppll.srv.vocento.in/hemerotecapdf/',
               [
                   'Host' => 'des-www.laverdad.es',
                   'Accept' => 'text/html',
                   'Content-Type' => 'text/html',
                   'User-agent' => 'PPLL frontal client',
                   'request-id' => $requestId,
               ],
               '',
               '1.1'
           )
           ->willReturn(self::createMock(RequestInterface::class));

        $client = new HttpFrontalClient('http://des-api-frontal-ppll.srv.vocento.in', $mockClient, $messageFactory);

        $response = $client->getHtmlFrontal('des-www.laverdad.es', '/hemerotecapdf/', [], $requestId, []);

        self::assertInstanceOf(ResponseInterface::class, $response);
    }

    /**
     * @test
     * @covers \Vocento\Infrastructure\Client\Frontal\Http\HttpFrontalClient::getHtmlFrontal()
     */
    public function getHtmlFrontalWithGETParamsShouldReturnResponseInterface()
    {
        $requestId = RequestId::create();

        $mockClient = self::createMockClient();
        $mockClient->addResponse(self::createMockResponse(200));

        $messageFactory = self::createMock(MessageFactory::class);
        $messageFactory->expects(self::once())
           ->method('createRequest')
           ->with(
               'GET',
               'http://des-api-frontal-ppll.srv.vocento.in/hemerotecapdf/?x-vocento-user-type=non_subscriber',
               [
                   'Host' => 'des-www.laverdad.es',
                   'Accept' => 'text/html',
                   'Content-Type' => 'text/html',
                   'User-agent' => 'PPLL frontal client',
                   'request-id' => $requestId,
               ],
               '',
               '1.1'
           )
           ->willReturn(self::createMock(RequestInterface::class));

        $logger = $this->getMockLogger();

        $client = new HttpFrontalClient('http://des-api-frontal-ppll.srv.vocento.in', $mockClient, $messageFactory, null, [], $logger);

        $response = $client->getHtmlFrontal('des-www.laverdad.es', '/hemerotecapdf/', [], $requestId, ['x-vocento-user-type' => 'non_subscriber']);

        self::assertInstanceOf(ResponseInterface::class, $response);
    }

    /**
     * @test
     * @expectedException \Vocento\Infrastructure\Client\Http\Exceptions\ObjectNotFoundException
     */
    public function getHtmlFrontalShouldThrowExceptionOnError()
    {
        $mockClient = self::createMockClient();
        $mockClient->addResponse(self::createMockResponse(404));

        $client = new HttpFrontalClient('http://des-api-frontal-ppll.srv.vocento.in', $mockClient);
        $client->getHtmlFrontal('des-www.laverdad.es', '/hemerotecapdf/', [], null, []);
    }

    /**
     * @return PHPUnit_Framework_MockObject_MockObject|LoggerInterface
     */
    private function getMockLogger()
    {
        /** @var LoggerInterface|PHPUnit_Framework_MockObject_MockObject $logger */
        $logger = $this->createMock(LoggerInterface::class);
        $logger->method('info');

        return $logger;
    }
}
