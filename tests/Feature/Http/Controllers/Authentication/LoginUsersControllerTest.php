<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Authentication;

use App\Actions\Authentication\LoginUserAction;
use App\Models\User;
use App\Types\LoggedInUser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginUsersControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    private array $data;

    private string $route;

    public function testLoginUserAndReturnSuccessfully(): void
    {
        $this->post($this->route, $this->data)
            ->assertOk()
            ->assertJsonStructure([
                'data' => [
                    'user',
                    'accessToken',
                ]
            ]);
    }

    public function testUsesLoginUserAction(): void
    {
        $loginUser = $this->mock(LoginUserAction::class);

        $loginUser->shouldReceive('apiLogin')
            ->withAnyArgs()
            ->andReturnSelf();

        $loginUser->shouldReceive('execute')
            ->withArgs(fn (string $email, string $password): bool => $email === $this->user->email && $password === self::DEFAULT_PASSWORD)
            ->andReturn(new LoggedInUser($this->user, $accessToken = 'plain_text_access_token'));

        $this->postJson($this->route, $this->data)
            ->assertSee($accessToken);
    }

    public function testRouteMiddlewares(): void
    {
        $this->assertRouteMiddleware(
            'auth.login',
            'api', 'guest'
        );
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create(['password' => self::DEFAULT_PASSWORD]);
        $this->data = [
            'email' => $this->user->email,
            'password' => self::DEFAULT_PASSWORD,
        ];
        $this->route = route('auth.login');
    }
}
