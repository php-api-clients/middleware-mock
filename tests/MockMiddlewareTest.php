<?php declare(strict_types=1);

namespace ApiClients\Tests\Middleware\Mock;

use ApiClients\Middleware\Mock\MockMiddleware;
use ApiClients\Middleware\Mock\PathMock;
use ApiClients\Tools\TestUtilities\TestCase;
use function Clue\React\Block\await;
use Psr\Http\Message\ResponseInterface;
use React\EventLoop\Factory;
use function React\Promise\resolve;
use RingCentral\Psr7\Request;
use RingCentral\Psr7\Response;

/**
 * @internal
 */
class MockMiddlewareTest extends TestCase
{
    public function testNoMatchingMock(): void
    {
        $middleware = new MockMiddleware();
        $request = new Request('GET', 'https://example.com', [], '');

        /** @var ResponseInterface $response */
        $response = await($middleware->pre($request, 'abc')->otherwise(function (ResponseInterface $response) {
            return resolve($response);
        }), Factory::create());

        self::assertSame(500, $response->getStatusCode());
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

        $modifiedRequest = await($middleware->pre($request, 'abc')->otherwise(function (ResponseInterface $response) {
            return resolve($response);
        }), Factory::create());

        self::assertSame(
            [
                'Foo' => ['Bar'],
            ],
            $modifiedRequest->getHeaders()
        );
    }
}
