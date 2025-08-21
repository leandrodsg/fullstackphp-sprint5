<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Service extends Model
{
    protected $fillable = [
        'name',
        'category',
        'website_url',
        'description'
    ];

    public function subscriptions(): HasMany {
        return $this->hasMany(Subscription::class);
    }
}
