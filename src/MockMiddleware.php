<?php declare(strict_types=1);

namespace ApiClients\Middleware\Mock;

use ApiClients\Foundation\Middleware\ErrorTrait;
use ApiClients\Foundation\Middleware\MiddlewareInterface;
use ApiClients\Foundation\Middleware\PostTrait;
use Psr\Http\Message\RequestInterface;
use React\Promise\CancellablePromiseInterface;
use function React\Promise\reject;
use RingCentral\Psr7\Response;

final class MockMiddleware implements MiddlewareInterface
{
    use PostTrait;
    use ErrorTrait;
    private const ERROR_MESSAGE = 'No matching mocks found';
    /** @var MockInterface[] */
    private $mocks = [];

    /**
     * @param MockInterface[] $mocks
     */
    public function __construct(MockInterface ...$mocks)
    {
        $this->mocks = $mocks;
    }

    /**
     * @param  RequestInterface            $request
     * @param  array                       $options
     * @return CancellablePromiseInterface
     */
    public function pre(
        RequestInterface $request,
        string $transactionId,
        array $options = []
    ): CancellablePromiseInterface {
        foreach ($this->mocks as $mock) {
            if ($mock->match($request)) {
                return reject($mock->response($request));
            }
        }

        return reject(new Response(500, [], self::ERROR_MESSAGE));
    }
}
