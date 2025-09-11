<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Authentication;

use App\Actions\Authentication\LogoutUserAction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LogoutUsersControllerTest extends TestCase
{
    use RefreshDatabase;

    private string $route;

    private User $user;

    public function testLogoutAndReturnOk(): void
    {
        $this->postJson($this->route)
            ->assertNoContent();
    }

    public function testUsesLogoutUserAction(): void
    {
        $spy = $this->spy(LogoutUserAction::class);

        $this->postJson($this->route);

        $spy->shouldHaveReceived('execute')
            ->once()
            ->withArgs(fn (User $user) => $user->is($this->user));
    }

    public function testRouteMiddlewares(): void
    {
        $this->assertRouteMiddleware(
            'auth.logout',
            'api', 'auth:sanctum'
        );
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->actingAs($this->user);
        $this->route = route('auth.logout');
    }
}
