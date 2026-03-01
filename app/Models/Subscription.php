<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'plan_id',
        'start_at',
        'end_at',
        'is_active',
        'canceled_at',
        'created_from_payment_id',
    ];

    protected $casts = [
        'start_at' => 'datetime',
        'end_at' => 'datetime',
        'is_active' => 'boolean',
        'canceled_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    public function createdFromPayment(): BelongsTo
    {
        return $this->belongsTo(Payment::class, 'created_from_payment_id');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query
            ->where('is_active', true)
            ->where('end_at', '>', now());
    }

    public function isExpired(): bool
    {
        return $this->end_at !== null && $this->end_at->lte(now());
    }

    public function deactivate(): void
    {
        $this->forceFill([
            'is_active' => false,
            'canceled_at' => now(),
        ])->save();
    }
}
