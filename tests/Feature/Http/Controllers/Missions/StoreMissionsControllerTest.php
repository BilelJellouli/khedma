<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Missions;

use App\Actions\Mission\CreateMissionAction;
use App\Enums\MissionStatus;
use App\Enums\MissionType;
use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StoreMissionsControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    private array $data;

    private string $route;

    public function testCreateMissionAndReturnCreated(): void
    {
        $this->postJson($this->route, $this->data)
            ->assertCreated()
            ->assertJsonStructure([
                'data',
            ]);
    }

    public function testUsesCreateMissionAction(): void
    {
        $spy = $this->spy(CreateMissionAction::class);

        $this->postJson($this->route, $this->data);

        $spy->shouldHaveReceived('execute')
            ->once()
            ->withArgs(fn (User $customer, array $data): bool => $customer->is($this->user) &&
                $this->data['title'] === $data['title'] &&
                $this->data['description'] === $data['description'] &&
                $this->data['location'] === $data['location'] &&
                $this->data['type'] === $data['type'] &&
                $this->data['budget'] === $data['budget'] &&
                $this->data['service_id'] === $data['service_id'] &&
                $data['status'] === MissionStatus::LIVE
            );
    }

    public function testPassesMissionStatusLiveIfPublished(): void
    {
        $spy = $this->spy(CreateMissionAction::class);

        $this->postJson(
            $this->route,
            [...$this->data, 'published' => true]
        );

        $spy->shouldHaveReceived('execute')
            ->once()
            ->withArgs(fn (User $customer, array $data): bool => $data['status'] === MissionStatus::LIVE);
    }

    public function testPassesMissionStatusPendingIfPublished(): void
    {
        $spy = $this->spy(CreateMissionAction::class);

        $this->postJson(
            $this->route,
            [...$this->data, 'published' => false]
        );

        $spy->shouldHaveReceived('execute')
            ->once()
            ->withArgs(fn (User $customer, array $data): bool => $data['status'] === MissionStatus::PENDING);
    }

    public function testRequestCanNotOverrideStatus(): void
    {
        $spy = $this->spy(CreateMissionAction::class);

        $this->postJson(
            $this->route,
            [...$this->data, 'status' => MissionStatus::CANCELLED]
        );

        $spy->shouldHaveReceived('execute')
            ->once()
            ->withArgs(fn (User $customer, array $data): bool => $data['status'] === MissionStatus::LIVE);
    }

    public function testRouteMiddleware(): void
    {
        $this->assertRouteMiddleware(
            'missions.store',
            'api', 'auth:sanctum'
        );
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->customer()->create();
        $this->data = [
            'title' => 'title',
            'description' => 'description',
            'location' => 'location',
            'published' => true,
            'type' => MissionType::ONE_TIME->value,
            'budget' => 'budget',
            'service_id' => Service::factory()->create()->id,
        ];

        $this->actingAs($this->user);
        $this->route = route('missions.store');
    }
}
