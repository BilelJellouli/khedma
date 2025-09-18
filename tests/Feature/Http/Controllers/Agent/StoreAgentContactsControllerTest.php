<?php

declare(strict_types=1);

namespace Feature\Http\Controllers\Agent;

use App\Actions\Agent\CreateAgentContactAction;
use App\Enums\AgentContactType;
use App\Models\Agent;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StoreAgentContactsControllerTest extends TestCase
{
    use RefreshDatabase;

    private Agent $agent;

    private User $user;

    private array $data;

    private string $route;

    public function testCreatesAgentContactAndReturnCreated(): void
    {
        $this->postJson($this->route, $this->data)
            ->assertCreated()
            ->assertJsonStructure(['data']);
    }

    public function testUsesCreateAgentContactAction(): void
    {
        $spy = $this->spy(CreateAgentContactAction::class);

        $this->postJson($this->route, $this->data);

        $spy->shouldHaveReceived('execute')
            ->once()
            ->withArgs(
                fn (Agent $agent, array $data): bool => $agent->is($this->agent) && $data === $this->data
            );
    }

    public function testRouteMiddleware(): void
    {
        $this->assertRouteMiddleware(
            'agents.contacts.store',
            'api', 'auth:sanctum'
        );
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->agent = Agent::factory()->create();
        $this->user = $this->agent->user;
        $this->data = [
            'type' => AgentContactType::PHONE->value,
            'value' => 'value',
            'is_primary' => true,
        ];
        $this->route = route('agents.contacts.store');
        $this->actingAs($this->user);
    }
}
