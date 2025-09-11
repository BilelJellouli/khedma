<?php

declare(strict_types=1);

namespace Tests\Feature\Models;

use App\Models\Mission;
use App\Models\Rating;
use App\Models\Recommendation;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function testHasManyMissions(): void
    {
        $user = User::factory()->customer()->create();
        Mission::factory(2)->for($user, 'customer')->create();

        $this->assertInstanceOf(HasMany::class, $user->missions());
        $this->assertInstanceOf(Collection::class, $user->missions);
        $this->assertCount(2, $user->missions);
        $this->assertInstanceOf(Mission::class, $user->missions->first());
    }

    public function testHasManyRatings(): void
    {
        $user = User::factory()->customer()->create();
        Rating::factory(2)->for($user, 'customer')->create();
        $this->assertInstanceOf(HasMany::class, $user->ratings());
        $this->assertInstanceOf(Collection::class, $user->ratings);
        $this->assertCount(2, $user->ratings);
        $this->assertInstanceOf(Rating::class, $user->ratings->first());
    }

    public function testHasManyRecommendations(): void
    {
        $user = User::factory()->customer()->create();
        Recommendation::factory(2)->for($user, 'customer')->create();
        $this->assertInstanceOf(HasMany::class, $user->recommendations());
        $this->assertInstanceOf(Collection::class, $user->recommendations);
        $this->assertCount(2, $user->recommendations);
        $this->assertInstanceOf(Recommendation::class, $user->recommendations->first());
    }
}
