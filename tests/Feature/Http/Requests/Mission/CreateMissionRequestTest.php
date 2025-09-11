<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Requests\Mission;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class CreateMissionRequestTest extends TestCase
{
    use RefreshDatabase;

    private string $route;

    public function testFailsWithoutRequiredFields(): void
    {
        $this->postJson($this->route, [])
            ->assertUnprocessable()
            ->assertJsonValidationErrors([
                'title', 'location', 'published', 'type', 'budget', 'service_id',
            ]);
    }

    public function testFailPublishedNotBoolean(): void
    {
        $this->postJson($this->route, ['published' => 'NOT_BOOLEAN'])
            ->assertUnprocessable()
            ->assertJsonValidationErrorFor('published');
    }

    public function testFailsWithIncorrectType(): void
    {
        $this->postJson($this->route, ['type' => 'INCORRECT'])
            ->assertUnprocessable()
            ->assertJsonValidationErrorFor('type');
    }

    public function testWithNonUuidOfService(): void
    {
        $this->postJson($this->route, ['service_id' => 'NOT_AN_UUID'])
            ->assertUnprocessable()
            ->assertJsonValidationErrorFor('service_id');
    }

    public function testWithNonExistingService(): void
    {
        $this->postJson($this->route, ['service_id' => Str::uuid()->toString()])
            ->assertUnprocessable()
            ->assertJsonValidationErrorFor('service_id');
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware();
        $this->route = route('missions.store');
    }
}
