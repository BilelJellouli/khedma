<?php

declare(strict_types=1);

namespace Tests\Feature\Models;

use App\Models\Agent;
use App\Models\Mission;
use App\Models\Proposal;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProposalTest extends TestCase
{
    use RefreshDatabase;

    public function test_belongs_to_mission(): void
    {
        $mission = Mission::factory()->create();
        $proposal = Proposal::factory()->for($mission)->create();

        $this->assertInstanceOf(BelongsTo::class, $proposal->mission());
        $this->assertInstanceOf(Mission::class, $proposal->mission);
        $this->assertTrue($proposal->mission->is($mission));
    }

    public function test_belong_to_agent(): void
    {
        $agent = Agent::factory()->create();
        $proposal = Proposal::factory()->for($agent)->create();

        $this->assertInstanceOf(BelongsTo::class, $proposal->agent());
        $this->assertInstanceOf(Agent::class, $proposal->agent);
        $this->assertTrue($proposal->agent->is($agent));
    }

    public function test_belongs_to_customer(): void
    {
        $customer = User::factory()->customer()->create();
        $proposal = Proposal::factory()->for($customer, 'customer')->create();

        $this->assertInstanceOf(BelongsTo::class, $proposal->customer());
        $this->assertInstanceOf(User::class, $proposal->customer);
        $this->assertTrue($proposal->customer->is($customer));
    }
}
