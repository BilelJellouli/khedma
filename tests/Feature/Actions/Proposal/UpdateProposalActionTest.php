<?php

declare(strict_types=1);

namespace Tests\Feature\Actions\Proposal;

use App\Actions\Proposal\UpdateProposalAction;
use App\Enums\PricingUnit;
use App\Enums\ProposalInitiator;
use App\Enums\ProposalRejectionReason;
use App\Enums\ProposalStatus;
use App\Events\Proposal\ProposalUpdated;
use App\Models\Proposal;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class UpdateProposalActionTest extends TestCase
{
    use RefreshDatabase;

    private UpdateProposalAction $action;

    private Proposal $proposal;

    private array $data;

    public function testUpdateProposalAndReturn(): void
    {
        $this->action->execute($this->proposal, $this->data);

        $this->assertSame($this->proposal->refresh()->initiator, $this->data['initiator']);
        $this->assertSame($this->proposal->status, $this->data['status']);
        $this->assertSame($this->proposal->agent_message, $this->data['agent_message']);
        $this->assertSame($this->proposal->price, $this->data['price']);
        $this->assertSame($this->proposal->pricing_unit, $this->data['pricing_unit']);
        $this->assertSame($this->proposal->seen_at_by_customer, $this->data['seen_at_by_customer']);
        $this->assertSame($this->proposal->rejection_reason, $this->data['rejection_reason']);
        $this->assertSame($this->proposal->rejection_message, $this->data['rejection_message']);
    }

    public function testDispatchesProposalUpdatedEvent(): void
    {
        Event::fake();

        $this->action->execute($this->proposal, $this->data);

        Event::assertDispatched(fn (ProposalUpdated $evet) => $evet->proposal->is($this->proposal));
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->proposal = Proposal::factory()->create();
        $this->data = [
            'initiator' => ProposalInitiator::AGENT,
            'status' => ProposalStatus::PENDING,
            'agent_message' => 'agent message updated',
            'price' => 100,
            'pricing_unit' => PricingUnit::PER_DAY,
            'seen_at_by_customer' => null,
            'rejection_reason' => ProposalRejectionReason::MISSION_CANCELLED,
            'rejection_message' => 'mission was canceled by customer',
        ];
        $this->action = $this->app->make(UpdateProposalAction::class);
    }
}
