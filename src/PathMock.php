<?php declare(strict_types=1);

namespace ApiClients\Middleware\Mock;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

final class PathMock implements MockInterface
{
    /** @var string */
    private $path;

    /** @var ResponseInterface */
    private $response;

    /** @var string */
    private $method;

    public function __construct(string $path, ResponseInterface $response, string $method = 'GET')
    {
        $this->path = $path;
        $this->response = $response;
        $this->method = $method;
    }

    public function match(RequestInterface $request): bool
    {
        return $request->getMethod() === $this->method && $request->getUri()->getPath() === $this->path;
    }

    public function response(RequestInterface $request): ResponseInterface
    {
        return $this->response;
    }
}
