<?php

declare(strict_types = 1);

test('globals')
    ->expect(['dd', 'dump', 'ray', 'ds'])
    ->not->toBeUsed();
