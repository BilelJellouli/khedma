<?php

declare(strict_types=1);

namespace Tests\Feature\Actions\Mission;

use App\Actions\Mission\CreateMissionAction;
use App\Enums\MissionStatus;
use App\Enums\MissionType;
use App\Events\Mission\MissionCreated;
use App\Models\Mission;
use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class CreateMissionActionTest extends TestCase
{
    use RefreshDatabase;

    private array $data;

    private User $user;

    private Service $service;

    private CreateMissionAction $action;

    public function testCreateMissionAndReturn(): void
    {
        $this->assertDatabaseEmpty(Mission::class);

        $mission = $this->action->execute($this->user, $this->data);

        $this->assertInstanceOf(Mission::class, $mission);
        $this->assertDatabaseHas(Mission::class, $mission->toArray());

        $this->assertSame($this->data['title'], $mission->title);
        $this->assertSame($this->data['description'], $mission->description);
        $this->assertSame($this->data['status'], $mission->status);
        $this->assertSame($this->data['type'], $mission->type);
        $this->assertSame($this->data['budget'], $mission->budget);
        $this->assertSame($this->data['location'], $mission->location);
        $this->assertTrue($mission->service->is($this->service));
        $this->assertTrue($mission->customer->is($this->user));
    }

    public function testDispatchesMissionCreatedEvent(): void
    {
        Event::fake();

        $mission = $this->app->make(CreateMissionAction::class)
            ->execute($this->user, $this->data);

        Event::assertDispatched(fn (MissionCreated $event) => $event->mission->is($mission));
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->customer()->create();
        $this->service = Service::factory()->create();
        $this->data = [
            'service_id' => $this->service->id,
            'title' => 'Mission title',
            'description' => 'Mission description',
            'location' => 'Location',
            'status' => MissionStatus::LIVE,
            'type' => MissionType::ONE_TIME,
            'budget' => '500 Dinar',
        ];
        $this->action = $this->app->make(CreateMissionAction::class);
    }
}
