<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subscription extends Model
{
    protected $fillable = [
        'user_id',
        'service_id',
        'plan',
        'price',
        'currency',
        'next_billing_date',
        'status'
    ];

    protected $casts = [
        'next_billing_date' => 'date',
        'price' => 'decimal:2'
    ];

    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function service(): BelongsTo {
        return $this->belongsTo(Service::class);
    }
}
