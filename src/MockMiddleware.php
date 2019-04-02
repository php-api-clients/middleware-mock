<?php declare(strict_types=1);

namespace ApiClients\Middleware\Mock;

use ApiClients\Foundation\Middleware\ErrorTrait;
use ApiClients\Foundation\Middleware\MiddlewareInterface;
use ApiClients\Foundation\Middleware\PostTrait;
use Psr\Http\Message\RequestInterface;
use React\Promise\CancellablePromiseInterface;
use function React\Promise\reject;
use function React\Promise\resolve;

final class MockMiddleware implements MiddlewareInterface
{
    use PostTrait;
    use ErrorTrait;
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
                return resolve($mock->response($request));
            }
        }

        return reject(new \RuntimeException('No matching mocks found'));
    }
}
