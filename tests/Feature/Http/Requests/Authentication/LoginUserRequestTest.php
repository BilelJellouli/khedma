<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Requests\Authentication;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginUserRequestTest extends TestCase
{
    use RefreshDatabase;

    private string $route;

    public function testFailWithoutRequiredFields(): void
    {
        $this->postJson($this->route, [])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['email', 'password']);
    }

    public function testTestFailsWithinValidEmailAddress(): void
    {
        $this->postJson($this->route, ['email' => 'not_an_email'])
            ->assertUnprocessable()
            ->assertJsonValidationErrorFor('email');
    }

    public function testForNotRegisteredUser(): void
    {
        $this->postJson($this->route, ['email' => 'not_registered@email.com'])
            ->assertUnprocessable()
            ->assertJsonValidationErrorFor('email');
    }

    public function testFailsWithBannedUser(): void
    {
        $user = User::factory()->banned()->create();
        $this->postJson($this->route, ['email' => $user->email])
            ->assertUnprocessable()
            ->assertJsonValidationErrorFor('email');
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->route = route('auth.login');
    }
}
