<?php

namespace Tests\Integration\Http\AuthFacebook;

use Infrastructure\Eloquent\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\Integration\AbstractIntegrationTestCase as TestCase;

final class AuthFacebookLinkRouteTest extends TestCase
{
    use LazilyRefreshDatabase, WithFaker;

    public function testUnauthenticated(): void
    {
        $this
            ->json('POST', 'authFacebook/link', [
                'token' => $this->faker->sha1(),
            ])
            ->assertUnauthorized();
    }

    public function testLinkInvalidFacebookAccountToken(): void
    {
        $user = User::factory()->withoutFacebookCredentials()->create();
        $token = $this->faker->sha1();

        $this
            ->expectFacebookUserFetched($token)
            ->andThrowInvalidTokenException();

        $this
            ->actingAs($user)
            ->json('POST', 'authFacebook/link', [
                'token' => $token,
            ])
            ->assertUnprocessable();
    }

    public function testLinkAlreadyLinkedFacebookAccount(): void
    {
        $user = User::factory()->withoutFacebookCredentials()->create();
        $linkedUser = User::factory()->create();
        $token = $this->faker->sha1();

        $this
            ->expectFacebookUserFetched($token)
            ->andReturnUser(['id' => $linkedUser->facebook_id]);

        $this
            ->actingAs($user)
            ->json('POST', 'authFacebook/link', [
                'token' => $token,
            ])
            ->assertForbidden();
    }

    public function testLinkFacebookAccount(): void
    {
        $user = User::factory()->withoutFacebookCredentials()->create();
        $token = $this->faker->sha1();

        $facebookUser = $this
            ->expectFacebookUserFetched($token)
            ->andReturnUser();

        $this
            ->actingAs($user)
            ->json('POST', 'authFacebook/link', [
                'token' => $token,
            ])
            ->assertOk();

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'facebook_id' => $facebookUser->getId(),
        ]);
    }
}
