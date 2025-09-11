<?php

declare(strict_types=1);

namespace Tests\Feature\Models;

use App\Models\Agent;
use App\Models\AgentContact;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AgentContactTest extends TestCase
{
    use RefreshDatabase;

    public function testBelongsToAgent(): void
    {
        $agent = Agent::factory()->create();
        $contact = AgentContact::factory()->for($agent)->create();

        $this->assertInstanceOf(BelongsTo::class, $contact->agent());
        $this->assertInstanceOf(Agent::class, $contact->agent);
        $this->assertTrue($contact->agent->is($agent));
    }
}
