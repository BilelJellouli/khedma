<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Missions;

use App\Actions\Mission\UpdateMissionAction;
use App\Enums\MissionStatus;
use App\Enums\MissionType;
use App\Models\Mission;
use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UpdateMissionsControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    private Mission $mission;

    private array $data;

    private string $route;

    public function testUpdatesMissionAndReturn(): void
    {
        $this->putJson($this->route, $this->data)
            ->assertOk();
    }

    public function testUsesUpdateMissionAction(): void
    {
        $spy = $this->spy(UpdateMissionAction::class);

        $this->putJson($this->route, $this->data);

        $spy->shouldHaveReceived('execute')
            ->once()
            ->withArgs(fn (Mission $mission, array $data): bool => $mission->is($this->mission) &&
                $data['title'] === $this->data['title'] &&
                $data['description'] === $this->data['description'] &&
                $data['location'] === $this->data['location'] &&
                $data['type'] === $this->data['type'] &&
                $data['budget'] === $this->data['budget'] &&
                $data['service_id'] === $this->data['service_id'] &&
                $data['status'] === MissionStatus::LIVE
            );
    }

    public function testPassesMissionStatusLiveIfPublished(): void
    {
        $spy = $this->spy(UpdateMissionAction::class);

        $this->putJson($this->route, [
            ...$this->data,
            'published' => true,
        ]);

        $spy->shouldHaveReceived('execute')
            ->once()
            ->withArgs(fn (Mission $mission, array $data): bool => $data['status'] === MissionStatus::LIVE);
    }

    public function testPassesMissionStatusPendingIfPublished(): void
    {
        $spy = $this->spy(UpdateMissionAction::class);

        $this->putJson($this->route, [
            ...$this->data,
            'published' => false,
        ]);

        $spy->shouldHaveReceived('execute')
            ->once()
            ->withArgs(fn (Mission $mission, array $data): bool => $data['status'] === MissionStatus::PENDING);
    }

    public function testRouteMiddleware(): void
    {
        $this->assertRouteMiddleware(
            'missions.update',
            'api', 'auth:sanctum'
        );
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->customer()->create();
        $this->mission = Mission::factory()->for($this->user, 'customer')->create();
        $this->actingAs($this->user);
        $this->data = [
            'title' => 'title',
            'description' => 'description',
            'location' => 'location',
            'published' => true,
            'type' => MissionType::ONE_TIME->value,
            'budget' => 'budget',
            'service_id' => Service::factory()->create()->id,
        ];
        $this->route = route('missions.update', ['mission' => $this->mission]);
    }
}
