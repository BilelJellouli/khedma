<?php

declare(strict_types=1);

namespace Tests\Feature\Actions\Mission;

use App\Actions\Mission\UpdateMissionAction;
use App\Enums\MissionStatus;
use App\Enums\MissionType;
use App\Events\Mission\MissionUpdated;
use App\Models\Mission;
use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class UpdateMissionActionTest extends TestCase
{
    use RefreshDatabase;

    private array $data;

    private User $user;

    private Service $service;

    private Mission $mission;

    private UpdateMissionAction $action;

    public function testUpdateMissionAndReturn(): void
    {
        $this->assertFalse($this->mission->service->is($this->service));

        $mission = $this->action->execute($this->mission, $this->data);

        $this->assertInstanceOf(Mission::class, $mission->refresh());

        $this->assertSame($this->data['title'], $mission->title);
        $this->assertSame($this->data['description'], $mission->description);
        $this->assertSame($this->data['status'], $mission->status);
        $this->assertSame($this->data['type'], $mission->type);
        $this->assertSame($this->data['budget'], $mission->budget);
        $this->assertSame($this->data['location'], $mission->location);
        $this->assertTrue($mission->service->is($this->service));
    }

    public function testActionWontOverrideCustomer(): void
    {
        $user = User::factory()->create();

        $mission = $this->action->execute($this->mission, [
            ...$this->data,
            'customer_id' => $user->id,
        ]);

        $this->assertFalse($mission->customer->is($user));
    }

    public function testDispatchesMissionUpdatedEvent(): void
    {
        Event::fake();

        $mission = $this->app->make(UpdateMissionAction::class)
            ->execute($this->mission, $this->data);

        Event::assertDispatched(fn (MissionUpdated $event) => $event->mission->is($mission));
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->mission = Mission::factory()->create([
            'type' => MissionType::PART_TIME,
            'status' => MissionStatus::PENDING,
        ]);

        $this->service = Service::factory()->create();

        $this->data = [
            'service_id' => $this->service->id,
            'title' => 'Mission title updated',
            'description' => 'Mission description updated',
            'location' => 'Location updated',
            'status' => MissionStatus::LIVE,
            'type' => MissionType::ONE_TIME,
            'budget' => 'Updated updated',
        ];
        $this->action = $this->app->make(UpdateMissionAction::class);
    }
}
