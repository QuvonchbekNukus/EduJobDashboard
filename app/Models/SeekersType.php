<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SeekersType extends Model
{
    use HasFactory;

    protected $table = 'seekers_types';

    protected $fillable = [
        'name',
        'label',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function seekers(): HasMany
    {
        return $this->hasMany(Seeker::class, 'seekertype_id');
    }
}
