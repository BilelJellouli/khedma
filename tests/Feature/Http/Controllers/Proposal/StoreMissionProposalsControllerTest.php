<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Proposal;

use App\Actions\Proposal\CreateProposalAction;
use App\Enums\PricingUnit;
use App\Enums\ProposalInitiator;
use App\Models\Agent;
use App\Models\Mission;
use App\Models\Proposal;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StoreMissionProposalsControllerTest extends TestCase
{
    use RefreshDatabase;

    private Mission $mission;

    private User $agent;

    private string $route;

    public function testCreatesMissionProposalAndReturnCreated(): void
    {
        $this->actingAs($this->agent)
            ->postJson($this->route)
            ->assertCreated()
            ->assertJsonStructure([
                'data',
            ]);
    }

    public function testUsesCreateProposalAction(): void
    {
        $spy = $this->spy(CreateProposalAction::class);

        $this->actingAs($this->agent)
            ->postJson($this->route);

        $agent = Agent::firstWhere('user_id', $this->agent->id);

        $spy->shouldHaveReceived('execute')
            ->once()
            ->withArgs(
                fn (Agent $agentArg, Mission $mission, array $data): bool => $agentArg->is($agent) &&
                    $mission->is($this->mission) &&
                    $data === ['initiator' => ProposalInitiator::AGENT]
            );
    }

    public function testPassesToActionValidRequestData(): void
    {
        $spy = $this->spy(CreateProposalAction::class);

        $this->actingAs($this->agent)
            ->postJson($this->route, $requestData = [
                'agent_message' => 'agent message',
                'price' => 1,
                'pricing_unit' => PricingUnit::PER_DAY->value,
            ]);

        $spy->shouldHaveReceived('execute')
            ->once()
            ->withArgs(
                fn (Agent $agentArg, Mission $mission, array $data): bool => $data === [
                    ...$requestData,
                    'initiator' => ProposalInitiator::AGENT,
                ]
            );
    }

    public function testAgentCanNotPostOnHisOwnMissions(): void
    {
        // An Agent can be a customer as well
        $mission = Mission::factory()->for($this->agent, 'customer')->create();
        $this->actingAs($this->agent)
            ->postJson(route('missions.proposals.store', ['mission' => $mission]))
            ->assertForbidden();
    }

    public function testCanNotProposeTwiceToSameMission(): void
    {
        $agent = Agent::firstWhere('user_id', $this->agent->id);
        Proposal::factory()
            ->for($agent)
            ->for($this->mission)
            ->create();

        $this->actingAs($this->agent)
            ->postJson($this->route)
            ->assertForbidden();
    }

    public function testRouteMiddlewares(): void
    {
        $this->assertRouteMiddleware(
            'missions.proposals.store',
            'api', 'auth:sanctum'
        );
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->mission = Mission::factory()->create();
        $this->agent = User::factory()->agent()->create();
        $this->route = route('missions.proposals.store', ['mission' => $this->mission]);
    }
}
