<?php

namespace Feature\Http\Controllers\Missions;

use App\Actions\Mission\DeleteMissionAction;
use App\Models\Mission;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeleteMissionsControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Mission $mission;
    private string $route;

    public function testDeleteMissionAndReturnNoContent(): void
    {
        $this->actingAs($this->user)
            ->deleteJson($this->route)
            ->assertNoContent();
    }

    public function testUsesDeleteMissionAction(): void
    {
        $spy = $this->spy(DeleteMissionAction::class);

        $this->actingAs($this->user)->deleteJson($this->route);

        $spy->shouldHaveReceived('execute')
            ->once()
            ->withArgs(fn (Mission $mission) => $mission->is($this->mission));
    }

    public function testAllowAdminUserToDeleteMission(): void
    {
        $adminUser = User::factory()->admin()->create();

        $this->actingAs($adminUser)
            ->deleteJson($this->route)
            ->assertNoContent();
    }

    public function testForbidOtherUserToDeleteOthersMissions(): void
    {
        $user = User::factory()->notAdmin()->create();

        $this->actingAs($user)
            ->deleteJson($this->route)
            ->assertForbidden();
    }

    public function testRouteMiddleware(): void
    {
        $this->assertRouteMiddleware(
            'missions.delete',
            'api', 'auth:sanctum'
        );
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->customer()->create();
        $this->mission = Mission::factory()->for($this->user, 'customer')->create();
        $this->route = route('missions.delete', ['mission' => $this->mission]);
    }
}
