<?php

declare(strict_types=1);

use App\Kernel;

\mb_internal_encoding('UTF-8');
\error_reporting(E_ALL & ~E_DEPRECATED);
\ini_set('display_errors', 'stderr');

require __DIR__ . '/vendor/autoload.php';

$app = Kernel::create(
    directories: ['root' => __DIR__],
)->run();

if ($app === null) {
    exit(255);
}

$code = (int) $app->serve();
exit($code);
