<?php

declare(strict_types=1);

namespace Tests\Feature\Actions\Mission;

use App\Actions\Mission\DeleteMissionAction;
use App\Events\Mission\MissionDeleted;
use App\Models\Mission;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class DeleteMissionActionTest extends TestCase
{
    use RefreshDatabase;

    private Mission $mission;

    private DeleteMissionAction $action;

    public function testDeleteMissionFromDatabase(): void
    {
        $this->assertDatabaseCount(Mission::class, 1);
        $this->assertModelExists($this->mission);

        $this->action->execute($this->mission);

        $this->assertModelMissing($this->mission);
        $this->assertDatabaseEmpty(Mission::class);
    }

    public function testDispatchesMissionDeletedEvent(): void
    {
        Event::fake();

        $this->app->make(DeleteMissionAction::class)->execute($this->mission);

        Event::assertDispatched(fn (MissionDeleted $event) => $event->mission->is($this->mission));
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->mission = Mission::factory()->create();
        $this->action = $this->app->make(DeleteMissionAction::class);
    }
}
