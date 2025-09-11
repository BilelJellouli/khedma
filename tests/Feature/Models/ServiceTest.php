<?php

declare(strict_types=1);

namespace Tests\Feature\Models;

use App\Models\Agent;
use App\Models\Mission;
use App\Models\Service;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ServiceTest extends TestCase
{
    use RefreshDatabase;

    public function testHasManyMissions(): void
    {
        $service = Service::factory()->create();
        Mission::factory(2)->for($service)->create();

        $this->assertInstanceOf(HasMany::class, $service->missions());
        $this->assertInstanceOf(Collection::class, $service->missions);
        $this->assertCount(2, $service->missions);
        $this->assertInstanceOf(Mission::class, $service->missions->first());
    }

    public function testBelongsToManyAgents(): void
    {
        $service = Service::factory()->create();
        $agent = Agent::factory()->create();

        $service->agents()->attach($agent);

        $this->assertInstanceOf(BelongsToMany::class, $service->agents());
        $this->assertInstanceOf(Collection::class, $service->agents);
        $this->assertInstanceOf(Agent::class, $service->agents->first());
    }
}
