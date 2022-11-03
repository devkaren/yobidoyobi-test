<?php

namespace Tests\Integration\Http\AuthProfile;

use Infrastructure\Eloquent\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\Integration\AbstractIntegrationTestCase as TestCase;

final class AuthProfileUpdateRouteTest extends TestCase
{
    use LazilyRefreshDatabase, WithFaker;

    private function generateRequest(array $base = []): array
    {
        return [
            'firstName' => $this->faker->firstName(),
            'lastName' => $this->faker->lastName(),
            ...$base,
        ];
    }

    public function testUnauthenticated(): void
    {
        $this
            ->json('PUT', 'authProfile', $this->generateRequest())
            ->assertUnauthorized();
    }

    public function testUpdateProfile(): void
    {
        $user = User::factory()->create();

        $this
            ->actingAs($user)
            ->json('PUT', 'authProfile', $request = $this->generateRequest())
            ->assertOk();

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'first_name' => $request['firstName'],
            'last_name' => $request['lastName'],
        ]);
    }
}
