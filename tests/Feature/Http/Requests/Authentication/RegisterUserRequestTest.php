<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Requests\Authentication;

use App\Enums\UserRole;
use App\Http\Requests\Authentication\RegisterUserRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\Rules\Password;
use Tests\TestCase;

class RegisterUserRequestTest extends TestCase
{
    use RefreshDatabase;

    private string $route;

    public function testFailsWithoutRequiredFields(): void
    {
        $this->postJson($this->route, [])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['name', 'email', 'password']);
    }

    public function testValidEmailAddress(): void
    {
        $this->postJson($this->route, ['email' => 'invalid_email'])
            ->assertUnprocessable()
            ->assertJsonValidationErrorFor('email');
    }

    public function testNotAlreadyExistingEmail(): void
    {
        $user = User::factory()->create();

        $this->postJson($this->route, ['email' => $user->email])
            ->assertUnprocessable()
            ->assertJsonValidationErrorFor('email');
    }

    public function testNotAlreadyDeactivatedAccountEmail(): void
    {
        $user = User::factory()->deactivated()->create();

        $this->postJson($this->route, ['email' => $user->email])
            ->assertUnprocessable()
            ->assertJsonValidationErrorFor('email');
    }

    public function testNotBannedAccountEmail(): void
    {
        $user = User::factory()->banned()->create();

        $this->postJson($this->route, ['email' => $user->email])
            ->assertUnprocessable()
            ->assertJsonValidationErrorFor('email');
    }

    public function testPasswordNeedToSendConfirmedPassword(): void
    {
        $this->postJson($this->route, ['password' => 'password'])
            ->assertUnprocessable()
            ->assertJsonPath('errors.password.0', __('validation.confirmed', ['attribute' => 'password']));
    }

    public function testPasswordMustBeConfirmed(): void
    {
        $this->postJson($this->route, ['password' => 'password', 'password_confirmation' => 'password'])
            ->assertUnprocessable()
            ->assertDontSee(__('validation.confirmed', ['attribute' => 'password']));
    }

    public function testPasswordRules(): void
    {
        $passwordRule = Password::min(8)->mixedCase()->symbols()->numbers();

        $requestRules = new RegisterUserRequest()->rules();

        $this->assertSame((array) $passwordRule, (array) $requestRules['password'][2]);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->route = route('auth.register', ['userRole' => UserRole::AGENT]);
    }
}
