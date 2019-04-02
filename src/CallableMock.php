<?php declare(strict_types=1);

namespace ApiClients\Middleware\Mock;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

final class CallableMock implements MockInterface
{
    /** @var callable */
    private $matcher;

    /** @var ResponseInterface */
    private $response;

    public function __construct(callable $matcher, ResponseInterface $response)
    {
        $this->matcher = $matcher;
        $this->response = $response;
    }

    public function match(RequestInterface $request): bool
    {
        return ($this->matcher)($request);
    }

    public function response(RequestInterface $request): ResponseInterface
    {
        return $this->response;
    }
}
