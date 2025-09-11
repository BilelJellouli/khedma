<?php

declare(strict_types=1);

namespace Tests\Feature\Actions\Authentication;

use App\Actions\Authentication\LoginUserAction;
use App\Events\Authentication\UserLoggedIn;
use App\Exceptions\UserBannedException;
use App\Exceptions\UserDoNotExistsException;
use App\Exceptions\WrongCredentialException;
use App\Models\User;
use App\Types\LoggedInUser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class LoginUserActionTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    private LoginUserAction $action;

    public function testLoginUser(): void
    {
        $this->assertGuest();
        $this->action->execute($this->user->email, self::DEFAULT_PASSWORD);
        $this->assertAuthenticatedAs($this->user);
    }

    public function testThrowsUserDoNotExistsException(): void
    {
        $this->assertThrows(
            fn (): \App\Models\User|\App\Types\LoggedInUser => $this->action->execute('not_existing_user@email.com', self::DEFAULT_PASSWORD),
            UserDoNotExistsException::class
        );
    }

    public function testThrowsWrongCredentialException(): void
    {
        $this->assertThrows(
            fn (): \App\Models\User|\App\Types\LoggedInUser => $this->action->execute($this->user->email, 'wrong_password'),
            WrongCredentialException::class
        );
    }

    public function testThrowsUserBannedException(): void
    {
        $bannedUser = User::factory()->banned()->create(['password' => bcrypt(self::DEFAULT_PASSWORD)]);
        $this->assertThrows(
            fn (): \App\Models\User|\App\Types\LoggedInUser => $this->action->execute($bannedUser->email, self::DEFAULT_PASSWORD),
            UserBannedException::class
        );
    }

    public function testDispatchesUserLoggedIn(): void
    {
        Event::fake();

        $user = $this->app->make(LoginUserAction::class)->execute($this->user->email, self::DEFAULT_PASSWORD);

        Event::assertDispatched(fn (UserLoggedIn $event): bool => $event->user->is($user));
    }

    public function testApiLoginReturnsLoggedInUser(): void
    {
        $loggedInUser = $this->action->apiLogin('test')->execute($this->user->email, self::DEFAULT_PASSWORD);
        $this->assertInstanceOf(LoggedInUser::class, $loggedInUser);
        $this->assertTrue($loggedInUser->user->is($this->user));
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create(['password' => self::DEFAULT_PASSWORD]);
        $this->action = $this->app->make(LoginUserAction::class);
    }
}
