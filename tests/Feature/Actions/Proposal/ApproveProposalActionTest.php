<?php

declare(strict_types=1);

namespace Tests\Feature\Actions\Proposal;

use App\Actions\Proposal\ApproveProposalAction;
use App\Actions\Proposal\UpdateProposalAction;
use App\Enums\ProposalStatus;
use App\Events\Proposal\ProposalApproved;
use App\Models\Proposal;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class ApproveProposalActionTest extends TestCase
{
    use RefreshDatabase;

    private Proposal $proposal;

    private ApproveProposalAction $action;

    public function testApprovesProposal(): void
    {
        $this->action->execute($this->proposal);

        $this->assertSame($this->proposal->refresh()->status, ProposalStatus::APPROVED);
    }

    public function testUsesUpdateProposalAction(): void
    {
        $spy = $this->spy(UpdateProposalAction::class);

        $this->app->make(ApproveProposalAction::class)->execute($this->proposal);

        $spy->shouldHaveReceived('execute')
            ->once()
            ->withArgs(
                fn (Proposal $proposal, array $data): bool => $proposal->is($this->proposal) && $data === ['status' => ProposalStatus::APPROVED]
            );
    }

    public function testDispatchesProposalApprovedEvent(): void
    {
        Event::fake();

        $this->app->make(ApproveProposalAction::class)->execute($this->proposal);

        Event::assertDispatched(fn (ProposalApproved $event) => $event->proposal->is($this->proposal));
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->proposal = Proposal::factory()->pending()->create();
        $this->action = $this->app->make(ApproveProposalAction::class);
    }
}
