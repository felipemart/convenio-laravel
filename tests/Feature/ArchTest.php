<?php

test('globals')
    ->expect(['dd', 'dump', 'ray', 'ds'])
    ->not->toBeUsed();
