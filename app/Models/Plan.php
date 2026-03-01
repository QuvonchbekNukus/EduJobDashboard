<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Plan extends Model
{
    use HasFactory;

    public const TYPE_PER_POST = 'per_post';
    public const TYPE_VIP = 'vip';
    public const TYPE_SUBSCRIPTION = 'subscription';

    public const TYPES = [
        self::TYPE_PER_POST,
        self::TYPE_VIP,
        self::TYPE_SUBSCRIPTION,
    ];

    protected $fillable = [
        'name',
        'type',
        'price',
        'duration_days',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }
}
