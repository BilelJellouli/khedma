<?php

declare(strict_types=1);

namespace Tests\Feature\Models;

use App\Models\Agent;
use App\Models\Mission;
use App\Models\Proposal;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProposalTest extends TestCase
{
    use RefreshDatabase;

    public function testBelongsToMission(): void
    {
        $mission = Mission::factory()->create();
        $proposal = Proposal::factory()->for($mission)->create();

        $this->assertInstanceOf(BelongsTo::class, $proposal->mission());
        $this->assertInstanceOf(Mission::class, $proposal->mission);
        $this->assertTrue($proposal->mission->is($mission));
    }

    public function testBelongToAgent(): void
    {
        $agent = Agent::factory()->create();
        $proposal = Proposal::factory()->for($agent)->create();

        $this->assertInstanceOf(BelongsTo::class, $proposal->agent());
        $this->assertInstanceOf(Agent::class, $proposal->agent);
        $this->assertTrue($proposal->agent->is($agent));
    }
}
