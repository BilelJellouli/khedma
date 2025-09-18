<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Requests\Agent;

use App\Models\Agent;
use App\Models\User;
use Illuminate\Support\Str;
use Tests\TestCase;

class CreateAgentRequestTest extends TestCase
{
    private User $user;

    private string $route;

    public function testDisallowNonAgentFromCreatingAgent(): void
    {
        $customer = User::factory()->customer()->create();
        $this->actingAs($customer)
            ->post($this->route)
            ->assertForbidden();

        $admin = User::factory()->admin()->create();
        $this->actingAs($admin)
            ->postJson($this->route)
            ->assertForbidden();
    }

    public function testDisallowAgentFromCreatingTwoProfiles(): void
    {
        Agent::factory()->for($this->user)->create();

        $this->postJson($this->route)
            ->assertForbidden();
    }

    public function testSkillsMustBeAnArray(): void
    {
        $this->postJson($this->route, ['skills' => 'NOT_ARRAY'])
            ->assertUnprocessable()
            ->assertJsonValidationErrorFor('skills');
    }

    public function testAvailabilityMustBeValidEnum(): void
    {
        $this->postJson($this->route, ['availability' => 'INCORRECT_VALUE'])
            ->assertUnprocessable()
            ->assertJsonValidationErrorFor('availability');
    }

    public function testServicesContainsMaxFive(): void
    {
        $this->postJson($this->route, ['services' => [1, 2, 3, 4, 5, 6]])
            ->assertUnprocessable()
            ->assertJsonValidationErrorFor('services')
            ->assertSee(__('validation.max.array', ['attribute' => 'services', 'max' => 5]));
    }

    public function testServiceMustBeUuid(): void
    {
        $this->postJson($this->route, ['services' => ['NOT_A_UUID']])
            ->assertUnprocessable()
            ->assertJsonValidationErrorFor('services.0')
            ->assertSee(__('validation.uuid', ['attribute' => 'services.0']));
    }

    public function testMustBeExistingService(): void
    {
        $this->postJson($this->route, ['services' => [Str::uuid()->toString()]])
            ->assertUnprocessable()
            ->assertJsonValidationErrorFor('services.0')
            ->assertSee(__('validation.exists', ['attribute' => 'services.0']));
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->agent()->make();
        $this->user->save();

        $this->route = route('agents.store');
        $this->actingAs($this->user);
    }
}
