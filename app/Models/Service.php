<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Service extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'name',
        'category',
        'website_url',
        'description'
    ];

    public function subscriptions(): HasMany {
        return $this->hasMany(Subscription::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope to filter services by user
     */
    public function scopeForUser($query, $userId = null) {
        $userId = $userId ?? auth()->id();
        return $query->where('user_id', $userId);
    }
}
