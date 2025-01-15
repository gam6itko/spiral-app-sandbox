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
    name: 'app:storage:minio-put',
    description: 'Put file to minio S3'
)]
final class MinioPutCommand extends Command
{
    protected function perform(
        OutputInterface $output,
        DirectoriesInterface $dir,
        StorageInterface $storage,
        DistributionInterface $distribution
    ): void {
        $filepath = $dir->get('resources') . 'fuu.jpg';
        $file = $storage
            ->bucket('s3')
            ->write(
                pathname: Uuid::uuid7()->toString(),
                content: \fopen($filepath, 'rb+'),
            );
        $link = $distribution->resolver('s3')->resolve($file->getPathname());
        $output->writeln("URL: $link");
    }
}
