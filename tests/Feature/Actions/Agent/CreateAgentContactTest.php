<?php

declare(strict_types=1);

namespace Feature\Actions\Agent;

use App\Actions\Agent\CreateAgentContactAction;
use App\Enums\AgentContactType;
use App\Events\Agent\AgentContactCreated;
use App\Models\Agent;
use App\Models\AgentContact;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class CreateAgentContactTest extends TestCase
{
    use RefreshDatabase;

    private Agent $agent;

    private array $data;

    private CreateAgentContactAction $action;

    public function testCreateAgentContactAndReturn(): void
    {
        $this->assertDatabaseEmpty(AgentContact::class);

        $contact = $this->action->execute($this->agent, $this->data);

        $this->assertInstanceOf(AgentContact::class, $contact);
        $this->assertDatabaseCount(AgentContact::class, 1);
        $this->assertSame($this->data['type'], $contact->type);
        $this->assertSame($this->data['value'], $contact->value);
        $this->assertSame($this->data['is_primary'], $contact->is_primary);
        $this->assertTrue($contact->agent->is($this->agent));
    }

    public function testDispatchesAgentContactCreated(): void
    {
        Event::fake();

        $contact = $this->app->make(CreateAgentContactAction::class)
            ->execute($this->agent, $this->data);

        Event::assertDispatched(fn (AgentContactCreated $event) => $event->contact->is($contact));
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->agent = Agent::factory()->create();
        $this->data = [
            'type' => AgentContactType::PHONE,
            'value' => 'phone_number',
            'is_primary' => true,
        ];
        $this->action = $this->app->make(CreateAgentContactAction::class);
    }
}
