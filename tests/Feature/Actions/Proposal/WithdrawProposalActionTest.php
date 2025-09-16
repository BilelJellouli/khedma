<?php

declare(strict_types=1);

namespace Tests\Feature\Actions\Proposal;

use App\Actions\Proposal\UpdateProposalAction;
use App\Actions\Proposal\WithdrawProposalAction;
use App\Enums\ProposalStatus;
use App\Events\Proposal\ProposalWithdrawn;
use App\Models\Proposal;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class WithdrawProposalActionTest extends TestCase
{
    use RefreshDatabase;

    private Proposal $proposal;

    private WithdrawProposalAction $action;

    public function testWithdrawsProposal(): void
    {
        $this->action->execute($this->proposal);

        $this->assertSame($this->proposal->refresh()->status, ProposalStatus::WITHDRAW);
    }

    public function testUsesUpdateProposalAction(): void
    {
        $spy = $this->spy(UpdateProposalAction::class);

        $this->app->make(WithdrawProposalAction::class)->execute($this->proposal);

        $spy->shouldHaveReceived('execute')
            ->once()
            ->withArgs(
                fn (Proposal $proposal, array $data): bool => $proposal->is($this->proposal) && $data === ['status' => ProposalStatus::WITHDRAW]
            );
    }

    public function testDispatchesProposalWithdrawnEvent(): void
    {
        Event::fake();

        $this->app->make(WithdrawProposalAction::class)->execute($this->proposal);

        Event::assertDispatched(fn (ProposalWithdrawn $event) => $event->proposal->is($this->proposal));
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->proposal = Proposal::factory()->pending()->create();
        $this->action = $this->app->make(WithdrawProposalAction::class);
    }
}
