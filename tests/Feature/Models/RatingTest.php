<?php

declare(strict_types=1);

namespace Tests\Feature\Models;

use App\Models\Agent;
use App\Models\Mission;
use App\Models\Rating;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RatingTest extends TestCase
{
    use RefreshDatabase;

    public function test_belongs_to_agent(): void
    {
        $agent = Agent::factory()->create();
        $rating = Rating::factory()->for($agent)->create();

        $this->assertInstanceOf(BelongsTo::class, $rating->agent());
        $this->assertInstanceOf(Agent::class, $rating->agent);
        $this->assertTrue($rating->agent()->is($agent));
    }

    public function test_belongs_to_customer(): void
    {
        $customer = User::factory()->customer()->create();
        $rating = Rating::factory()->for($customer, 'customer')->create();

        $this->assertInstanceOf(BelongsTo::class, $rating->customer());
        $this->assertInstanceOf(User::class, $rating->customer);
        $this->assertTrue($rating->customer->is($customer));
    }

    public function test_belongs_to_mission(): void
    {
        $mission = Mission::factory()->create();
        $rating = Rating::factory()->for($mission)->create();

        $this->assertInstanceOf(BelongsTo::class, $rating->mission());
        $this->assertInstanceOf(Mission::class, $rating->mission);
        $this->assertTrue($rating->mission->is($mission));
    }

    public function test_mission_will_be_set_null_if_mission_deleted(): void
    {
        $mission = Mission::factory()->create();
        $rating = Rating::factory()->for($mission)->create();
        $mission->delete();
        $this->assertNull($rating->refresh()->mission);
    }
}
