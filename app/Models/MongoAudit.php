<?php

declare(strict_types = 1);

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\MorphTo;
use MongoDB\Laravel\Eloquent\Model;
use OwenIt\Auditing\Audit;

class MongoAudit extends Model implements \OwenIt\Auditing\Contracts\Audit
{
    use Audit;

    /**
     * {@inheritdoc}
     */
    protected $guarded = [];

    /**
     * {@inheritdoc}
     */
    protected $casts = [
        'old_values' => 'json',
        'new_values' => 'json',
    ];

    /**
     * {@inheritdoc}
     */
    public function auditable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * {@inheritdoc}
     */
    public function user(): MorphTo
    {
        return $this->morphTo();
    }
}
