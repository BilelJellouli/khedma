<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers;

use App\Actions\Agent\CreateAgentAction;
use App\Enums\AgentAvailability;
use App\Models\Service;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StoreAgentsControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    private string $route;

    private array $data;

    public function testCreatesAgentAndReturn(): void
    {
        $this->postJson($this->route, $this->data)
            ->assertCreated();
    }

    public function testUsesCreateAgentAction(): void
    {
        $spy = $this->spy(CreateAgentAction::class);

        $this->postJson($this->route, $this->data);

        $spy->shouldHaveReceived('execute')
            ->once()
            ->withArgs(
                fn (User $user, Collection $services, array $data): bool => $user->is($this->user) &&
                    $services->pluck('id')->sort()->toArray() === Service::get()->pluck('id')->sort()->toArray() &&
                    $data['bio'] === $this->data['bio'] &&
                    $data['experience'] === $this->data['experience'] &&
                    $data['skills'] === $this->data['skills'] &&
                    $data['availability'] === $this->data['availability']
            );
    }

    public function testRouteMiddleware(): void
    {
        $this->assertRouteMiddleware(
            'agents.store',
            'api', 'auth:sanctum'
        );
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->agent()->make();
        $this->user->save();

        $this->data = [
            'bio' => 'bio text',
            'experience' => 'experience text',
            'skills' => ['skill_1', 'skill_2'],
            'availability' => AgentAvailability::FULL_TIME->value,
            'services' => Service::factory(5)->create()->pluck('id')->toArray(),
        ];
        $this->route = route('agents.store');
        $this->actingAs($this->user);
    }
}
