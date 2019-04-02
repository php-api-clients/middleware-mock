<?php declare(strict_types=1);

namespace ApiClients\Tests\Middleware\Mock;

use ApiClients\Middleware\Mock\CallableMock;
use ApiClients\Tools\TestUtilities\TestCase;
use Psr\Http\Message\RequestInterface;
use RingCentral\Psr7\Request;
use RingCentral\Psr7\Response;

/**
 * @internal
 */
class CallableMockTest extends TestCase
{
    public function testMatch(): void
    {
        $response = new Response();
        $mock = new CallableMock(function (RequestInterface $request) {
            return $request->getUri()->getPath() === '/path';
        }, $response);
        self::assertTrue($mock->match(new Request('GET', '/path')));
    }

    public function testNoMatch(): void
    {
        $response = new Response();
        $mock = new CallableMock(function (RequestInterface $request) {
            return $request->getUri()->getPath() === '/';
        }, $response);
        self::assertFalse($mock->match(new Request('GET', '/path')));
    }

    public function testResponse(): void
    {
        $response = new Response();
        $mock = new CallableMock(function (RequestInterface $request) {
            return $request->getUri()->getPath() === '/path';
        }, $response);
        self::assertSame($response, $mock->response(new Request('GET', '/path')));
    }
}
