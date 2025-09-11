<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Requests\Mission;

use App\Http\Requests\Mission\CreateMissionRequest;
use App\Http\Requests\Mission\UpdateMissionRequest;
use App\Models\Mission;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UpdateMissionRequestTest extends TestCase
{
    public $route;

    public $mission;

    use RefreshDatabase;

    public function testIsSubClassOfCreateMissionRequest(): void
    {
        $this->assertTrue(
            is_subclass_of(UpdateMissionRequest::class, CreateMissionRequest::class),
        );
    }

    public function testAllowsAdminToEditAnyMission(): void
    {
        $this->actingAs(User::factory()->admin()->create());
        $this->putJson($this->route)
            ->assertUnprocessable();
    }

    public function testAllowsMissionCreatorToUpdateHisMission(): void
    {
        $user = $this->mission->customer;
        $this->actingAs($user);
        $this->putJson($this->route)
            ->assertUnprocessable();
    }

    public function testDenyUpdatingOthersMissions(): void
    {
        $agent = User::factory()->customer()->create();
        $this->actingAs($agent);
        $this->putJson($this->route)->assertForbidden();

        $customer = User::factory()->customer()->create();
        $this->actingAs($customer);
        $this->putJson($this->route)->assertForbidden();
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->mission = Mission::factory()->create();
        $this->route = route('missions.update', ['mission' => $this->mission]);
    }
}
