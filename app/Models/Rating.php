<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Rating extends Model
{
    use HasFactory;
    use HasUuids;

    protected $fillable = [
        'rate',
        'comment',
    ];

    public function agent(): belongsTo
    {
        return $this->belongsTo(Agent::class);
    }

    public function customer(): belongsTo
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function mission(): BelongsTo
    {
        return $this->belongsTo(Mission::class, 'mission_id');
    }
}
