<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Proposal;

use App\Actions\Proposal\ApproveProposalAction;
use App\Models\Mission;
use App\Models\Proposal;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApproveMissionProposalsControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $customer;

    private Mission $mission;

    private Proposal $proposal;

    private string $route;

    public function testApprovesProposalAndReturnNoContent(): void
    {
        $this->actingAs($this->customer)
            ->putJson($this->route)
            ->assertNoContent();
    }

    public function testUsesApproveProposalAction(): void
    {
        $spy = $this->spy(ApproveProposalAction::class);

        $this->actingAs($this->customer)
            ->putJson($this->route);

        $spy->shouldHaveReceived('execute')
            ->once()
            ->withArgs(fn (Proposal $proposal) => $proposal->is($this->proposal));
    }

    public function testDisallowUsersApproveMissionOnBehalfOfOthers(): void
    {
        $anotherCustomer = User::factory()->create();

        $this->actingAs($anotherCustomer)
            ->putJson($this->route)
            ->assertForbidden();
    }

    public function testRouteMiddleware(): void
    {
        $this->assertRouteMiddleware(
            'missions.proposals.approve',
            'api', 'auth:sanctum'
        );
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->customer = User::factory()->customer()->create();
        $this->mission = Mission::factory()->for($this->customer, 'customer')->create();
        $this->proposal = Proposal::factory()->for($this->mission)->create();
        $this->route = route('missions.proposals.approve', ['mission' => $this->mission, 'proposal' => $this->proposal]);
    }
}
