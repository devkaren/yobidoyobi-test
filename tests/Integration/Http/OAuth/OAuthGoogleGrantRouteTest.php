<?php

namespace Tests\Integration\Http\OAuth;

use Laravel\Passport\Client;
use Infrastructure\Eloquent\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\Integration\AbstractIntegrationTestCase as TestCase;

final class OAuthGoogleGrantRouteTest extends TestCase
{
    use LazilyRefreshDatabase, WithFaker;

    public function testInvalidClient(): void
    {
        $this
            ->json('POST', 'oauth/token', [
                'grant_type' => 'google',
                'client_id' => $this->faker->uuid(),
                'client_secret' => $this->faker->sha256(),
            ])
            ->assertUnauthorized()
            ->assertJsonFragment(['error' => 'invalid_client']);
    }

    public function testRequestWithoutAccessToken(): void
    {
        $client = Client::factory()->create();

        $this
            ->json('POST', 'oauth/token', [
                'grant_type' => 'google',
                'client_id' => $client->id,
                'client_secret' => $client->secret,
            ])
            ->assertStatus(400)
            ->assertJsonFragment(['error' => 'invalid_request']);
    }

    public function testRequestWithNonStringAccessToken(): void
    {
        $client = Client::factory()->create();

        $this
            ->json('POST', 'oauth/token', [
                'grant_type' => 'google',
                'client_id' => $client->id,
                'client_secret' => $client->secret,
                'token' => [$this->faker->uuid()],
            ])
            ->assertStatus(400)
            ->assertJsonFragment(['error' => 'invalid_request']);
    }

    public function testRequestWithInvalidAccessToken(): void
    {
        $client = Client::factory()->create();
        $accessToken = $this->faker->sha1();

        $this
            ->expectGoogleUserFetched($accessToken)
            ->andThrowInvalidTokenException();

        $this
            ->json('POST', 'oauth/token', [
                'grant_type' => 'google',
                'client_id' => $client->id,
                'client_secret' => $client->secret,
                'token' => $accessToken,
            ])
            ->assertStatus(400);
    }

    public function testNoUserWithAssignedGoogleAccount(): void
    {
        $client = Client::factory()->create();
        $accessToken = $this->faker->sha1();

        $this
            ->expectGoogleUserFetched($accessToken)
            ->andReturnUser();

        $this
            ->json('POST', 'oauth/token', [
                'grant_type' => 'google',
                'client_id' => $client->id,
                'client_secret' => $client->secret,
                'token' => $accessToken,
            ])
            ->assertStatus(400);
    }

    public function testIssueAccessToken(): void
    {
        $client = Client::factory()->create();
        $user = User::factory()->create();
        $accessToken = $this->faker->sha1();

        $this
            ->expectGoogleUserFetched($accessToken)
            ->andReturnUser(['id' => $user->google_id]);

        $this
            ->json('POST', 'oauth/token', [
                'grant_type' => 'google',
                'client_id' => $client->id,
                'client_secret' => $client->secret,
                'token' => $accessToken,
            ])
            ->assertOk();
    }
}
