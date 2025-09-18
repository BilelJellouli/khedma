<?php

namespace Feature\Http\Requests\Agent;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateAgentContactRequestTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private string $route;

    public function testDenyNonAgentUsers(): void
    {
        $this->actingAs(User::factory()->customer()->create())
            ->postJson($this->route)
            ->assertForbidden();

        $this->actingAs(User::factory()->admin()->create())
            ->postJson($this->route)
            ->assertForbidden();
    }

    public function testRequiredFields(): void
    {
        $this->postJson($this->route)
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['type', 'value']);
    }

    public function testTypeMustBeValidEnum(): void
    {
        $this->postJson($this->route, ['type' => 'INVALID_TYPE'])
            ->assertUnprocessable()
            ->assertJsonValidationErrorFor('type');
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->agent()->withAgentProfile()->create();
        $this->route = route('agents.contacts.store');
        $this->actingAs($this->user);
    }
}
