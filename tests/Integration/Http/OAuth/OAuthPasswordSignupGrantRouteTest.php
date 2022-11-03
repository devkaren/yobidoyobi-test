<?php

namespace Tests\Integration\Http\OAuth;

use Laravel\Passport\Client;
use Illuminate\Support\Facades\Hash;
use Infrastructure\Eloquent\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\Integration\AbstractIntegrationTestCase as TestCase;

final class OAuthPasswordSignupGrantRouteTest extends TestCase
{
    use LazilyRefreshDatabase, WithFaker;

    public function testInvalidClient(): void
    {
        $this
            ->json('POST', 'oauth/token', [
                'grant_type' => 'password_signup',
                'client_id' => $this->faker->uuid(),
                'client_secret' => $this->faker->sha256(),
            ])
            ->assertUnauthorized()
            ->assertJsonFragment(['error' => 'invalid_client']);
    }

    public function testNonUuidClientId(): void
    {
        $this
            ->json('POST', 'oauth/token', [
                'grant_type' => 'password_signup',
                'client_id' => $this->faker->sha1(),
                'client_secret' => $this->faker->sha256(),
            ])
            ->assertUnauthorized()
            ->assertJsonFragment(['error' => 'invalid_client']);
    }

    public function testRequestWithoutPasswordCredentials(): void
    {
        $client = Client::factory()->create();

        $this
            ->json('POST', 'oauth/token', [
                'grant_type' => 'password_signup',
                'client_id' => $client->id,
                'client_secret' => $client->secret,
                'first_name' => $this->faker->firstName(),
                'last_name' => $this->faker->lastName(),
            ])
            ->assertStatus(400)
            ->assertJsonFragment(['error' => 'invalid_request']);
    }

    public function testCreateUserAndSendEmailVerificationLink(): void
    {
        $client = Client::factory()->create();
        $now = $this->fakeCurrentTimestamp();

        $this
            ->json('POST', 'oauth/token', $request = [
                'grant_type' => 'password_signup',
                'client_id' => $client->id,
                'client_secret' => $client->secret,
                'username' => $this->faker->safeEmail(),
                'password' => 'Password1234~',
                'first_name' => $this->faker->firstName(),
                'last_name' => $this->faker->lastName(),
            ])
            ->assertOk()
            ->assertJsonStructure([
                'access_token',
                'expires_in',
                'refresh_token',
            ]);

        $this->assertDatabaseHas('users', [
            'email' => $request['username'],
            'first_name' => $request['first_name'],
            'last_name' => $request['last_name'],
            'email_verified_at' => null,
            'registered_at' => $now,
        ]);

        $this->assertTrue(Hash::check(
            $request['password'],
            User::whereEmail($request['username'])->value('password'),
        ));
    }
}
