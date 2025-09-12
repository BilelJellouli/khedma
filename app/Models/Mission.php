<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\MissionStatus;
use App\Enums\MissionType;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Mission extends Model
{
    use HasFactory;
    use HasUuids;

    protected $fillable = [
        'service_id',
        'title',
        'description',
        'location',
        'status',
        'type',
        'budget',
    ];

    protected $casts = [
        'status' => MissionStatus::class,
        'type' => MissionType::class,
    ];

    #[Scope]
    protected function ofUser(Builder $query, User $customer): void
    {
        $query->where('customer_id', $customer->id);
    }

    #[Scope]
    protected function live(Builder $query): void
    {
        $query->where('status', MissionStatus::LIVE);
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function proposals(): HasMany
    {
        return $this->hasMany(Proposal::class);
    }
}
