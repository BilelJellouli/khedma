<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Missions;

use App\Models\Mission;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShowMissionsControllerTest extends TestCase
{
    use RefreshDatabase;

    private Mission $mission;

    private string $route;

    public function testReturnShowedMethod(): void
    {
        $this->actingAs($this->mission->customer);

        $this->getJson($this->route)
            ->assertOk()
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'title',
                    'description',
                    'location',
                    'status',
                    'type',
                    'budget',
                    'service',
                    'proposals',
                ],
            ]);
    }

    public function testAgentWontSeeProposals(): void
    {
        $user = User::factory()->agent()->create();
        $this->actingAs($user);

        $this->getJson($this->route)
            ->assertDontSee('proposals');
    }

    public function testAssertWontLoadCustomerIfSeeingHisOwnMission(): void
    {
        $this->actingAs($this->mission->customer);

        $this->getJson($this->route)
            ->assertDontSee('customer');
    }

    public function testRouteMiddlewares(): void
    {
        $this->assertRouteMiddleware(
            'missions.show',
            'api', 'auth:sanctum'
        );
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->mission = Mission::factory()->create();
        $this->route = route('missions.show', ['mission' => $this->mission]);
    }
}
