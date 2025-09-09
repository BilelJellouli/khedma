<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Agent extends Model
{
    use HasFactory;

    protected $fillable = [
        'bio',
        'experience',
        'skills',
        'availability',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function contacts(): HasMany
    {
        return $this->hasMany(AgentContact::class);
    }

    public function services(): BelongsToMany
    {
        return $this->belongsToMany(Service::class);
    }

    public function proposals(): HasMany
    {
        return $this->hasMany(Proposal::class);
    }

    public function ratings(): HasMany
    {
        return $this->hasMany(Rating::class);
    }

    public function recommendations(): HasMany
    {
        return $this->hasMany(Recommendation::class);
    }
}
