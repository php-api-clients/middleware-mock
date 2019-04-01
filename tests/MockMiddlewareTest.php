<?php declare(strict_types=1);

namespace ApiClients\Tests\Middleware\Mock;

use ApiClients\Middleware\Mock\MockMiddleware;
use ApiClients\Tools\TestUtilities\TestCase;
use function Clue\React\Block\await;
use React\EventLoop\Factory;
use RingCentral\Psr7\Request;

/**
 * @internal
 */
class MockMiddlewareTest extends TestCase
{
    public function testPre(): void
    {
        $middleware = new MockMiddleware();
        $request = new Request('GET', 'https://example.com', [], '');

        $modifiedRequest = await($middleware->pre($request, 'abc'), Factory::create());
        self::assertSame(
            [
                'Host' => ['example.com'],
            ],
            $modifiedRequest->getHeaders()
        );
    }
}
