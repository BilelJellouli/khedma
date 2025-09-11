<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Authentication;

use App\Actions\Authentication\RegisterUserAction;
use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegisterUsersControllerTest extends TestCase
{
    use RefreshDatabase;

    private string $route;

    private UserRole $userRole;

    private array $data;

    public function testUsesRegisterAndReturnCreated(): void
    {
        $this->postJson($this->route, $this->data)
            ->assertCreated()
            ->assertJsonStructure([
                'user',
                'accessToken',
            ]);
    }

    public function testUsesRegisterUserAction(): void
    {
        $registerUser = $this->mock(RegisterUserAction::class);

        $registerUser->shouldReceive('execute')
            ->withArgs(fn (UserRole $role, array $data): bool => $role === $this->userRole)
            ->andReturn(User::factory()->create(['password' => bcrypt(self::DEFAULT_PASSWORD)]));

        $this->postJson($this->route, $this->data)->assertCreated();
    }

    public function testRouteMiddlewares(): void
    {
        $this->assertRouteMiddleware(
            'auth.register',
            'api', 'guest'
        );
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->data = [
            'name' => 'John Doe',
            'email' => 'email@exmaple.com',
            'password' => self::DEFAULT_PASSWORD,
            'password_confirmation' => self::DEFAULT_PASSWORD,
        ];

        $this->userRole = UserRole::CUSTOMER;

        $this->route = route('auth.register', ['userRole' => $this->userRole]);
    }
}
