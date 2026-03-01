<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Application extends Model
{
    use HasFactory;

    public const STATUSES = ['sent', 'viewed', 'accepted', 'rejected'];

    protected $fillable = [
        'vacancy_id',
        'seeker_id',
        'status',
    ];

    public function vacancy(): BelongsTo
    {
        return $this->belongsTo(Vacancy::class);
    }

    public function seeker(): BelongsTo
    {
        return $this->belongsTo(Seeker::class);
    }
}
