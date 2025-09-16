<?php

declare(strict_types=1);

namespace Tests\Feature\Actions\Proposal;

use App\Actions\Proposal\RejectProposalAction;
use App\Actions\Proposal\UpdateProposalAction;
use App\Enums\ProposalRejectionReason;
use App\Enums\ProposalStatus;
use App\Events\Proposal\ProposalRejected;
use App\Models\Proposal;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class RejectProposalActionTest extends TestCase
{
    use RefreshDatabase;

    private Proposal $proposal;

    private RejectProposalAction $action;

    private array $data;

    public function testRejectsProposal(): void
    {
        $this->action->execute($this->proposal, $this->data);

        $this->assertSame($this->proposal->refresh()->status, ProposalStatus::REJECTED);
        $this->assertSame($this->proposal->rejection_reason, ProposalRejectionReason::OTHERS);
        $this->assertSame($this->proposal->rejection_message, 'rejection_message');
    }

    public function testUsesUpdateProposalAction(): void
    {
        $spy = $this->spy(UpdateProposalAction::class);

        $this->app->make(RejectProposalAction::class)
            ->execute($this->proposal, $this->data);

        $spy->shouldHaveReceived('execute')
            ->once()
            ->withArgs(
                fn (Proposal $proposal, array $data): bool => $proposal->is($this->proposal) && $data === [...$this->data, 'status' => ProposalStatus::REJECTED]
            );
    }

    public function testDispatchesProposalApprovedEvent(): void
    {
        Event::fake();

        $this->app->make(RejectProposalAction::class)->execute($this->proposal, []);

        Event::assertDispatched(fn (ProposalRejected $event) => $event->proposal->is($this->proposal));
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->proposal = Proposal::factory()->pending()->create();
        $this->data = [
            'rejection_reason' => ProposalRejectionReason::OTHERS,
            'rejection_message' => 'rejection_message',
        ];
        $this->action = $this->app->make(RejectProposalAction::class);
    }
}
