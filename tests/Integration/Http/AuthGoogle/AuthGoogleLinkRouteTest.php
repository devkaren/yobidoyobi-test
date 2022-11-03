<?php

namespace Tests\Integration\Http\AuthGoogle;

use Infrastructure\Eloquent\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\Integration\AbstractIntegrationTestCase as TestCase;

final class AuthGoogleLinkRouteTest extends TestCase
{
    use LazilyRefreshDatabase, WithFaker;

    public function testUnauthenticated(): void
    {
        $this
            ->json('POST', 'authGoogle/link', [
                'token' => $this->faker->sha1(),
            ])
            ->assertUnauthorized();
    }

    public function testLinkInvalidGoogleAccountToken(): void
    {
        $user = User::factory()->withoutGoogleCredentials()->create();
        $token = $this->faker->sha1();

        $this
            ->expectGoogleUserFetched($token)
            ->andThrowInvalidTokenException();

        $this
            ->actingAs($user)
            ->json('POST', 'authGoogle/link', [
                'token' => $token,
            ])
            ->assertUnprocessable();
    }

    public function testLinkAlreadyLinkedGoogleAccount(): void
    {
        $user = User::factory()->withoutGoogleCredentials()->create();
        $linkedUser = User::factory()->create();
        $token = $this->faker->sha1();

        $this
            ->expectGoogleUserFetched($token)
            ->andReturnUser(['id' => $linkedUser->google_id]);

        $this
            ->actingAs($user)
            ->json('POST', 'authGoogle/link', [
                'token' => $token,
            ])
            ->assertForbidden();
    }

    public function testLinkGoogleAccount(): void
    {
        $user = User::factory()->withoutGoogleCredentials()->create();
        $token = $this->faker->sha1();

        $googleUser = $this
            ->expectGoogleUserFetched($token)
            ->andReturnUser();

        $this
            ->actingAs($user)
            ->json('POST', 'authGoogle/link', [
                'token' => $token,
            ])
            ->assertOk();

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'google_id' => $googleUser->getId(),
        ]);
    }
}
