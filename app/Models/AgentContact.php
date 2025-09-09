<?php

namespace App\Models;

use App\Enums\AgentContactType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AgentContact extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'value',
        'is_primary'
    ];

    protected $casts = [
        'type' => AgentContactType::class,
        'is_primary' => 'boolean',
    ];

    public function agent(): BelongsTo
    {
        return $this->belongsTo(Agent::class);
    }
}
