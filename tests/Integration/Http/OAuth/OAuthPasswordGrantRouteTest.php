<?php

namespace Tests\Integration\Http\OAuth;

use Laravel\Passport\Client;
use Infrastructure\Eloquent\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Infrastructure\Eloquent\Models\UserTrustedDevice;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\Integration\AbstractIntegrationTestCase as TestCase;

final class OAuthPasswordGrantRouteTest extends TestCase
{
    use LazilyRefreshDatabase, WithFaker;

    public function testInvalidClient(): void
    {
        $this
            ->json('POST', 'oauth/token', [
                'grant_type' => 'password',
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
                'grant_type' => 'password',
                'client_id' => $this->faker->sha1(),
                'client_secret' => $this->faker->sha256(),
            ])
            ->assertUnauthorized()
            ->assertJsonFragment(['error' => 'invalid_client']);
    }

    public function testRequestWithoutUsernameAndPassword(): void
    {
        $client = Client::factory()->create(['password_client' => true]);

        $this
            ->json('POST', 'oauth/token', [
                'grant_type' => 'password',
                'client_id' => $client->id,
                'client_secret' => $client->secret,
            ])
            ->assertStatus(400)
            ->assertJsonFragment(['error' => 'invalid_request']);
    }

    public function testRequestWithoutPassword(): void
    {
        $client = Client::factory()->create(['password_client' => true]);

        $this
            ->json('POST', 'oauth/token', [
                'grant_type' => 'password',
                'client_id' => $client->id,
                'client_secret' => $client->secret,
                'username' => $this->faker->email(),
            ])
            ->assertStatus(400)
            ->assertJsonFragment(['error' => 'invalid_request']);
    }

    public function testRequestWithInvalidCredentials(): void
    {
        $client = Client::factory()->create(['password_client' => true]);

        $this
            ->json('POST', 'oauth/token', [
                'grant_type' => 'password',
                'client_id' => $client->id,
                'client_secret' => $client->secret,
                'username' => $this->faker->email(),
                'password' => $this->faker->password(),
            ])
            ->assertStatus(400)
            ->assertJsonFragment(['error' => 'invalid_grant']);
    }

    public function testRequestWithInvalidPassword(): void
    {
        $client = Client::factory()->create(['password_client' => true]);
        $user = User::factory()->withoutGoogle2FA()->create();

        $this
            ->json('POST', 'oauth/token', [
                'grant_type' => 'password',
                'client_id' => $client->id,
                'client_secret' => $client->secret,
                'username' => $user->email,
                'password' => $this->faker->uuid(),
            ])
            ->assertStatus(400)
            ->assertJsonFragment(['error' => 'invalid_grant']);
    }

    public function testRequestWithUserCredentials(): void
    {
        $client = Client::factory()->create(['password_client' => true]);
        $user = User::factory()->withoutGoogle2FA()->create();

        $this
            ->json('POST', 'oauth/token', [
                'grant_type' => 'password',
                'client_id' => $client->id,
                'client_secret' => $client->secret,
                'username' => $user->email,
                'password' => 'password',
            ])
            ->assertOk()
            ->assertJsonStructure(['access_token']);
    }

    public function testRequestDoNotRequireOtpWhenDeviceIsTrusted(): void
    {
        $client = Client::factory()->create(['password_client' => true]);

        $user = User::factory()
            ->has(UserTrustedDevice::factory()->current())
            ->create();

        $this
            ->json('POST', 'oauth/token', [
                'grant_type' => 'password',
                'client_id' => $client->id,
                'client_secret' => $client->secret,
                'username' => $user->email,
                'password' => 'password',
            ])
            ->assertOk()
            ->assertJsonStructure(['access_token']);
    }

    public function testRequestDoNotRequireOtpWhenGoogle_2faIsNotEnabled(): void
    {
        $client = Client::factory()->create(['password_client' => true]);

        $user = User::factory()->create([
            'google_2fa_enabled' => false,
        ]);

        $this
            ->json('POST', 'oauth/token', [
                'grant_type' => 'password',
                'client_id' => $client->id,
                'client_secret' => $client->secret,
                'username' => $user->email,
                'password' => 'password',
            ])
            ->assertOk()
            ->assertJsonStructure(['access_token']);
    }

    public function testRequestRequireOtpWhenTrustedDeviceIsExpired(): void
    {
        $client = Client::factory()->create(['password_client' => true]);

        $user = User::factory()
            ->has(UserTrustedDevice::factory()->expired()->current())
            ->create();

        $this
            ->json('POST', 'oauth/token', [
                'grant_type' => 'password',
                'client_id' => $client->id,
                'client_secret' => $client->secret,
                'username' => $user->email,
                'password' => 'password',
            ])
            ->assertStatus(400)
            ->assertJsonFragment(['error' => 'invalid_otp']);
    }

