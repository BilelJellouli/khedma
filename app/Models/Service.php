<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category'
    ];

    public function missions(): HasMany
    {
        return $this->hasMany(Mission::class);
    }

    public function agents(): BelongsToMany
    {
        return $this->belongsToMany(Agent::class);
    }
}
