<?php

declare(strict_types=1);

namespace Tests\Feature\Actions\Authentication;

use App\Actions\Authentication\RegisterUserAction;
use App\Actions\User\CreateAgentUserAction;
use App\Actions\User\CreateCustomerUserAction;
use App\Enums\UserRole;
use App\Events\Authentication\UserRegistered;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class RegisterUserActionTest extends TestCase
{
    use RefreshDatabase;

    private array $data;

    private RegisterUserAction $action;

    public function testUsesCreateCustomerUserAction(): void
    {
        $spy = $this->spy(CreateCustomerUserAction::class);

        $registerAgentAction = $this->spy(CreateAgentUserAction::class);

        $this->app->make(RegisterUserAction::class)->execute(UserRole::CUSTOMER, $this->data);

        $spy->shouldHaveReceived('execute')
            ->once()
            ->withArgs(fn (array $attributes): bool => $attributes === $this->data);

        $registerAgentAction->shouldNotHaveReceived('execute');
    }

    public function testUsesCreateAgentUserAction(): void
    {
        $spy = $this->spy(CreateAgentUserAction::class);

        $registerCustomerAction = $this->spy(CreateCustomerUserAction::class);

        $this->app->make(RegisterUserAction::class)->execute(UserRole::AGENT, $this->data);

        $spy->shouldHaveReceived('execute')
            ->once()
            ->withArgs(fn (array $attributes): bool => $attributes === $this->data);

        $registerCustomerAction->shouldNotHaveReceived('execute');
    }

    public function testDispatchesUserRegisteredEvent(): void
    {
        Event::fake();

        $mock = $this->mock(CreateCustomerUserAction::class);
        $mock->shouldReceive('execute')
            ->once()
            ->with($this->data)
            ->andReturn($user = User::factory()->create());

        $this->app->make(RegisterUserAction::class)->execute(UserRole::CUSTOMER, $this->data);

        Event::assertDispatched(fn (UserRegistered $event) => $event->user->is($user));
    }

    public function testThrowsInvalidArgumentExceptionWithUnpermittedUserRoles(): void
    {
        $this->assertThrows(
            fn (): \App\Models\User => $this->action->execute(UserRole::ADMIN, $this->data),
            \InvalidArgumentException::class,
        );
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->data = [
            'name' => 'Jane Doe',
            'email' => 'email@example.com',
            'password' => 'password',
        ];

        $this->action = $this->app->make(RegisterUserAction::class);
    }
}