    public function testRequestWithInvalidOtp(): void
    {
        $client = Client::factory()->create(['password_client' => true]);
        $user = User::factory()->create();

        $this->expectGoogle2FAKeyVerified(
            $user->google_2fa_secret,
            $otp = $this->faker->otp(),
            false,
        );

        $this
            ->json('POST', 'oauth/token', [
                'grant_type' => 'password',
                'client_id' => $client->id,
                'client_secret' => $client->secret,
                'username' => $user->email,
                'password' => 'password',
                'otp' => $otp,
            ])
            ->assertStatus(400)
            ->assertJsonFragment(['error' => 'invalid_otp']);
    }

    public function testRequestWithValidOtp(): void
    {
        $client = Client::factory()->create(['password_client' => true]);
        $user = User::factory()->create();

        $this->expectGoogle2FAKeyVerified(
            $user->google_2fa_secret,
            $otp = $this->faker->otp(),
            true,
        );

        $this
            ->json('POST', 'oauth/token', [
                'grant_type' => 'password',
                'client_id' => $client->id,
                'client_secret' => $client->secret,
                'username' => $user->email,
                'password' => 'password',
                'otp' => $otp,
            ])
            ->assertOk()
            ->assertJsonStructure(['access_token']);
    }

    public function testRequestSaveTrustedDevice(): void
    {
        $client = Client::factory()->create(['password_client' => true]);
        $user = User::factory()->create();
        $now = $this->fakeCurrentTimestamp();

        $this->expectGoogle2FAKeyVerified(
            $user->google_2fa_secret,
            $otp = $this->faker->otp(),
            true,
        );

        $this
            ->json('POST', 'oauth/token', [
                'grant_type' => 'password',
                'client_id' => $client->id,
                'client_secret' => $client->secret,
                'username' => $user->email,
                'password' => 'password',
                'otp' => $otp,
                'trusted' => true,
            ])
            ->assertOk()
            ->assertJsonStructure(['access_token']);

        $this->assertDatabaseHas('user_trusted_devices', [
            'user_id' => $user->id,
            'ip' => '127.0.0.1',
            'user_agent' => 'Symfony',
            'valid_to' => $now->addMonth(),
        ]);
    }

    public function testRequestProlongeExpiredTrustedDevice(): void
    {
        $client = Client::factory()->create(['password_client' => true]);
        $user = User::factory()->create();

        $device = UserTrustedDevice::factory()->expired()->current()->create([
            'user_id' => $user->id,
        ]);

        $now = $this->fakeCurrentTimestamp();

        $this->expectGoogle2FAKeyVerified(
            $user->google_2fa_secret,
            $otp = $this->faker->otp(),
            true,
        );

        $this
            ->json('POST', 'oauth/token', [
                'grant_type' => 'password',
                'client_id' => $client->id,
                'client_secret' => $client->secret,
                'username' => $user->email,
                'password' => 'password',
                'otp' => $otp,
                'trusted' => true,
            ])
            ->assertOk()
            ->assertJsonStructure(['access_token']);

        $this->assertDatabaseHas('user_trusted_devices', [
            'id' => $device->id,
            'user_id' => $user->id,
            'ip' => $device->ip,
            'user_agent' => $device->user_agent,
            'valid_to' => $now->addMonth(),
        ]);
    }

    public function testInvalidOtpRecoveryCode(): void
    {
        $client = Client::factory()->create(['password_client' => true]);
        $user = User::factory()->create();

        $this
            ->json('POST', 'oauth/token', [
                'grant_type' => 'password',
                'client_id' => $client->id,
                'client_secret' => $client->secret,
                'username' => $user->email,
                'password' => 'password',
                'otp_recovery_code' => '0',
            ])
            ->assertStatus(400)
            ->assertJsonFragment(['error' => 'invalid_otp_recovery_code']);
    }

    public function testLoginUsingRecoveryCode(): void
    {
        $client = Client::factory()->create(['password_client' => true]);
        $user = User::factory()->create();

        $this
            ->json('POST', 'oauth/token', [
                'grant_type' => 'password',
                'client_id' => $client->id,
                'client_secret' => $client->secret,
                'username' => $user->email,
                'password' => 'password',
                'otp_recovery_code' => $user->google_2fa_recovery_code,
            ])
            ->assertOk()
            ->assertJsonStructure(['access_token']);
    }
}
