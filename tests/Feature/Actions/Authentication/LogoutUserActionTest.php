<?php

declare(strict_types=1);

namespace Tests\Feature\Actions\Authentication;

use App\Actions\Authentication\LogoutUserAction;
use App\Events\Authentication\UserLoggedOut;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class LogoutUserActionTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    private LogoutUserAction $action;

    public function testLogUserOut(): void
    {
        $this->actingAs($this->user);

        $this->assertAuthenticatedAs($this->user);

        $this->action->execute($this->user);

        $this->assertGuest();
    }

    public function testDispatchesUserLoggedOutEvent(): void
    {
        Event::fake();

        $this->app->make(LogoutUserAction::class)->execute($this->user);

        Event::assertDispatched(fn (UserLoggedOut $event) => $event->user->is($this->user));
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->action = $this->app->make(LogoutUserAction::class);
    }
}
