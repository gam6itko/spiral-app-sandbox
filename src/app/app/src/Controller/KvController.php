<?php

declare(strict_types=1);

namespace App\Controller;

use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\SimpleCache\CacheInterface;
use Spiral\Router\Annotation\Route;

class KvController
{
    public function __construct(
        private readonly CacheInterface $cache
    )
    {
    }

    #[Route(route: '/kv/set/<key>/<value>', methods: 'GET')]
    public function setAction(string $key, string $value): ResponseInterface
    {
        $result = $this->cache->set($key, $value);
        return new Response(
            status: 200,
            headers: ['Content-Type' => 'application/json'],
            body: \json_encode([
                'ok' => $result,
                'time' => time(),
            ])
        );
    }

    #[Route(route: '/kv/get/<key>', methods: 'GET')]
    public function getAction(string $key): ResponseInterface
    {
        $result = $this->cache->get($key);
        return new Response(
            status: 200,
            headers: ['Content-Type' => 'application/json'],
            body: \json_encode([
                'value' => $result,
                'time' => time(),
            ])
        );
    }
}
