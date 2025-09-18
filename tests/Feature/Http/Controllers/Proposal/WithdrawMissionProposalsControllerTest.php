<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Proposal;

use App\Actions\Proposal\WithdrawProposalAction;
use App\Models\Agent;
use App\Models\Mission;
use App\Models\Proposal;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WithdrawMissionProposalsControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $agent;

    private Mission $mission;

    private Proposal $proposal;

    private string $route;

    public function testWithdrawProposalAndReturnNoContent(): void
    {
        $this->actingAs($this->agent)
            ->putJson($this->route)
            ->assertNoContent();
    }

    public function testUsesApproveProposalAction(): void
    {
        $spy = $this->spy(WithdrawProposalAction::class);

        $this->actingAs($this->agent)
            ->putJson($this->route);

        $spy->shouldHaveReceived('execute')
            ->once()
            ->withArgs(fn (Proposal $proposal) => $proposal->is($this->proposal));
    }

    public function testDisallowUsersWithdrawMissionOnBehalfOfOthers(): void
    {
        $anotherAgent = User::factory()->agent()->create();

        $this->actingAs($anotherAgent)
            ->putJson($this->route)
            ->assertForbidden();
    }

    public function testRouteMiddleware(): void
    {
        $this->assertRouteMiddleware(
            'missions.proposals.withdraw',
            'api', 'auth:sanctum'
        );
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->agent = User::factory()->agent()->withAgentProfile()->create();
        $agent = Agent::firstWhere('user_id', $this->agent->id);

        $this->mission = Mission::factory()->create();
        $this->proposal = Proposal::factory()
            ->for($this->mission)
            ->for($agent)
            ->pending()
            ->create();
        $this->route = route('missions.proposals.withdraw', ['mission' => $this->mission, 'proposal' => $this->proposal]);
    }
}
