<?php

declare(strict_types=1);

namespace Tests\Feature\Actions\User;

use App\Actions\User\CreateAgentUserAction;
use App\Enums\UserRole;
use App\Events\User\AgentUserCreated;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class CreateAgentUserActionTest extends TestCase
{
    use RefreshDatabase;

    private array $data;

    private CreateAgentUserAction $action;

    public function testCreateCustomerAndReturnUser(): void
    {
        $this->assertDatabaseCount(User::class, 0);

        $user = $this->action->execute($this->data);

        $this->assertInstanceOf(User::class, $user);
        $this->assertDatabaseCount(User::class, 1);
        $this->assertSame($user->role, UserRole::AGENT);
    }

    public function testDispatchesCustomerUserCreated(): void
    {
        Event::fake();

        $user = $this->app->make(CreateAgentUserAction::class)->execute($this->data);

        Event::assertDispatched(fn (AgentUserCreated $event) => $event->user->is($user));
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->data = [
            'name' => 'Customer name',
            'email' => 'customer@example.com',
            'password' => 'password',
        ];
        $this->action = $this->app->make(CreateAgentUserAction::class);
    }
}
