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

    public function __construct(string $path, ResponseInterface $response)
    {
        $this->path = $path;
        $this->response = $response;
    }

    public function match(RequestInterface $request): bool
    {
        return $request->getUri()->getPath() === $this->path;
    }

    public function response(RequestInterface $request): ResponseInterface
    {
        return $this->response;
    }
}
