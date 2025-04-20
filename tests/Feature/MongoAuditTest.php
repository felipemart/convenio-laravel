<?php

declare(strict_types = 1);

use App\Models\MongoAudit;
use Illuminate\Database\Eloquent\Relations\MorphTo;

it('auditable method returns a MorphTo relation', function () {
    $mongoAudit = new MongoAudit();
    expect($mongoAudit->auditable())->toBeInstanceOf(MorphTo::class);
});

it('user method returns a MorphTo relation', function () {
    $mongoAudit = new MongoAudit();
    expect($mongoAudit->user())->toBeInstanceOf(MorphTo::class);
});
