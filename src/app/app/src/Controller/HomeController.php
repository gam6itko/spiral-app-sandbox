<?php

namespace App\Controller;

use App\Job\SleepJob;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Spiral\Queue\Options;
use Spiral\Queue\QueueManager;
use Spiral\Router\Annotation\Route;

class HomeController
{
    #[Route(route: '/produce/kafka', methods: 'GET')]
    public function indexAction(QueueManager $qm): ResponseInterface
    {
        $qm->getConnection('roadrunner')
            ->push(
                'foo-bar',
                [
                    'foo' => 'bar',
                    'time' => time(),
                ],
                Options::onQueue('kafka')
            );

        return new Response(
            status: 200,
            headers: ['Content-Type' => 'application/json'],
            body: \json_encode([
                'ok' => true,
                'time' => time(),
            ])
        );
    }

    #[Route(route: '/produce/local', methods: 'GET')]
    public function localAction(QueueManager $qm): ResponseInterface
    {
        $qm->getConnection('roadrunner')
            ->push(
                SleepJob::class,
                ['seconds' => 2],
                Options::onQueue('local')
            );

        return new Response(
            status: 200,
            headers: ['Content-Type' => 'application/json'],
            body: \json_encode([
                'ok' => true,
                'time' => time(),
            ])
        );
    }

    #[Route(route: '/favicon.ico', methods: 'GET')]
    public function faviconAction(): ResponseInterface
    {
        $logo = <<<SVG
<svg width="294" height="382" xmlns="http://www.w3.org/2000/svg">
  <path fill="#6FB7F1" d="M229 57.5L64.7 250.1 23.1 108.6 229 57.5"/>
  <path fill="#459AE1" d="M229 57.5l-.3 120.5-56.4 24.8-107.6 47.3z"/>
  <path fill="#3F87D2" d="M271.9 7.1L228.7 178l.3-120.5z"/>
  <path fill="#6FB7F1" d="M215.6 230l-.3 1.3-29.8 117.6-38-56.3z"/>
  <path fill="#3F87D2" d="M172.3 202.8l-24.8 89.8-82.8-42.5z"/>
  <path fill="#459AE1" d="M215.6 230l-68.1 62.6 24.8-89.8z"/>
</svg>
SVG;
        return new Response(
            status: 200,
            headers: ['Content-Type' => 'image/svg+xml'],
            body: $logo
        );
    }
}
