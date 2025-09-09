<?php

declare(strict_types=1);

namespace Feature\Models;

use App\Models\Mission;
use App\Models\Proposal;
use App\Models\Service;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MissionTest extends TestCase
{
    use RefreshDatabase;

    public function test_belongs_to_service(): void
    {
        $service = Service::factory()->create();
        $mission = Mission::factory()->for($service)->create();

        $this->assertInstanceOf(BelongsTo::class, $mission->service());
        $this->assertInstanceOf(Service::class, $mission->service);
        $this->assertTrue($mission->service->is($service));
    }

    public function test_belongs_to_customer(): void
    {
        $customer = User::factory()->customer()->create();
        $mission = Mission::factory()->for($customer, 'customer')->create();

        $this->assertInstanceOf(BelongsTo::class, $mission->customer());
        $this->assertInstanceOf(User::class, $mission->customer);
        $this->assertTrue($mission->customer->is($customer));
    }

    public function test_has_many_proposals(): void
    {
        $mission = Mission::factory()->create();
        Proposal::factory(2)->for($mission)->create();

        $this->assertInstanceOf(HasMany::class, $mission->proposals());
        $this->assertInstanceOf(Collection::class, $mission->proposals);
        $this->assertCount(2, $mission->proposals);
        $this->assertInstanceOf(Proposal::class, $mission->proposals->first());
    }
}
