<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Proposal;

use App\Actions\Proposal\RejectProposalAction;
use App\Enums\ProposalRejectionReason;
use App\Enums\ProposalStatus;
use App\Models\Mission;
use App\Models\Proposal;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RejectMissionProposalsControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $customer;

    private Mission $mission;

    private Proposal $proposal;

    private string $route;

    public function testRejectProposalAndReturnNoContent(): void
    {
        $this->actingAs($this->customer)
            ->putJson($this->route)
            ->assertNoContent();
    }

    public function testUsesRejectProposalAction(): void
    {
        $spy = $this->spy(RejectProposalAction::class);

        $this->actingAs($this->customer)
            ->putJson($this->route, $requestObject = [
                'rejection_reason' => ProposalRejectionReason::OTHERS->value,
                'rejection_message' => 'REJECTION_MESSAGE',
            ]);

        $spy->shouldHaveReceived('execute')
            ->once()
            ->withArgs(fn (Proposal $proposal, array $data): bool => $proposal->is($this->proposal) && $data === $requestObject);
    }

    public function testDisallowUsersRejectingMissionOnBehalfOfOthers(): void
    {
        $anotherCustomer = User::factory()->create();

        $this->actingAs($anotherCustomer)
            ->putJson($this->route)
            ->assertForbidden();
    }

    public function testCanNotRejectProposalTwice(): void
    {
        $this->proposal->update(['status' => ProposalStatus::REJECTED]);

        $this->actingAs($this->customer)
            ->putJson($this->route)
            ->assertForbidden();
    }

    public function testRouteMiddleware(): void
    {
        $this->assertRouteMiddleware(
            'missions.proposals.reject',
            'api', 'auth:sanctum'
        );
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->customer = User::factory()->customer()->create();
        $this->mission = Mission::factory()->for($this->customer, 'customer')->create();
        $this->proposal = Proposal::factory()->for($this->mission)->pending()->create();
        $this->route = route('missions.proposals.reject', ['mission' => $this->mission, 'proposal' => $this->proposal]);
    }
}
