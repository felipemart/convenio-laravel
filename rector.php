<?php

declare(strict_types = 1);

use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withPaths([

        __DIR__ . '/app',
        __DIR__ . '/database',
        __DIR__ . '/tests',
        __DIR__ . '/routes',
        __DIR__ . '/bootstrap/app.php',

    ])
    // here we can define, what prepared sets of rules will be applied
    ->withPreparedSets(
        codeQuality: true,
        typeDeclarations: true,
        privatization: true,
        earlyReturn: true,
        strictBooleans: true,
    );
