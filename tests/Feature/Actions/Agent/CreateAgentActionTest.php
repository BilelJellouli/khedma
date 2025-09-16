<?php

declare(strict_types=1);

namespace Tests\Feature\Actions\Agent;

use App\Actions\Agent\CreateAgentAction;
use App\Enums\AgentAvailability;
use App\Events\Agent\AgentCreated;
use App\Models\Agent;
use App\Models\Service;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class CreateAgentActionTest extends TestCase
{
    public $service;

    use RefreshDatabase;

    private User $user;

    private Collection $services;

    private array $data;

    private CreateAgentAction $action;

    public function testCreatesAnAgentAndReturn(): void
    {
        $this->assertDatabaseEmpty(Agent::class);
        $agent = $this->action->execute($this->user, Service::get(), $this->data);
        $this->assertInstanceOf(Agent::class, $agent);
        $this->assertDatabaseCount(Agent::class, 1);
        $this->assertSame($agent->bio, $this->data['bio']);
        $this->assertSame($agent->experience, $this->data['experience']);
        $this->assertSame($agent->skills, $this->data['skills']);
        $this->assertCount(1, $agent->services);
        $this->assertTrue($agent->services->contains($this->service));
        $this->assertTrue($agent->user->is($this->user));
    }

    public function testDispatchesAgentCreated(): void
    {
        Event::fake();

        $agent = $this->app->make(CreateAgentAction::class)
            ->execute($this->user, Service::get(), $this->data);

        Event::assertDispatched(fn (AgentCreated $event) => $event->agent->is($agent));
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->agent()->make();
        $this->user->save();

        $this->service = Service::factory()->create();
        $this->data = [
            'bio' => 'bio text',
            'experience' => 'experience text',
            'skills' => ['skill_1', 'skill_2'],
            'availability' => AgentAvailability::FULL_TIME,
        ];
        $this->action = $this->app->make(CreateAgentAction::class);
    }
}
