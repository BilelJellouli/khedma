<?php

declare(strict_types=1);

namespace Feature\Actions\Proposal;

use App\Actions\Proposal\CreateProposalAction;
use App\Enums\PricingUnit;
use App\Enums\ProposalInitiator;
use App\Enums\ProposalRejectionReason;
use App\Enums\ProposalStatus;
use App\Events\Proposal\ProposalCreated;
use App\Models\Agent;
use App\Models\Mission;
use App\Models\Proposal;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class CreateProposalActionTest extends TestCase
{
    use RefreshDatabase;

    private CreateProposalAction $action;

    private Agent $agent;

    private Mission $mission;

    private array $data;

    public function testCreateProposal(): void
    {
        $this->assertDatabaseEmpty(Proposal::class);

        $proposal = $this->action->execute($this->agent, $this->mission, $this->data);

        $this->assertInstanceOf(Proposal::class, $proposal);
        $this->assertDatabaseHas(Proposal::class, $this->data);
        $this->assertDatabaseCount(Proposal::class, 1);

        $this->assertSame($proposal->initiator, $this->data['initiator']);
        $this->assertSame($proposal->agent_message, $this->data['agent_message']);
        $this->assertSame($proposal->price, $this->data['price']);
        $this->assertSame($proposal->pricing_unit, $this->data['pricing_unit']);
    }

    public function testAssertNullFieldsAndInitialValues(): void
    {
        $proposal = $this->action->execute($this->agent, $this->mission, [
            ...$this->data,
            'seen_at_by_customer' => CarbonImmutable::now(),
            'rejection_reason' => ProposalRejectionReason::BUDGET,
            'rejection_message' => 'value',
            'status' => ProposalStatus::APPROVED,
        ]);

        $this->assertNull($proposal->seen_at_by_customer);
        $this->assertNull($proposal->rejection_reason);
        $this->assertNull($proposal->rejection_message);

        $this->assertSame(ProposalStatus::PENDING, $proposal->status);
    }

    public function testDispatchesProposalCreatedEvent(): void
    {
        Event::fake();

        $proposal = $this->app->make(CreateProposalAction::class)->execute($this->agent, $this->mission, $this->data);

        Event::assertDispatched(fn (ProposalCreated $event) => $event->proposal->is($proposal));
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->agent = Agent::factory()->create();
        $this->mission = Mission::factory()->create();
        $this->data = [
            'initiator' => ProposalInitiator::AGENT,
            'agent_message' => 'agent_message',
            'price' => 100,
            'pricing_unit' => PricingUnit::PER_DAY,
        ];
        $this->action = $this->app->make(CreateProposalAction::class);
    }
}
