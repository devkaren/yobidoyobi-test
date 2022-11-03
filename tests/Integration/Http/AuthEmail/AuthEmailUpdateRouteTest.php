<?php

namespace Tests\Integration\Http\AuthEmail;

use Infrastructure\Eloquent\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\Integration\AbstractIntegrationTestCase as TestCase;

final class AuthEmailUpdateRouteTest extends TestCase
{
    use LazilyRefreshDatabase, WithFaker;

    public function testUnauthenticated(): void
    {
        $this
            ->json('POST', 'authEmail/update', [
                'email' => $this->faker->safeEmail(),
            ])
            ->assertUnauthorized();
    }

    public function testUpdateEmail(): void
    {
        $user = User::factory()->emailVerified()->create();

        $this
            ->actingAs($user)
            ->json('POST', 'authEmail/update', $request = [
                'email' => $this->faker->unique()->safeEmail(),
            ])
            ->assertOk();

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'email' => $request['email'],
            'email_verification_token' => null,
            'email_verified_at' => null,
        ]);
    }
}
