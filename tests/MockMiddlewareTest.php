<?php declare(strict_types=1);

namespace ApiClients\Tests\Middleware\Mock;

use ApiClients\Middleware\Mock\MockMiddleware;
use ApiClients\Middleware\Mock\PathMock;
use ApiClients\Tools\Psr7\HttpStatusExceptions\InternalServerErrorException;
use ApiClients\Tools\TestUtilities\TestCase;
use function Clue\React\Block\await;
use React\EventLoop\Factory;
use RingCentral\Psr7\Request;
use RingCentral\Psr7\Response;

/**
 * @internal
 */
class MockMiddlewareTest extends TestCase
{
    public function testNoMatchingMock(): void
    {
        self::expectException(InternalServerErrorException::class);
        self::expectExceptionCode(500);

        $middleware = new MockMiddleware();
        $request = new Request('GET', 'https://example.com', [], '');

        await($middleware->pre($request, 'abc'), Factory::create());
    }

    public function testMatchingMock(): void
    {
        $middleware = new MockMiddleware(
            new PathMock(
                '',
                new Response(
                    200,
                    [
                        'Foo' => 'Bar',
                    ]
                )
            )
        );
        $request = new Request('GET', 'https://example.com', [], '');

        $modifiedRequest = await($middleware->pre($request, 'abc'), Factory::create());
        self::assertSame(
            [
                'Foo' => ['Bar'],
            ],
            $modifiedRequest->getHeaders()
        );
    }
}
