<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Requests\Proposal;

use App\Models\Mission;
use App\Models\Proposal;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RejectProposalRequestTest extends TestCase
{
    use RefreshDatabase;

    private Mission $mission;

    private Proposal $proposal;

    private string $route;

    public function testAuthorizeOnlyMissionOwner(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->putJson($this->route)
            ->assertForbidden();

        $this->actingAs($this->mission->customer)
            ->putJson($this->route)
            ->assertNoContent();
    }

    public function testFailsWithIncorrectRejectionReason(): void
    {
        $this->actingAs($this->mission->customer)
            ->putJson($this->route, ['rejection_reason' => 'NOT_VALID_REJECTION_ENUM'])
            ->assertUnprocessable()
            ->assertJsonValidationErrorFor('rejection_reason');
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->mission = Mission::factory()->create();
        $this->proposal = Proposal::factory()
            ->for($this->mission)
            ->pending()
            ->create();

        $this->route = route('missions.proposals.reject', [
            'mission' => $this->mission,
            'proposal' => $this->proposal,
        ]);
    }
}
