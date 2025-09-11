<?php

declare(strict_types=1);

namespace Tests\Feature\Actions\User;

use App\Actions\User\CreateUserAction;
use App\Enums\UserRole;
use App\Events\Users\UserCreated;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class CreateUserActionTest extends TestCase
{
    use RefreshDatabase;

    private array $data;

    private CreateUserAction $action;

    public function testCreateUserAndReturn(): void
    {
        $this->assertDatabaseEmpty(User::class);

        $user = $this->action->execute($this->data);

        $this->assertInstanceOf(User::class, $user);
        $this->assertDatabaseCount(User::class, 1);

        $this->assertSame($user->name, $this->data['name']);
        $this->assertSame($user->email, $this->data['email']);
        $this->assertTrue(Hash::check($this->data['password'], $user->password));
    }

    public function testWillAssignRandomPasswordIfPasswordWasNotSet(): void
    {
        $user = $this->action->execute(Arr::except($this->data, 'password'));
        $this->assertTrue($user->random_password);
        $this->assertNotNull($user->password);
    }

    public function testDispatchesUserCreated(): void
    {
        Event::fake([UserCreated::class]);

        $user = $this->app->make(TestCreateUserAction::class)->execute($this->data);

        Event::assertDispatched(fn (UserCreated $event) => $event->user->is($user));
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->data = [
            'name' => 'John Doe',
            'email' => 'email@example.com',
            'password' => 'password',
        ];
        $this->action = $this->app->make(TestCreateUserAction::class);
    }
}

class TestCreateUserAction extends CreateUserAction
{
    protected UserRole $userRole = UserRole::ADMIN;
}
