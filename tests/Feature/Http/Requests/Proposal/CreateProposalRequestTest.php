<?php

declare(strict_types=1);

namespace Feature\Http\Requests\Proposal;

use App\Models\Mission;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateProposalRequestTest extends TestCase
{
    use RefreshDatabase;

    private Mission $mission;

    private User $agent;

    private string $route;

    public function testOnlyAgentCanPostProposal(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->postJson($this->route)
            ->assertForbidden();

        $customer = User::factory()->customer()->create();

        $this->actingAs($customer)
            ->postJson($this->route)
            ->assertForbidden();
    }

    public function testPriceMustBeInteger(): void
    {
        $this->actingAs($this->agent)
            ->postJson($this->route, ['price' => 'not_an_integer'])
            ->assertUnprocessable()
            ->assertJsonValidationErrorFor('price');
    }

    public function testMinPrice(): void
    {
        $this->actingAs($this->agent)
            ->postJson($this->route, ['price' => 0])
            ->assertUnprocessable()
            ->assertJsonValidationErrorFor('price');
    }

    public function testPricingUnitRequiredIfPriceWasPosted(): void
    {
        $this->actingAs($this->agent)
            ->postJson($this->route, ['price' => 100])
            ->assertUnprocessable()
            ->assertJsonValidationErrorFor('pricing_unit');
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->agent = User::factory()->agent()->create();
        $this->mission = Mission::factory()->create();
        $this->route = route('missions.proposals.store', ['mission' => $this->mission]);
    }
}
