<?php

declare(strict_types=1);

namespace App\Command\Storage;

use Ramsey\Uuid\Uuid;
use Spiral\Boot\DirectoriesInterface;
use Spiral\Console\Attribute as Console;
use Spiral\Console\Command;
use Spiral\Distribution\DistributionInterface;
use Spiral\Storage\StorageInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[Console\AsCommand(
    name: 'app:storage:static-put',
    description: 'Put file local'
)]
final class StaticPutCommand extends Command
{
    #[Console\Argument(name: 'bucket')]
    private string $bucket = 'local';

    protected function perform(
        OutputInterface $output,
        DirectoriesInterface $dir,
        StorageInterface $storage,
        DistributionInterface $distribution
    ): void {
        $filepath = $dir->get('resources') . 'fuu.jpg';
        $file = $storage
            ->bucket($this->bucket)
            ->write(
                pathname: Uuid::uuid7()->toString(),
                content: \fopen($filepath, 'rb+'),
            );
        $link = $distribution
            ->resolver('local')
            ->resolve($file->getPathname());
        $output->writeln("URL: $link");
    }
}
