<?php

declare(strict_types=1);

namespace Tests\Feature\Actions\User;

use App\Actions\User\CreateCustomerUserAction;
use App\Enums\UserRole;
use App\Events\Users\CustomerUserCreated;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class CreateCustomerUserActionTest extends TestCase
{
    use RefreshDatabase;

    private array $data;

    private CreateCustomerUserAction $action;

    public function test_create_customer_and_return_user(): void
    {
        $this->assertDatabaseCount(User::class, 0);

        $user = $this->action->execute($this->data);

        $this->assertInstanceOf(User::class, $user);
        $this->assertDatabaseCount(User::class, 1);
        $this->assertSame($user->role, UserRole::CUSTOMER);
    }

    public function test_dispatches_customer_user_created(): void
    {
        Event::fake();

        $user = $this->app->make(CreateCustomerUserAction::class)->execute($this->data);

        Event::assertDispatched(fn (CustomerUserCreated $event) => $event->user->is($user));
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->data = [
            'name' => 'Customer name',
            'email' => 'customer@example.com',
            'password' => 'password',
        ];
        $this->action = $this->app->make(CreateCustomerUserAction::class);
    }
}
