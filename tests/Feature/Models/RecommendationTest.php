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

    public function testBelongsToAgent(): void
    {
        $agent = Agent::factory()->create();
        $recommendation = Recommendation::factory()->for($agent)->create();

        $this->assertInstanceOf(BelongsTo::class, $recommendation->agent());
        $this->assertInstanceOf(Agent::class, $recommendation->agent);
        $this->assertTrue($recommendation->agent()->is($agent));
    }

    public function testBelongsToCustomer(): void
    {
        $customer = User::factory()->customer()->create();
        $recommendation = Recommendation::factory()->for($customer, 'customer')->create();

        $this->assertInstanceOf(BelongsTo::class, $recommendation->customer());
        $this->assertInstanceOf(User::class, $recommendation->customer);
        $this->assertTrue($recommendation->customer->is($customer));
    }

    public function testDeletingCustomerWillBeSetToNull(): void
    {
        $customer = User::factory()->customer()->create();
        $recommendation = Recommendation::factory()->for($customer, 'customer')->create();
        $customer->delete();
        $this->assertNull($recommendation->refresh()->customer);
    }
}
