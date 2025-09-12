<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Missions;

use App\Models\Mission;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ListMissionsControllerTest extends TestCase
{
    use RefreshDatabase;

    private string $route;

    public function testListMissionsForAdmin(): void
    {
        // Admin can see all from all customers and all statues
        $admin = User::factory()->admin()->create();

        Mission::factory()->live()->create();
        Mission::factory()->pending()->create();
        Mission::factory()->for(User::factory()->customer(), 'customer')->create();
        Mission::factory()->for(User::factory()->customer(), 'customer')->create();

        $this->actingAs($admin)
            ->getJson($this->route)
            ->assertOk()
            ->assertJsonCount(4, 'data');
    }

    public function testListMissionForAgent(): void
    {
        // Agent can see only live missions
        $agent = User::factory()->agent()->create();

        Mission::factory()->live()->create(['title' => 'live']);
        Mission::factory()->pending()->create(['title' => 'pending']);

        $this->actingAs($agent)
            ->getJson($this->route)
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertDontSee('pending');
    }

    public function testListMissionsForCustomer(): void
    {
        // A customer can only see his own missions
        // and no matter what status
        $customer = User::factory()->customer()->create();

        Mission::factory()->live()->for($customer, 'customer')->create();
        Mission::factory()->cancelled()->for($customer, 'customer')->create();
        Mission::factory()->for(User::factory()->customer(), 'customer')->create(['title' => 'other_customer_mission']);

        $this->actingAs($customer)
            ->getJson($this->route)
            ->assertOk()
            ->assertJsonCount(2, 'data')
            ->assertDontSee('other_customer_mission');
    }

    public function testRouteMiddleware(): void
    {
        $this->assertRouteMiddleware(
            'missions.list',
            'api', 'auth:sanctum'
        );
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->route = route('missions.list');
    }
}
