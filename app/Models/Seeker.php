<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Seeker extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'region_id',
        'seekertype_id',
        'subject_id',
        'experience',
        'salary_min',
        'work_format',
        'about_me',
        'cv_file_path',
    ];

    protected $casts = [
        'salary_min' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }

    public function seekersType(): BelongsTo
    {
        return $this->belongsTo(SeekersType::class, 'seekertype_id');
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }
}
