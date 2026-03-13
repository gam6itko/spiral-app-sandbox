<?php

declare(strict_types=1);

use Monolog\Logger;

return [
    'globalLevel' => Logger::toMonologLevel(env('MONOLOG_DEFAULT_LEVEL', 'INFO')),
];
