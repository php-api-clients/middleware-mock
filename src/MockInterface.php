<?php declare(strict_types=1);

namespace ApiClients\Middleware\Mock;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

interface MockInterface
{
    public function match(RequestInterface $request): bool;

    public function response(RequestInterface $request): ResponseInterface;
}
