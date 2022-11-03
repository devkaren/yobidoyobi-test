<?php

namespace Tests\Integration\Http\AuthEmail;

use Infrastructure\Eloquent\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\Integration\AbstractIntegrationTestCase as TestCase;

final class AuthEmailVerifyRouteTest extends TestCase
{
    use LazilyRefreshDatabase, WithFaker;

    public function testInvalidEmailAndToken(): void
    {
        $this
            ->json('POST', 'authEmail/verify', [
                'email' => $this->faker->safeEmail(),
                'token' => $this->faker->sha1(),
            ])
            ->assertStatus(400);
    }

    public function testInvalidToken(): void
    {
        $user = User::factory()->emailVerified(false)->create();

        $this
            ->json('POST', 'authEmail/verify', [
                'email' => $user->email,
                'token' => $this->faker->uuid(),
            ])
            ->assertStatus(400);
    }

    public function testVerifyEmail(): void
    {
        $user = User::factory()->emailVerified(false)->create();
        $now = $this->fakeCurrentTimestamp();

        $this
            ->json('POST', 'authEmail/verify', [
                'email' => $user->email,
                'token' => $user->email_verification_token,
            ])
            ->assertOk();

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'email_verification_token' => null,
            'email_verified_at' => $now,
        ]);
    }
}
