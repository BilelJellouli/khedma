<?php

declare(strict_types=1);

namespace Feature\Models;

use App\Models\Agent;
use App\Models\Recommendation;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RecommendationTest extends TestCase
{
    use RefreshDatabase;

    public function test_belongs_to_agent(): void
    {
        $agent = Agent::factory()->create();
        $recommendation = Recommendation::factory()->for($agent)->create();

        $this->assertInstanceOf(BelongsTo::class, $recommendation->agent());
        $this->assertInstanceOf(Agent::class, $recommendation->agent);
        $this->assertTrue($recommendation->agent()->is($agent));
    }

    public function test_belongs_to_customer(): void
    {
        $customer = User::factory()->customer()->create();
        $recommendation = Recommendation::factory()->for($customer, 'customer')->create();

        $this->assertInstanceOf(BelongsTo::class, $recommendation->customer());
        $this->assertInstanceOf(User::class, $recommendation->customer);
        $this->assertTrue($recommendation->customer->is($customer));
    }

    public function test_deleting_customer_will_be_set_to_null(): void
    {
        $customer = User::factory()->customer()->create();
        $recommendation = Recommendation::factory()->for($customer, 'customer')->create();
        $customer->delete();
        $this->assertNull($recommendation->refresh()->customer);
    }
}
