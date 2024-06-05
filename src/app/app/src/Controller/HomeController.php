<?php

namespace App\Controller;

use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Spiral\Queue\QueueManager;
use Spiral\Router\Annotation\Route;

class HomeController
{
    #[Route(route: '/produce/kafka', methods: 'GET')]
    public function indexAction(QueueManager $qm): ResponseInterface
    {
        $qm->getConnection('kafka')->push('foo-bar', ['foo' => 'bar']);

        return new Response(
            status: 200,
            headers: ['Content-Type' => 'application/json'],
            body: \json_encode(['ok' => true])
        );
    }
}
