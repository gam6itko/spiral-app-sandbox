<?php

declare(strict_types=1);

namespace App\Controller;

use Nyholm\Psr7\Response;
use RoadRunner\Lock\LockInterface;
use Spiral\Router\Annotation\Route;
use Symfony\Component\Lock\LockFactory;

final class LockController
{
    public function __construct(
        private readonly LockFactory   $lockFactory,
        private readonly LockInterface $rrLock,
    )
    {
    }

    #[Route(route: '/lock/symfony', methods: 'GET')]
    public function symfonyAction(): Response
    {
        $lock = $this->lockFactory->createLock('symfony');
        if (false === $lock->acquire()) {
            return $this->json(409, ['success' => false]);
        }

        try {
            sleep(5);

            return $this->json(200, [
                'success' => true,
            ]);
        } finally {
            $lock->release();
        }
    }

    #[Route(route: '/lock/roadrunner', methods: 'GET')]
    public function roadrunnerAction(): Response
    {
        $id = $this->rrLock->lock('roadrunner');
        if (false === $id) {
            return $this->json(409, ['success' => false]);
        }

        try {
            sleep(5);

            return $this->json(200, [
                'id' => $id,
                'success' => true,
            ]);
        } finally {
            $this->rrLock->release('roadrunner', $id);
        }
    }

    private function json(int $status, array $data = []): Response
    {
        return new Response(
            $status,
            ['Content-Type' => 'application/json'],
            \json_encode($data),
        );
    }
}
