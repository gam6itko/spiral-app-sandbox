<?php

declare(strict_types=1);

$appSrcDir = rtrim(directory('app'), DIRECTORY_SEPARATOR).'/src';

return [
    'debug' => true,
    'cache' => [
        'directory' => directory('runtime').'cache/listeners',
        'enabled' => env('TOKENIZER_CACHE_TARGETS', false),
    ],
    'directories' => [
        $appSrcDir
    ]
];
