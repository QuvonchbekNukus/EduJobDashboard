<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Vacancy extends Model
{
    use HasFactory;

    public const WORK_FORMATS = ['online', 'offline', 'gibrid'];
    public const STATUSES = ['pending', 'published', 'rejected', 'archived'];

    protected $fillable = [
        'region_id',
        'category_id',
        'employer_id',
        'subject_id',
        'title',
        'city',
        'district',
        'salary_from',
        'salary_to',
        'schedule',
        'work_format',
        'requirements',
        'contact_phone',
        'contact_username',
        'status',
        'published_at',
        'benefits',
    ];

    protected $casts = [
        'salary_from' => 'integer',
        'salary_to' => 'integer',
        'published_at' => 'date',
    ];

    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function employer(): BelongsTo
    {
        return $this->belongsTo(Employer::class);
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function applications(): HasMany
    {
        return $this->hasMany(Application::class);
    }

    public function seekers(): BelongsToMany
    {
        return $this->belongsToMany(Seeker::class, 'applications')
            ->withPivot('status')
            ->withTimestamps();
    }

    public function post(): HasOne
    {
        return $this->hasOne(VacancyPost::class);
    }
}
