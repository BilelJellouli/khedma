<?php

declare(strict_types=1);

namespace Tests\Feature\Models;

use App\Models\Agent;
use App\Models\AgentContact;
use App\Models\Proposal;
use App\Models\Rating;
use App\Models\Recommendation;
use App\Models\Service;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AgentTest extends TestCase
{
    use RefreshDatabase;

    public function testBelongsToUser(): void
    {
        $user = User::factory()->agent()->create();
        $agent = Agent::factory()->for($user)->create();

        $this->assertInstanceOf(BelongsTo::class, $agent->user());
        $this->assertInstanceOf(User::class, $agent->user);
        $this->assertTrue($agent->user->is($user));
    }

    public function testHasManyContacts(): void
    {
        $agent = Agent::factory()->create();
        AgentContact::factory(2)->for($agent)->create();

        $this->assertInstanceOf(HasMany::class, $agent->contacts());
        $this->assertInstanceOf(Collection::class, $agent->contacts);
        $this->assertCount(2, $agent->contacts);
        $this->assertInstanceOf(AgentContact::class, $agent->contacts->first());
    }

    public function testBelongToManyServices(): void
    {
        $agent = Agent::factory()->create();
        $service = Service::factory()->create();

        $agent->services()->attach($service);

        $this->assertInstanceOf(BelongsToMany::class, $agent->services());
        $this->assertInstanceOf(Collection::class, $agent->services);
        $this->assertInstanceOf(Service::class, $agent->services->first());
    }

    public function testHasManyProposal(): void
    {
        $agent = Agent::factory()->create();
        Proposal::factory(2)->for($agent)->create();

        $this->assertInstanceOf(HasMany::class, $agent->proposals());
        $this->assertInstanceOf(Collection::class, $agent->proposals);
        $this->assertInstanceOf(Proposal::class, $agent->proposals->first());
        $this->assertCount(2, $agent->proposals);
    }

    public function testHasManyRatings(): void
    {
        $agent = Agent::factory()->create();
        Rating::factory(2)->for($agent)->create();

        $this->assertInstanceOf(HasMany::class, $agent->ratings());
        $this->assertInstanceOf(Collection::class, $agent->ratings);
        $this->assertInstanceOf(Rating::class, $agent->ratings->first());
        $this->assertCount(2, $agent->ratings);
    }

    public function testHasManyRecommendations(): void
    {
        $agent = Agent::factory()->create();
        Recommendation::factory(2)->for($agent)->create();

        $this->assertInstanceOf(HasMany::class, $agent->recommendations());
        $this->assertInstanceOf(Collection::class, $agent->recommendations);
        $this->assertInstanceOf(Recommendation::class, $agent->recommendations->first());
        $this->assertCount(2, $agent->recommendations);
    }
}
